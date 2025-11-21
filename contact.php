<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "my_budget");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create messages table if it doesn't exist
$conn->query("CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Handle message submission
$message_sent = false;
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_email']) && isset($_POST['user_message'])) {
    $user_email = trim($_POST['user_email']);
    $user_message = trim($_POST['user_message']);

    if (filter_var($user_email, FILTER_VALIDATE_EMAIL) && $user_message != "") {
        $email_safe = $conn->real_escape_string($user_email);
        $msg_safe = $conn->real_escape_string($user_message);
        $sql = "INSERT INTO contact_messages (email, message) VALUES ('$email_safe', '$msg_safe')";
        if ($conn->query($sql)) {
            $message_sent = true;
        } else {
            $error_message = "Sorry, we couldn't save your message. Try again!";
        }
    } else {
        $error_message = "Please use a valid email and fill out the message.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <title>Contact Us - uniBudget</title>
    <meta name="description" content="Contact uniBudget team for support, queries, or feedback">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/fontello.css">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400;500;700&family=Fredoka+One&family=Roboto:wght@400;700;900&family=Varela+Round&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: lightblue;
            color: #222;
        }
        header {
            background-color: #28a745;
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
            /* background-color: #007bff; */
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
            background-color: lightblue;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 20px;
            margin-top: 20px;
        }
        .contact-block {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 18px;
        }
        .contact-info h5 {
            font-size: 1.2em;
            font-weight: bold;
            color: black;
        }
        .contact-info {
            font-size: 1.1em;
        }
        .form-label {
            font-size: 1.1em;
            font-weight: 500;
        }
        .form-control {
            font-size: 1.1em;
            border-radius: 10px;
        }
        .btn-primary {
            font-size: 1.1em;
            padding: 10px 30px;
            border-radius: 10px;
        }
        .social-icon {
            font-size: 1.5em;
            vertical-align: middle;
            margin-right: 6px;
        }
        .facebook-link {
            color:black;
            font-weight: 700;
            font-size: 1.09em;
            text-decoration: none;
        }
        @media (max-width: 600px) {
            .contact-block {
                padding: 15px;
            }
        }
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
        footer a:hover {
            color: #d9e2ec; /* Light Blue */
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
    <main>
        <section class="container contact-block">
            <div class="row">
                <div class="col-lg-5 contact-info mb-4 mb-lg-0">
                    <h2 class="mb-3" style="color:black;">Contact Information</h2>
                    <div>
                        <h5><i class="icon-phone"></i> Phone</h5>
                        <span>+268 7250 8293 / +268 7972 7921</span>
                    </div>
                    <div class="mt-3">
                        <h5><i class="icon-mail"></i> Email</h5>
                        <span>unibudgetesw@gmail.com</span>
                    </div>
                    <div class="mt-3">
                        <h5><i class="icon-whatsapp"></i> WhatsApp</h5>
                        <span>+268 7972 7921</span>
                    </div>
                    <div class="mt-3">
                        <h5><span class="social-icon"><i class="icon-facebook"></i></span>Facebook</h5>
                        <a class="facebook-link" href="https://www.facebook.com/profile.php?id=61583802283173
" target="_blank">uniBudgetEsw</a>
                    </div>
                </div>
                <div class="col-lg-7">
                    <h2 class="mb-3" style="color: black;">Send Us a Message</h2>
                    <?php
                    if ($message_sent) {
                        echo '<div class="alert alert-success mt-2 mb-2">Thank you! Your message has been sent.</div>';
                    }
                    if ($error_message) {
                        echo '<div class="alert alert-danger mt-2 mb-2">'.$error_message.'</div>';
                    }
                    ?>
                    <form method="post" autocomplete="off">
                        <div class="mb-3">
                            <label for="user_email" class="form-label">Your Email</label>
                            <input type="email" class="form-control" id="user_email" name="user_email" required placeholder="Your email address">
                        </div>
                        <div class="mb-3">
                            <label for="user_message" class="form-label">Your Message</label>
                            <textarea class="form-control" id="user_message" name="user_message" rows="6" required placeholder="Type your message or inquiry here..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="background-color: green;"><i class="icon-paper-plane"></i> Send Message</button>
                    </form>
                </div>
            </div>
        </section>
        <!-- Modal for custom balance date selection -->
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
        <p style="font-size:1.1em;">&copy; 2023 uniBudget.com | Designed for Limkokwing University Eswatini</p>
        <p>
            <a href="about.php" style="font-size:1.05em;">About</a> | 
            <a href="contact.php" style="font-size:1.05em;">Contact</a> | 
            <a href="privacy.php" style="font-size:1.05em;">Privacy Policy</a>
        </p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>