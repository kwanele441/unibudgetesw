<?php
	session_start();
	
	if(isset($_SESSION['loggedUserId'])) {
	header('Location: menu.php');
	exit();
	}
	
	$_SESSION['successfulRegistration'] = false;
	
	if(isset($_POST['email'])) {
		$positiveValidation = true;
		
		$userName = $_POST['userName'];
		if((strlen($userName) < 2) || (strlen($userName) > 20)) {
			
			$positiveValidation = false;
			$_SESSION['nameError'] = "Name needs to be between 2 to 20 characters.";
		}
		
		if(!preg_match('/^[A-ZĄĘÓŁŚŻŹĆŃa-ząęółśżźćń]+$/', $userName)) {
			
			$positiveValidation = false;
			$_SESSION['nameError'] = "Name must contain letters only, special characters not allowed.";
		}
		
		$email = $_POST['email'];
		$emailCheck = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if(filter_var($emailCheck, FILTER_VALIDATE_EMAIL) == false || $emailCheck != $email) {
			
			$positiveValidation = false;
			$_SESSION['emailError'] = "Please enter a valid e-mail adress";
		}
		
		$password1 = $_POST['password'];
		$password2 = $_POST['passwordConfirm'];
		
		if(strlen($password1) < 8 || strlen($password1) > 50) {
			
			$positiveValidation = false;
			$_SESSION['passwordError'] = "Password needs to be between 8 to 50 characters.";
		}
		
		if($password1 != $password2) {
			
			$positiveValidation = false;
			$_SESSION['passwordError'] = "Password you have entered does not match.";
		}
		
		$passwordHash = password_hash($password1, PASSWORD_DEFAULT);
		
		$_SESSION['formName']=$userName;
		$_SESSION['formEmail']=$email;
		$_SESSION['formPassword1']=$password1;
		$_SESSION['formPassword2']=$password2;
		
		require_once 'database.php';
				
		$checkEmailQuery = $db->prepare(
		"SELECT user_id
		FROM users
		WHERE email = :email");
		
		$checkEmailQuery -> execute([':email' => $email]);

		$isEmailUsed = $checkEmailQuery -> rowCount();
		
		if($isEmailUsed) {
			
			$positiveValidation = false;
			$_SESSION['emailError'] = "An account with this e-mail adress already exists.";
		}
				
		if($positiveValidation == true) {
			
			$addUserQuery = $db->prepare(
			"INSERT INTO users
			VALUES(NULL, :userName, :email, :passwordHash)");
			$addUserQuery->execute([':userName'=> $userName, ':passwordHash'=> $passwordHash,':email' => $email]);
			
			$getUserId = $db->prepare(
			"SELECT user_id
			FROM users
			WHERE email = :email");
			$getUserId -> execute([':email' => $email]);
			$result = $getUserId -> fetch();
			$userId = $result['user_id'];
			
			$assignIncomeCategoriesToUser = $db->prepare(
			"INSERT INTO user_income_category
			VALUES($userId, 1),($userId, 2),($userId, 3),($userId, 4)");
			$assignIncomeCategoriesToUser -> execute();
			
			$assignExpenseCategoriesToUser = $db->prepare(
			"INSERT INTO user_expense_category
			VALUES($userId, 1),($userId, 2),($userId, 3),($userId, 4),($userId, 5),($userId, 6),($userId, 7),($userId, 8),($userId, 9),($userId, 10),($userId, 11),($userId, 12),($userId, 13),($userId, 14),($userId, 15),($userId, 16),($userId, 17)");
			$assignExpenseCategoriesToUser -> execute();
			
			$assignPaymentMethodsToUser = $db->prepare(
			"INSERT INTO user_payment_method
			VALUES($userId, 1),($userId, 2),($userId, 3)");
			$assignPaymentMethodsToUser -> execute();
			
			$_SESSION['successfulRegistration'] = true;
		}
	}
?>

<!DOCTYPE html>

<html lang="pl">

<head>

	<meta charset="utf-8">
	<title>MyBudget - Your Personal Finance Manager</title>
	<meta name="description" content="Track your income and expenses - avoid overspending!">
	<meta name="keywords" content="expense manager, budget planner, expense tracker, budgeting app, money manager, money management, personal finance management software, finance manager, saving planner">
	<meta name="author" content="Magdalena Słomiany">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<meta http-equiv="X-Ua-Compatible" content="IE=edge">
	
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/fontello.css">
	<link href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400;500;700&family=Fredoka+One&family=Roboto:wght@400;700;900&family=Varela+Round&display=swap" rel="stylesheet">
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<style>
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
            background-color: #007bff;
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
	
	<header>
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
		
		<section class="container-fluid square my-4 py-4">
			
			<form class="col-sm-10 col-md-8 col-lg-6 mx-auto my-2 py-3" method="post">
				
				<div class="row justify-content-around">
				
					<div class="col-sm-8">
					
						<div class="input-group mt-3">
							<div class="input-group-prepend px-1 pt-1 inputIcon">
								<i class="icon-user"></i>
							</div>
							<input class="form-control  userInput" type="text" name="userName" placeholder="name" value="<?php
								if(isset($_SESSION['formName'])) {
									
									echo $_SESSION['formName'];
									unset($_SESSION['formName']);
								}
							?>" required>
						</div>
						
						<?php
							if(isset($_SESSION['nameError'])) {
								
								echo '<div class="text-danger">'.$_SESSION['nameError'].'</div>';
								unset($_SESSION['nameError']);
							}
						?>
						
						<div class="input-group mt-3">
							<div class="input-group-prepend px-1 pt-1 inputIcon">
								<i class="icon-mail-alt"></i>
							</div>
							<input class="form-control  userInput" type="email" name="email" placeholder="email@address.com" value="<?php
								if(isset($_SESSION['formEmail'])) {
									
									echo $_SESSION['formEmail'];
									unset($_SESSION['formEmail']);
								}
							?>" required>
						</div>
						
						<?php
							if(isset($_SESSION['emailError'])) {
								
								echo '<div class="text-danger">'.$_SESSION['emailError'].'</div>';
								unset($_SESSION['emailError']);
							}
						?>
						
						<div class="input-group mt-3">
							<div class="input-group-prepend px-1 pt-1 inputIcon">
							<i class="icon-lock"></i>
							</div>
							<input class="form-control  userInput" type="password" id="password1" name="password" placeholder="password" value="<?php
								if(isset($_SESSION['formPassword1'])) {
									
									echo $_SESSION['formPassword1'];
									unset($_SESSION['formPassword1']);
								}
							?>" required>
						</div>
						
						<?php
							if(isset($_SESSION['passwordError'])) {
								
								echo '<div class="text-danger">'.$_SESSION['passwordError'].'</div>';
								unset($_SESSION['passwordError']);
							}
						?>
						
						<div class="input-group mt-3">
							<div class="input-group-prepend px-1 pt-1 inputIcon">
								<i class="icon-lock"></i>
							</div>
							<input class="form-control  userInput" type="password" id="password2" name="passwordConfirm" placeholder="confirm password" required>
							
						</div>
						
						<div id="passwordCheck">
							<input class="mt-3" type="checkbox" onclick="showPassword()"> Show password
						</div>
						
						<button class="btn btn-lg mt-3 mb-2 signButton" type="submit" data-toggle="modal" data-target="#dateModal">
							<i class="icon-user-plus"></i> Sign up
						</button>
						
					</div>
					
				</div>
				
			</form>
			
		</section>
		
		<div class="modal fade" id="registration" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-body">
				<h3 class="modal-title">Merci !</h3>
			  </div>
			</div>
		  </div>
		</div>
		
		<?php
			if($_SESSION['successfulRegistration'] == true) {
				
				echo "<script>$(document).ready(function(){ $('#registrationModal').modal('show'); });</script>

					<div class='modal fade' id='registrationModal' role='dialog'>
						<div class='modal-dialog col'>
							<div class='modal-content'>
								<div class='modal-header'>
									<h3 class='modal-title'>Successful Registration</h3>
									<a href='index.php'>
										<button type='button' class='close'>&times;</button>
									</a>
								</div>
														
								<div class='modal-body'>
									<p>Thank you for registration! You can now sign in.</p>
								</div>
								<div class='modal-footer'>
									<a href='login.php'>
										<button type='button' class='btn btn-success'>Sign in</button>
									</a>
								</div>
							</div>
						</div>
					</div>"; 
			}
		?>
		
	</main>
	
	<footer>
	
	
        <p>&copy; 2023 uniBudget.com | Designed for Limkokwing University Eswatini</p>
        <p>
            <a href="about.php">About</a> | 
            <a href="contact.php">Contact</a> | 
            <a href="privacy.php">Privacy Policy</a>
        </p>
    
		
	</footer>
	
	<script src="js/bootstrap.min.js"></script>
	<script src="js/budget.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	
</body>

</html>