<?php
	session_start();

	// ---------- GOALS TABLE SQL ----------
	/*
	CREATE TABLE goals (
		goal_id INT AUTO_INCREMENT PRIMARY KEY,
		user_id INT NOT NULL,
		name VARCHAR(100) NOT NULL,
		description TEXT,
		target_amount DECIMAL(10,2),
		target_date DATE,
		achieved TINYINT(1) DEFAULT 0,
		achieved_date DATE,
		created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
		updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		FOREIGN KEY (user_id) REFERENCES users(user_id)
	);
	*/

	if(isset($_SESSION['loggedUserId'])) {
		require_once 'database.php';
		
		// Categories and Payment Methods for logged user
		$expenseCategoryQuery = $db -> prepare(
			"SELECT ec.expense_category
			FROM expense_categories ec NATURAL JOIN user_expense_category uec
			WHERE uec.user_id = :loggedUserId");
		$expenseCategoryQuery -> execute([':loggedUserId'=> $_SESSION['loggedUserId']]);
		$expenseCategoriesOfLoggedUser = $expenseCategoryQuery -> fetchAll();

		$paymentMethodQuery = $db -> prepare(
			"SELECT pm.payment_method
			FROM payment_methods pm NATURAL JOIN user_payment_method upm
			WHERE upm.user_id = :loggedUserId");
		$paymentMethodQuery -> execute([':loggedUserId'=> $_SESSION['loggedUserId']]);
		$paymentMethodsOfLoggedUser = $paymentMethodQuery -> fetchAll();

		$_SESSION['expenseAdded'] = false;
		
		// ---------- ADD EXPENSE PROCESS ----------
		if(isset($_POST['expenseAmount'])) {
			if(!empty($_POST['expenseAmount'])) {
				$positiveValidation = true;
				$expenseAmount = number_format($_POST['expenseAmount'], 2, '.', '');
				$amount = explode('.', $expenseAmount);

				if(!is_numeric($expenseAmount) || strlen($expenseAmount) > 9 || $expenseAmount < 0 || !(isset($amount[1]) && strlen($amount[1]) == 2)) {
					$_SESSION['expenseAmountError'] = "Enter valid positive amount - maximum 6 integer digits and 2 decimal places.";
					$positiveValidation = false;
				}

				$expenseComment = $_POST['expenseComment'];

				if(!empty($expenseComment) && !preg_match('/^[A-ZĄĘÓŁŚŻŹĆŃa-ząęółśżźćń 0-9]+$/', $expenseComment)) {
					$_SESSION['commentError'] = "Comment can contain up to 100 characters - only letters and numbers allowed.";
					$positiveValidation = false;
				}

				$_SESSION['formExpenseAmount'] = $expenseAmount;
				$_SESSION['formExpenseDate'] = $_POST['expenseDate'];
				$_SESSION['formExpensePaymentMethod'] = $_POST['expensePaymentMethod'];
				$_SESSION['formExpenseCategory'] = $_POST['expenseCategory'];
				$_SESSION['formExpenseComment'] = $expenseComment;
			
				if($positiveValidation == true) {
					$addExpenseQuery = $db->prepare(
						"INSERT INTO expenses
						VALUES (NULL, :userId, :expenseAmount, :expenseDate,
						(SELECT payment_method_id FROM payment_methods
						WHERE payment_method=:expensePaymentMethod),
						(SELECT category_id FROM expense_categories
						WHERE expense_category=:expenseCategory),
						:expenseComment)");
					$addExpenseQuery -> execute([
						':userId' => $_SESSION['loggedUserId'],
						':expenseAmount' => $expenseAmount,
						':expenseDate' => $_POST['expenseDate'],
						':expensePaymentMethod' => $_POST['expensePaymentMethod'],
						':expenseCategory' => $_POST['expenseCategory'],
						':expenseComment' => $expenseComment
					]);
					$_SESSION['expenseAdded'] = true;
					unset($_SESSION['formExpenseAmount'], $_SESSION['formExpenseDate'], $_SESSION['formExpensePaymentMethod'], $_SESSION['formExpenseCategory'], $_SESSION['formExpenseComment']);
				}
			} else {
				$_SESSION['emptyFieldError'] = "Please fill in all required fields.";
				$_SESSION['expenseAmountError'] = "Amount of an expense required.";
			}
		}

		// ---------- FETCH EXPENSES ----------
		$expensesQuery = $db->prepare(
		    "SELECT e.*, ec.expense_category, pm.payment_method 
		     FROM expenses e
		     JOIN expense_categories ec ON e.category_id = ec.category_id
		     JOIN payment_methods pm ON e.payment_method_id = pm.payment_method_id
		     WHERE e.user_id = :loggedUserId
		     ORDER BY e.expense_date DESC"
		);
		$expensesQuery->execute([':loggedUserId' => $_SESSION['loggedUserId']]);
		$userExpenses = $expensesQuery->fetchAll();

		// ---------- GOAL SECTION ----------
		// Add Goal Form Handler (can also be moved to add_goal.php!)
		if(isset($_POST['goal_name'])) {
			$name = trim($_POST['goal_name']);
			$description = trim($_POST['goal_description']);
			$target_amount = empty($_POST['goal_target_amount']) ? NULL : number_format($_POST['goal_target_amount'], 2, '.', '');
			$target_date = empty($_POST['goal_target_date']) ? NULL : $_POST['goal_target_date'];

			if($name) {
				$goalAddQuery = $db->prepare(
					"INSERT INTO goals (user_id, name, description, target_amount, target_date) 
					 VALUES (:userId, :name, :description, :target_amount, :target_date)"
				);
				$goalAddQuery->execute([
					':userId' => $_SESSION['loggedUserId'],
					':name' => $name,
					':description' => $description,
					':target_amount' => $target_amount,
					':target_date' => $target_date
				]);
				// Optionally, add feedback messages!
				header("Location: expense.php");
				exit();
			}
		}

		// Fetch user's goals
		$goalsQuery = $db->prepare("SELECT * FROM goals WHERE user_id = :loggedUserId ORDER BY target_date ASC");
		$goalsQuery->execute([':loggedUserId' => $_SESSION['loggedUserId']]);
		$userGoals = $goalsQuery->fetchAll();
	} else {
		header ("Location: index.php");
		exit();
	}
?>

<!DOCTYPE html>
<html lang="pl">
<head>

<meta charset="utf-8">
    <title>uniBudget - Your Personal Finance Manager</title>
    <meta name="description" content="Track your income and expenses - avoid overspending!">
    <meta name="keywords" content="expense manager, budget planner, expense tracker, budgeting app, money manager, money management, personal finance management software, finance manager, saving planner">
    <meta name="author" content="Magdalena Słomiany">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-Ua-Compatible" content="IE=edge">
    
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/fontello.css">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400;500;700&family=Fredoka+One&family=Roboto:wght@400;700;900&family=Varela+Round&display=swap" rel="stylesheet">
    
    <style>
        body {
			body {
            background-color: blue;
            color: #333;
        }
        header {
            background-color: #28a745;
            color: #fff;
            padding: 20px 0;
            position: relative;
        }
        .header-logo {
            position: absolute;
            top: 50%;
            left: 32px;
            transform: translateY(-50%);
            width: 90px;
            height: 90px;
            object-fit: contain;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,123,255,0.18);
            background: #fff;
        }
        .header-center {
            text-align: center;
        }
        .header-title {
            margin-bottom: 2px;
            font-size: 2.1em;
            font-weight: bold;
            line-height: .98em;
        }
        .header-subtitle {
            color: #e0e0e0;
            font-size: 1.08em;
            margin-top: 4px;
        }
        #title a {
            color: #fff; /* Ensures .com stays white */
            text-decoration: none;
        }
        #logo {
            color: #fff !important; /* UniBudget in white */
        }
        @media (max-width: 600px) {
            .header-logo { width: 65px; height: 65px; left: 10px;}
            .header-title { font-size: 1.3em;}
        }
        .navbar {
            /* background-color: #28a745; */
            width: 100%;
        }
        .navbar-nav .nav-link {
            color: #fff;
            font-weight: bold;
        }
        .navbar-nav .nav-link:hover {
            color: #d9e2ec;
        }
        .square {
            background-color: #eaf5ea;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }
        footer {
            background-color: #28a745;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }
        footer a {
            color: #fff;
            text-decoration: none;
        }
        footer a:hover {
            color: #d9e2ec;
        }
    </style>
</head>
<body>
	
<header style="min-height: 130px;">
		<img src="logo2.png" alt="uniBudget Logo" class="header-logo">
		<div class="header-center">
			<h1 class="header-title" id="title">
				<a id="homeButton" href="index.php" role="button">
					<span id="logo">UniBudget</span>.com
				</a>
			</h1>
			<p class="header-subtitle" id="subtitle">Your Personal Finance Manager</p>
		</div>
	</header>
	
	<main>
		
		<section class="container-fluid square my-4 py-2">
		<nav class="navbar navbar-dark navbar-expand-lg">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainMenu" aria-controls="mainMenu" aria-expanded="false" aria-label="Navigation Toggler">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="mainMenu">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="menu.php"><i class="icon-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="income.php"><i class="icon-money-1"></i> Add Income</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="expense.php"><i class="icon-dollar"></i> Add Expense</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="ads.php"><i class="icon-megaphone"></i> Local Deals</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="balanceDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-chart-pie"></i> View Balance
                    </a>
                    <div class="dropdown-menu" aria-labelledby="balanceDropdown">
                        <?php
                            $userStartDate = date('Y-m-01');
                            $userEndDate = date('Y-m-t');
                            echo '<a class="dropdown-item" href="balance.php?userStartDate='.$userStartDate.'&userEndDate='.$userEndDate.'">Current Month</a>';
                        ?>
                        <?php
                            $userStartDate = date('Y-m-01', strtotime("last month"));
                            $userEndDate = date('Y-m-t', strtotime("last month"));
                            echo '<a class="dropdown-item" href="balance.php?userStartDate='.$userStartDate.'&userEndDate='.$userEndDate.'">Last Month</a>';
                        ?>
                        <?php
                            $userStartDate = date('Y-01-01');
                            $userEndDate = date('Y-12-31');
                            echo '<a class="dropdown-item" href="balance.php?userStartDate='.$userStartDate.'&userEndDate='.$userEndDate.'">Current Year</a>';
                        ?>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#dateModal">Custom</a>
                    </div>
                </li>
				<li class="nav-item">
                    <a class="nav-link" href="budget_advisor.php"><i class=""></i> Budget Advisor</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-cog-alt"></i> Settings
                    </a>
                    <div class="dropdown-menu" aria-labelledby="settingsDropdown">
                        <h6 class="dropdown-header">Profile settings</h6>
                        <a class="dropdown-item" href="#">Name</a>
                        <a class="dropdown-item" href="#">Password</a>
                        <a class="dropdown-item" href="#">E-mail Address</a>
                        <div class="dropdown-divider"></div>
                        <h6 class="dropdown-header">Category settings</h6>
                        <a class="dropdown-item" href="#">Income</a>
                        <a class="dropdown-item" href="#">Expense</a>
                        <a class="dropdown-item" href="#">Payment Methods</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="icon-logout"></i> Sign out</a>
                </li>
            </ul>
        </div>
    </nav>
		</section>

		<!-- ADD EXPENSE FORM -->
		<section class="container-fluid square my-4 py-4">
			<form class="col-sm-10 col-md-8 col-lg-6 py-3 mx-auto" method="post">
				<h3>ADDING AN EXPENSE</h3>
				<div class="row justify-content-around">
					<div class="col-sm-10 col-lg-8">
						<?php
							if(isset($_SESSION['emptyFieldError'])) {
								echo '<div class="text-danger">'.$_SESSION['emptyFieldError'].'</div>';
								unset($_SESSION['emptyFieldError']);
							}
						?>
						<div class="input-group mt-3">
							<div class="input-group-prepend px-1">
								<span class="input-group-text">Amount</span>
							</div>
							<input class="form-control userInput labeledInput" type="number" name="expenseAmount" step="0.01" value="<?php
								if(isset($_SESSION['formExpenseAmount'])) {
									echo $_SESSION['formExpenseAmount'];
									unset($_SESSION['formExpenseAmount']);
								}
							?>">
						</div>
						<?php
							if(isset($_SESSION['expenseAmountError'])) {
								echo '<div class="text-danger">'.$_SESSION['expenseAmountError'].'</div>';
								unset($_SESSION['expenseAmountError']);
							}
						?>
						<div class="input-group mt-3">
							<div class="input-group-prepend px-1">
								<span class="input-group-text">Date</span>
							</div>
							<?php
								if(!isset($_SESSION['formExpenseDate'])) {
									echo "<script>$(document).ready(function(){getCurrentDate();})</script>";
								}
							?>
							<input class="form-control  userInput labeledInput" type="date" name="expenseDate" id="dateInput" value="<?php
								if(isset($_SESSION['formExpenseDate'])) {
									echo $_SESSION['formExpenseDate'];
									unset($_SESSION['formExpenseDate']);
								}
							?>" required>
						</div>
						<div class="input-group mt-3">
							<div class="input-group-prepend px-1">
								<span class="input-group-text">Payment Method</span>
							</div>
							<select class="form-control userInput labeledInput" name="expensePaymentMethod">
								<?php
									foreach ($paymentMethodsOfLoggedUser as $payment_method) {
										if(isset($_SESSION['formExpensePaymentMethod']) && $_SESSION['formExpensePaymentMethod'] == $payment_method['payment_method']) {
											echo '<option selected>'.$payment_method['payment_method'].'</option>';
											unset($_SESSION['formExpensePaymentMethod']);
										} else {
											echo '<option>'.$payment_method['payment_method'].'</option>';
										}
									}
								?>
							</select>
						</div>
						<div class="input-group mt-3">
							<div class="input-group-prepend px-1">
								<span class="input-group-text">Category</span>
							</div>
							<select class="form-control userInput labeledInput" name="expenseCategory">
								<?php
									foreach ($expenseCategoriesOfLoggedUser as $category) {
										if(isset($_SESSION['formExpenseCategory']) && $_SESSION['formExpenseCategory'] == $category['expense_category']) {
											echo '<option selected>'.$category['expense_category']."</option>";
											unset($_SESSION['formExpenseCategory']);
										} else {
											echo "<option>".$category['expense_category']."</option>";
										}
									}
								?>
							</select>
						</div>
						<div class="input-group mt-3">
							<div class="input-group-prepend px-1">
								<span class="input-group-text">Comments<br />(optional)</span>
							</div>
							<textarea class="form-control userInput labeledInput" name="expenseComment" rows="5"><?php
									if(isset($_SESSION['formExpenseComment'])) {
										echo $_SESSION['formExpenseComment'];
										unset($_SESSION['formExpenseComment']);
									}
								?></textarea>
						</div>
						<?php
							if(isset($_SESSION['commentError'])) {
								echo '<div class="text-danger">'.$_SESSION['commentError'].'</div>';
								unset($_SESSION['commentError']);
							}
						?>
					</div>
					<div class="col-md-11">
						<button class="btn-lg mt-3 mb-2 mx-1 signButton bg-primary" type="submit">
							<i class="icon-floppy"></i> Save
						</button>
						<a data-toggle="modal" data-target="#discardExpenseModal">
							<button class="btn-lg mt-3 mb-2 mx-1 signButton bg-danger" type="button">
								<i class="icon-cancel-circled"></i> Cancel
							</button>
						</a>
					</div>
				</div>
			</form>
		</section>
		
		<!-- VIEW & EDIT EXPENSES SECTION -->
		<section class="container-fluid square my-4 py-4">
			<h3>VIEW & EDIT EXPENSES</h3>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Amount</th>
						<th>Date</th>
						<th>Payment Method</th>
						<th>Category</th>
						<th>Comment</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($userExpenses as $expense) { ?>
						<tr>
							<td><?= number_format($expense['expense_amount'], 2) ?></td>
							<td><?= htmlspecialchars($expense['expense_date']) ?></td>
							<td><?= htmlspecialchars($expense['payment_method']) ?></td>
							<td><?= htmlspecialchars($expense['expense_category']) ?></td>
							<td><?= htmlspecialchars($expense['expense_comment']) ?></td>
							<td>
								<a href="edit_expense.php?id=<?= $expense['expense_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
								<a href="delete_expense.php?id=<?= $expense['expense_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this expense?');">Delete</a>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</section>

		<!-- GOALS SECTION -->
		<section class="container-fluid square my-4 py-4">
			<h3>GOALS</h3>
			<!-- Add Goal Form -->
			<form class="mb-3 col-sm-10 col-md-8 col-lg-6 py-3 mx-auto" method="post">
				<div class="form-group">
					<label for="goal_name">Goal Name</label>
					<input type="text" name="goal_name" id="goal_name" class="form-control" required maxlength="100">
				</div>
				<div class="form-group">
					<label for="goal_description">Description (optional)</label>
					<textarea name="goal_description" id="goal_description" class="form-control"></textarea>
				</div>
				<div class="form-group">
					<label for="goal_target_amount">Target Amount (optional)</label>
					<input type="number" name="goal_target_amount" id="goal_target_amount" step="0.01" class="form-control">
				</div>
				<div class="form-group">
					<label for="goal_target_date">Target Date (optional)</label>
					<input type="date" name="goal_target_date" id="goal_target_date" class="form-control">
				</div>
				<button type="submit" class="btn btn-success">Add Goal</button>
			</form>

			<table class="table table-striped">
				<thead>
					<tr>
						<th>Name</th>
						<th>Target Amount</th>
						<th>Target Date</th>
						<th>Description</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($userGoals as $goal) { 
						$isAchieved = $goal['achieved'];
						$targetDate = $goal['target_date'];
						$dateObj = $targetDate ? new DateTime($targetDate) : null;
						$todayObj = new DateTime();
						$daysLeft = $dateObj ? $todayObj->diff($dateObj)->days : null;
						$isPast = $dateObj && $dateObj < $todayObj;
						$inCountdown = $dateObj && $daysLeft != false && $daysLeft <= 5 && !$isAchieved && !$isPast;
					?>
						<tr>
							<td><?= htmlspecialchars($goal['name']) ?></td>
							<td><?= ($goal['target_amount'] !== null ? number_format($goal['target_amount'], 2) : '') ?></td>
							<td><?= htmlspecialchars($goal['target_date']) ?></td>
							<td><?= htmlspecialchars($goal['description']) ?></td>
							<td>
								<?php if ($isAchieved): ?>
									<span class="achieved-badge">Achieved</span>
									<?php if($goal['achieved_date']) echo '<br/><small>' . htmlspecialchars($goal['achieved_date']) . '</small>'; ?>
								<?php else: ?>
									<?php if ($targetDate && $inCountdown): ?>
										<span class="countdown-badge">Countdown: <?= $daysLeft ?> day<?= $daysLeft != 1 ? 's' : '' ?> left</span>
									<?php elseif($isPast): ?>
										<span class="badge badge-danger">Target date passed!</span>
									<?php else: ?>
										<span class="badge badge-warning">In Progress</span>
									<?php endif; ?>
								<?php endif; ?>
							</td>
							<td>
								<?php if (!$isAchieved): ?>
									<a href="mark_goal_achieved.php?id=<?= $goal['goal_id'] ?>" class="btn btn-sm btn-success">Mark as Achieved</a>
								<?php endif; ?>
								<a href="delete_goal.php?id=<?= $goal['goal_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this goal?');">Delete</a>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</section>

		<!-- MODALS section from your template unchanged -->
		<div class="modal fade" role='dialog' id="dateModal">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title">Selecting time period</h3>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<form class="col py-3 mx-auto" action="balance.php" method="get">
						<div class="modal-body">
							<h5>Enter a start date and an end date of period that you want to review</h5>
							<div class="row justify-content-around py-2">
								<div class="form-group my-2">
									<label for="startDate">Enter start date</label>
									<input class="form-control userInput labeledInput" type="date" name="userStartDate" required>
								</div>
								<div class="form-group my-2">
									<label for="endDate">Enter end date</label>
									<input class="form-control userInput labeledInput" type="date" name="userEndDate" required>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button class="btn btn-primary" type="submit">Save</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php
			if($_SESSION['expenseAdded']){
				echo "<script>$(document).ready(function(){ $('#expenseAdded').modal('show'); });</script>
				<div class='modal fade' id='expenseAdded' role='dialog'>
					<div class='modal-dialog col'>
						<div class='modal-content'>
							<div class='modal-header'>
								<h3 class='modal-title'>New Expense Added</h3>
								<a href='income.php'>
								<button type='button' class='close'>&times;</button>
								</a>
							</div>
							<div class='modal-body'>
								<p>Your expense has been successfully added.</p>
							</div>
							<div class='modal-footer'>
								<a href='menu.php'>
									<button type='button' class='btn btn-success'>OK</button>
								</a>
							</div>
						</div>
					</div>
				</div>"; 
				$_SESSION['expenseAdded'] = false;
			}
		?>
		<div class="modal hide fade in" data-backdrop="static" id="discardExpenseModal">
			<div class='modal-dialog col'>
				<div class='modal-content'>
					<div class='modal-header'>
						<h3 class='modal-title'>Quit Adding Expense?</h3>
						<a href='expense.php'>
						<button type='button' class='close'>&times;</button>
						</a>
					</div>
					<div class='modal-body'>
						<p>Data you have entered so far will not be saved.</p>
					</div>
					<div class='modal-footer'>
						<a href='menu.php'>
							<button type='button' class='btn btn-success'>YES</button>
						</a>
						<button type="button" class="btn btn-danger" data-dismiss="modal">NO</button>
					</div>
				</div>
			</div>
		</div>
	</main>

	<footer>
        <p>&copy; 2023 uniBudget.com | Designed for Limkokwing University Eswatini</p>
        <p>
            <a href="about.php">About</a> | 
            <a href="contact.php">Contact</a> | 
            <a href="privacy.php">Privacy Policy</a>
        </p>
    </footer>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" crossorigin="anonymous"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>