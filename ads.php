<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "my_budget");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Search & filter
$filter_title = isset($_GET['title']) ? $conn->real_escape_string($_GET['title']) : '';
$filter_discount = isset($_GET['discount']) ? $conn->real_escape_string($_GET['discount']) : '';
$filter_valid_until = isset($_GET['valid_until']) ? $conn->real_escape_string($_GET['valid_until']) : '';
$filter_status = isset($_GET['status']) ? $conn->real_escape_string($_GET['status']) : 'active';

// Build SQL
$where = [];
if ($filter_title) $where[] = "title LIKE '%$filter_title%'";
if ($filter_discount) $where[] = "discount LIKE '%$filter_discount%'";
if ($filter_valid_until) $where[] = "valid_until = '$filter_valid_until'";
if ($filter_status) $where[] = "status='$filter_status'";
$where_clause = $where ? "WHERE " . implode(' AND ', $where) : '';

$sql = "SELECT * FROM ads $where_clause ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <title>uniBudget - Deals & Budget Tips</title>
    <meta name="description" content="Latest deals to help you save more as a student!">
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
            /* background-color: #007bff; */
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
            background-color: lightblue;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 20px;
            margin-top: 20px;
        }
        .ads-table-row {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0,0,0,0.09);
            margin-bottom: 36px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 35px 30px;
        }
        .ads-table-left {
            flex: 1;
            text-align: left;
        }
        .ads-img {
            width: 260px;
            height: 260px;
            object-fit: cover;
            border-radius: 14px;
            border: 3px solid #007bff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.13);
        }
        .ads-table-right {
            flex: 1.5;
            padding-left: 24px;
        }
        .ads-title {
            font-size: 2.3em;
            font-weight: 700;
            color: black;
        }
        .ads-description {
            font-size: 1.35em;
            margin: 14px 0 20px 0;
        }
        .ads-discount {
            font-size: 2em;
            font-weight: 600;
            color: #28a745;
        }
        .ads-valid-until {
            font-size:1.15em;
            color:#234;
            margin-bottom: 12px;
        }
        .ads-status {
            font-size: 1.1em;
            font-weight: 600;
            color: #fff;
            background: #28a745;
            border-radius: 22px;
            padding: 7px 20px;
            display: inline-block;
        }
        @media (max-width: 992px) {
            .ads-table-row {
                flex-direction: column;
                text-align: center;
                padding: 25px 8px;
            }
            .ads-table-left, .ads-table-right {
                padding-left: 0;
                margin: 0;
                text-align: center;
            }
            .ads-table-right {
                margin-top: 18px;
            }
        }
        @media (max-width: 600px) {
            .ads-title {
                font-size: 1.25em;
            }
            .ads-img {
                width: 120px;
                height: 120px;
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

    <!-- Navigation copied from expense.php -->
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
        <section class="container-fluid square my-4 py-2">
            <h2 class="text-center mb-4" style="font-size:2.1em;">Budget-Smart Student Deals</h2>

            <!-- Filter/Search Form -->
            <form class="mb-4" method="get">
                <div class="row">
                    <div class="col-sm-2 mb-1">
                        <input type="text" class="form-control form-control-lg" name="title" value="<?php echo htmlspecialchars($filter_title); ?>" placeholder="Search Title">
                    </div>
                    <div class="col-sm-2 mb-1">
                        <input type="text" class="form-control form-control-lg" name="discount" value="<?php echo htmlspecialchars($filter_discount); ?>" placeholder="Search Discount">
                    </div>
                    <div class="col-sm-2 mb-1">
                        <input type="date" class="form-control form-control-lg" name="valid_until" value="<?php echo htmlspecialchars($filter_valid_until); ?>" placeholder="Valid Until">
                    </div>
                    <div class="col-sm-2 mb-1">
                        <select class="form-control form-control-lg" name="status">
                            <option value="">Any Status</option>
                            <option value="active" <?php echo ($filter_status === "active") ? "selected" : ""; ?>>Active</option>
                            <option value="inactive" <?php echo ($filter_status === "inactive") ? "selected" : ""; ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="col-sm-2 mb-1">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">Search</button>
                    </div>
                    <div class="col-sm-2 mb-1">
                        <a href="ads.php" class="btn btn-secondary btn-lg btn-block">Reset</a>
                    </div>
                </div>
            </form>

            <?php
            if ($result && $result->num_rows > 0) {
                echo '<div class="ads-table-list">';
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="ads-table-row">';
                    // Image LEFT
                    echo '<div class="ads-table-left">';
                    if (!empty($row['image_url']) && file_exists($row['image_url'])) {
                        echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="Deal Image" class="ads-img"/>';
                    } else {
                        echo '<div style="color:#999;font-size:1.08em;margin-top:80px;">No Image</div>';
                    }
                    echo '</div>';
                    // Deal info RIGHT
                    echo '<div class="ads-table-right">';
                    echo '<div class="ads-title">' . htmlspecialchars($row['title']) . '</div>';
                    echo '<div class="ads-description">' . htmlspecialchars($row['description']) . '</div>';
                    echo '<div class="ads-discount"><i class="icon-tag"></i> ' . htmlspecialchars($row['discount']) . '</div>';
                    echo '<div class="ads-valid-until"><strong>Valid Until:</strong> ' . htmlspecialchars($row['valid_until']) . '</div>';
                    echo '<div class="ads-status">' . ucfirst(htmlspecialchars($row['status'])) . '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                echo '</div>';
            } else {
                echo '<div class="alert alert-info text-center" style="font-size:1.25em;">No deals found matching your search.</div>';
            }
            $conn->close();
            ?>
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