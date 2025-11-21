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
            background-color: lightblue; /* Light Gray */
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
            background-color: lightblue;
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
            <a id="homeButton" href="index.php" role="button"><span id="logo">myBudget</span>.com</a>
        </h1>
        <p id="subtitle">Admin - Manage Local Deals</p>
    </header>

    <main>
        <section class="container-fluid square my-4 py-2">
            <h2 class="text-center">Manage Deals</h2>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <?php
                    // Database connection
                    $conn = new mysqli("localhost", "root", "", "my_budget");

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Initialize variables for editing
                    $editMode = false;
                    $editId = '';
                    $editTitle = '';
                    $editDescription = '';
                    $editDiscount = '';
                    $editValidUntil = '';
                    $editStatus = '';

                    // Handle form submissions
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (isset($_POST['add'])) {
                            // Add a new deal
                            $title = $conn->real_escape_string($_POST['title']);
                            $description = $conn->real_escape_string($_POST['description']);
                            $discount = $conn->real_escape_string($_POST['discount']);
                            $valid_until = $conn->real_escape_string($_POST['valid_until']);
                            $status = $conn->real_escape_string($_POST['status']);

                            // Handle file upload
                            $uploadDir = 'uploads/';
                            $uploadFile = $uploadDir . basename($_FILES['image']['name']);
                            $imagePath = '';

                            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                                $imagePath = $uploadFile;
                            }

                            $sql = "INSERT INTO ads (title, description, image_url, discount, valid_until, status) 
                                    VALUES ('$title', '$description', '$imagePath', '$discount', '$valid_until', '$status')";
                            if ($conn->query($sql) === TRUE) {
                                echo '<div class="alert alert-success">Deal added successfully!</div>';
                            } else {
                                echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
                            }
                        } elseif (isset($_POST['edit'])) {
                            // Edit an existing deal
                            $id = $conn->real_escape_string($_POST['id']);
                            $title = $conn->real_escape_string($_POST['title']);
                            $description = $conn->real_escape_string($_POST['description']);
                            $discount = $conn->real_escape_string($_POST['discount']);
                            $valid_until = $conn->real_escape_string($_POST['valid_until']);
                            $status = $conn->real_escape_string($_POST['status']);

                            $sql = "UPDATE ads SET 
                                    title='$title', description='$description', discount='$discount', 
                                    valid_until='$valid_until', status='$status' 
                                    WHERE id='$id'";
                            if ($conn->query($sql) === TRUE) {
                                echo '<div class="alert alert-success">Deal updated successfully!</div>';
                            } else {
                                echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
                            }
                        } elseif (isset($_POST['delete'])) {
                            // Delete a deal
                            $id = $conn->real_escape_string($_POST['id']);
                            $sql = "DELETE FROM ads WHERE id='$id'";
                            if ($conn->query($sql) === TRUE) {
                                echo '<div class="alert alert-success">Deal deleted successfully!</div>';
                            } else {
                                echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
                            }
                        } elseif (isset($_POST['load_edit'])) {
                            // Load deal data for editing
                            $editMode = true;
                            $editId = $conn->real_escape_string($_POST['id']);
                            $sql = "SELECT * FROM ads WHERE id='$editId'";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $editTitle = $row['title'];
                                $editDescription = $row['description'];
                                $editDiscount = $row['discount'];
                                $editValidUntil = $row['valid_until'];
                                $editStatus = $row['status'];
                            }
                        }
                    }
                    ?>

                    <form method="POST" action="admin_add_deal.php" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $editId; ?>">
                        <div class="form-group">
                            <label for="title">Deal Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($editTitle); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Deal Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($editDescription); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="discount">Discount</label>
                            <input type="text" class="form-control" id="discount" name="discount" value="<?php echo htmlspecialchars($editDiscount); ?>" placeholder="e.g., 20% off" required>
                        </div>
                        <div class="form-group">
                            <label for="valid_until">Valid Until</label>
                            <input type="text" class="form-control" id="valid_until" name="valid_until" value="<?php echo htmlspecialchars($editValidUntil); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="active" <?php echo ($editStatus === 'active') ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo ($editStatus === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="image">Upload Image</label>
                            <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
                        </div>
                        <?php if ($editMode): ?>
                            <button type="submit" name="edit" class="btn btn-warning btn-block">Update Deal</button>
                        <?php else: ?>
                            <button type="submit" name="add" class="btn btn-primary btn-block">Add Deal</button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <h2 class="text-center mt-5">Existing Deals</h2>
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <?php
                    // Fetch all deals
                    $sql = "SELECT * FROM ads ORDER BY created_at DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        echo '<table class="table table-bordered">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>Title</th>';
                        echo '<th>Description</th>';
                        echo '<th>Discount</th>';
                        echo '<th>Valid Until</th>';
                        echo '<th>Status</th>';
                        echo '<th>Image</th>';
                        echo '<th>Actions</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['title']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['discount']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['valid_until']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['status']) . '</td>';
                            echo '<td><img src="' . htmlspecialchars($row['image_url']) . '" alt="Ad Image" style="width: 100px;"></td>';
                            echo '<td>';
                            echo '<form method="POST" action="admin_add_deal.php" style="display:inline;">';
                            echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
                            echo '<button type="submit" name="load_edit" class="btn btn-warning btn-sm">Edit</button>';
                            echo '<button type="submit" name="delete" class="btn btn-danger btn-sm">Delete</button>';
                            echo '</form>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        echo '</tbody>';
                        echo '</table>';
                    } else {
                        echo '<p class="text-center">No deals available.</p>';
                    }

                    $conn->close();
                    ?>
                </div>
            </div>
        </section>
    </main>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(function() {
            $("#valid_until").datepicker({ dateFormat: "yy-mm-dd" });
        });
    </script>
</body>
<footer>
        <p>&copy; 2023 uniBudget.com | Designed for Limkokwing University Eswatini</p>
        <p>
            <a href="about.php">About</a> | 
            <a href="contact.php">Contact</a> | 
            <a href="privacy.php">Privacy Policy</a>
        </p>
    </footer>
</html>