<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <title>About uniBudget</title>
    <meta name="description" content="Learn more about uniBudget.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/fontello.css">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Paaji+2:wght@400;500;700&family=Fredoka+One&family=Roboto:wght@400;700;900&family=Varela+Round&display=swap" rel="stylesheet">
    <style>
        
        .about-container {
            background: #eaf5ea;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            padding: 44px 22px;
            margin-top: 38px;
        }
        .about-section {
            margin-bottom: 50px;
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 1s, transform 1s;
        }
        .about-section.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .about-title {
            font-size: 2.4em;
            font-weight: 700;
            color: black;
            margin-bottom: 24px;
        }
        .mission-title {
            font-size: 1.4em;
            color: black;
            font-weight: 600;
            margin-bottom: 12px;
        }
        .mission-text {
            font-size: 1.18em;
            color: #222;
            line-height: 1.7;
            margin-bottom: 34px;
            font-style: italic;
        }
        .credit-text {
            font-size: 1.15em;
            margin-top: 24px;
            color: #444;
        }
        .logo-img {
            display: block;
            margin: 0 auto 40px auto;
            width: 170px;
            height: auto;
            border-radius: 20px;
            box-shadow: 0 2px 12px rgba(0,123,255,0.13);
        }
        @media (max-width: 600px) {
            .about-container { padding: 15px 2px; }
            .about-title { font-size: 1.5em; }
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
        <section class="container about-container">
            <!-- Logo SVG -->
            
            <div class="about-section" id="about1">
                <div class="about-title">Welcome to uniBudget</div>
                <div style="font-size:1.15em;line-height:1.7;">
                    uniBudget is an innovative personal finance management platform designed for students and young professionals. With uniBudget, you can effortlessly track your income, record and categorize your expenses, set financial goals, and discover tailored deals to help you save more.
                </div>
            </div>
            <div class="about-section" id="about2">
                <div class="mission-title">Our Mission</div>
                <div class="mission-text">
                    To empower students and young adults to take control of their finances, build smart saving habits, and plan for a secure future. We believe that effective money management is the cornerstone of academic and personal success. Our mission is to make budgeting simple, enjoyable, and accessible to all.
                </div>
            </div>
            <div class="about-section" id="about3">
                <div class="mission-title">Who We Are</div>
                <div style="font-size:1.13em;margin-bottom:24px;">
                    uniBudget was proudly developed by students at Limkokwing University of Creative Technology, Eswatini Campus. Our diverse team understands the unique financial challenges faced by African youth, and we are dedicated to delivering real solutions for real people.
                </div>
                <div class="credit-text">
                    <b>uniBudget, built for students by students. Join us and start your journey to smarter money management today!</b>
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
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        // Animate sections as you scroll
        function showOnScroll() {
            var sections = document.querySelectorAll('.about-section');
            var winBottom = window.scrollY + window.innerHeight;
            sections.forEach(function(sec){
                var secTop = sec.offsetTop + 80;
                if(winBottom > secTop){
                    sec.classList.add('visible');
                }
            });
        }
        document.addEventListener('scroll', showOnScroll);
        document.addEventListener('DOMContentLoaded', showOnScroll);
    </script>
    
</body>
</html>