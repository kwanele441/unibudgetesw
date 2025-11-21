<?php
    session_start();

    require_once 'database.php';
    
    if (!isset($_SESSION['loggedUserId'])) {
        if (isset($_POST['email'])) {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = filter_input(INPUT_POST, 'password');
        
            $userQuery = $db->prepare(
                "SELECT user_id, password, username
                FROM users
                WHERE email = :email"
            );
            $userQuery->execute([':email' => $email]);
            
            $user = $userQuery->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['loggedUserId'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                unset($_SESSION['badAttempt']);
            } else {
                $_SESSION['badAttempt'] = "";
                header('Location: login.php');
                exit();
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>uniBudget - Your Personal Finance Manager</title>
    <meta name="description" content="Track your income and expenses - avoid overspending!">
    <meta name="keywords" content="expense manager, budget planner, expense tracker, budgeting app, money manager, money management, personal finance management software, finance manager, saving planner">
    <meta name="author" content="Magdalena Słomiany">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-Ua-Compatible" content="IE=edge">
    
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/fontello.css">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400;500;700&family=Fredoka+One&family=Roboto:wght@400;700;900&family=Varela+Round&display=swap" rel="stylesheet">
    
    <style>
        /* Custom Styling */
        body {
            background-color: #f4f4f4; /* Light Gray */
            color: #333;
        }

        header {
            background-color: #007bff; /* Blue */
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }

        #title a {
            color: #fff;
            text-decoration: none;
        }

        #subtitle {
            color: #e0e0e0;
        }

        .navbar {
            background-color: #007bff; /* Blue */
            width: 100%; /* Full width */
        }

        .navbar-nav .nav-link {
            color: #fff; /* White */
            font-weight: bold;
        }

        .navbar-nav .nav-link:hover {
            color: #d9e2ec; /* Light Blue */
        }

        .square {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }

        footer {
            background-color: #007bff; /* Blue */
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }

        footer a {
            color: #fff;
            text-decoration: none;
        }

        footer a:hover {
            color: #d9e2ec; /* Light Blue */
        }
    </style>
</head>

<body>
    <header>
        <h1 class="mt-3 mb-1" id="title">
            <a id="homeButton" href="index.php" role="button"><span id="logo">uniBudget</span>.com</a>
        </h1>
        <p id="subtitle">Your Personal Finance Manager</p>
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
                                <a class="dropdown-item" href="balance.php?userStartDate=2023-01-01&userEndDate=2023-12-31">Current Year</a>
                                <a class="dropdown-item" href="balance.php?userStartDate=2023-01-01&userEndDate=2023-01-31">Current Month</a>
                                <a class="dropdown-item" href="balance.php?userStartDate=2022-12-01&userEndDate=2022-12-31">Last Month</a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#dateModal">Custom</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="settings.php"><i class="icon-cog"></i> Settings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php"><i class="icon-logout"></i> Sign out</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </section>
        
        <section class="container-fluid square">
            <h2 class="pt-4 mx-2">Hello <?php echo $_SESSION['username']; ?>!</h2>
            <img class="img-fluid" src="css/img/menuBG.png" alt="Welcome Image" />
        </section>
    </main>
    
    <footer>
        <p>&copy; 2023 uniBudget.com | Designed for Limkokwing University Eswatini</p>
        <p>
            <a href="about.php">About</a> | 
            <a href="contact.php">Contact</a> | 
            <a href="privacy.php">Privacy Policy</a>
        </p>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>