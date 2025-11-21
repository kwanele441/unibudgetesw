<?php
session_start();

if(!isset($_SESSION['loggedUserId'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>uniBudget - Budget Advisor</title>
    <meta name="description" content="Student Budget Advisor">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Existing CSS and JS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/fontello.css">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400;500;700&family=Fredoka+One&family=Roboto:wght@400;700;900&family=Varela+Round&display=swap" rel="stylesheet">
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

        <section class="container-fluid square my-4 py-2">
            <h3>BUDGET ADVISOR FOR STUDENTS</h3>
            <p class="lead">
                Get advice and tips on managing your budget as a student! Check recommendations below or ask the AI advisor a budgeting question.
            </p>
            <ul>
                <li>Create a realistic monthly budget and stick to it.</li>
                <li>Track every expense, even the small ones.</li>
                <li>Look for student discounts and deals.</li>
                <li>Cook at home, avoid eating out often.</li>
                <li>Set saving goals for your needs and wants.</li>
                <li>Review your spending at least once a week.</li>
                <li>Consider a part-time job for extra income.</li>
                <li>Always ask: Is this a need or a want?</li>
                <li>Take advantage of student resources at your campus.</li>
                <li>Share costs with roommates when you can.</li>
                <li>Limit subscriptions and recurring expenses.</li>
            </ul>
        </section>

        <section class="container-fluid square my-4 py-4">
            <h4>Ask the AI Budget Advisor</h4>
            <form method="POST" id="advisorForm">
                <div class="form-group">
                    <label for="question">Type your budgeting question:</label>
                    <input type="text" id="question" name="question" class="form-control" required />
                </div>
                <button type="submit" class="btn btn-primary" style="background-color: green;">Ask Advisor</button>
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['question'])) {
                $question = trim($_POST['question']);

                // Mock AI reply. Replace this with real AI API integration.
                function mock_ai_reply($question) {
                    $response = '';
                    $lower = strtolower($question);
                    if (strpos($lower,"food") !== false) $response = "For students, planning meals and shopping with a list can save money. Try prepping meals for the week!";
                    else if (strpos($lower,"save") !== false) $response = "Start by saving a small fixed amount from every income source you have, even if it's only R20 per week.";
                    else if (strpos($lower,"transport") !== false) $response = "Consider walking or carpooling where you can. Student discounts on buses and trains can also help!";
                    else if (strpos($lower,"study") !== false) $response = "Libraries are a great resource. Free or cheap online materials can also save you tons on study costs.";
                    else $response = "It's always helpful to categorize your expenses and track them. Prioritize needs, set spending limits, and review your budget weekly.";
                    return $response;
                }

                echo "<div class='alert alert-info mt-3'><strong>Advisor reply:</strong> " . mock_ai_reply($question) . "</div>";
            }
            ?>
        </section>

        <div class="modal fade" role="dialog" id="dateModal">
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
                                    <input class="form-control  userInput labeledInput" type="date" name="userStartDate" required>
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
    </main>
    <footer>
        <p>&copy; 2023 uniBudget.com | Designed for Limkokwing University Eswatini</p>
        <p>
            <a href="about.php">About</a> | 
            <a href="contact.php">Contact</a> | 
            <a href="privacy.php">Privacy Policy</a>
        </p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>