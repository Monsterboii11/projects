<?php
session_start();
include "dynamic_role_title.php"; // Include your dynamic role title script
include "connection.php"; // Include your database connection file

// Check if user ID is provided in the URL
if (!isset($_GET['edit_id'])) {
    // Redirect or handle error if no user ID is provided
    header("Location: users.php");
    exit;
}

// Fetch user details from the database
$edit_id = $_GET['edit_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
    } else {
        // Redirect or handle error if user ID is invalid or not found
        header("Location: users.php");
        exit;
    }

    $stmt->close();
} else {
    // Handle database error if prepare fails
    die('Error preparing SQL: ' . $conn->error);
}

// Handle form submission if POST method is used
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form data here to update user details in the database
    $updateSql = "UPDATE users SET name = ?, email = ?, number = ?, user_role = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);

    if ($updateStmt) {
        $updateStmt->bind_param("ssssi", $_POST['name'], $_POST['email'], $_POST['number'], $_POST['user_role'], $edit_id);

        // Execute the update statement
        if ($updateStmt->execute()) {
            // Redirect to users.php or show success message
            echo '<script>alert("Changes saved"); window.location.href="users.php";</script>';
            exit;
        } else {
            // Handle update error
            die('Error updating user: ' . $conn->error);
        }

        $updateStmt->close();
    } else {
        // Handle database error if prepare fails
        die('Error preparing update SQL: ' . $conn->error);
    }
}

// Close database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../CSS/user_dashboard.css">
    <style>
        .form {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .form-label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            font-family: 'poppins';
            width: calc(100% - 16px);
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .button-outline {
            background-color: transparent;
            border: 1px solid #007bff;
            color: #007bff;
        }

        .button-outline:hover {
            background-color: black;
            color: white;
        }
    </style>
</head>

<body>
<section id="sidebar">
        <a class="brand" href="#">
            <i class="bx bxs-smile"></i><span class="text"><?php echo $greeting; ?></span>
        </a>
        <ul class="side-menu top">
            <li>
                <a href="admin_dashboard.php" class="sidebar-link" data-section="dashboard">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li class="active">
                <a href="projects.php" class="sidebar-link" data-section="projects">
                    <i class='bx bxs-objects-horizontal-right'></i>
                    <span class="text">Projects</span>
                </a>
            </li>
            <li>
                <a href="task.php" id="tasks-link">
                    <i class='bx bx-task'></i>
                    <span class="text">Tasks</span>
                </a>
            </li>
            <li>
                <a href="#" id="reports-link">
                    <i class='bx bxs-report'></i>
                    <span class="text">Reports</span>
                </a>
            </li>
            <li>
                <a href="users.php" id="team-link">
                    <i class='bx bxs-group'></i>
                    <span class="text">Users</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="#" id="settings-link">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
                <a href="user_login.php" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- sidebar -->
    <!-- Content -->
    <section id="content">
        <nav>
            <i class='bx bx-menu'></i>
            <a href="#" class="nav-link">Categories</a>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Search...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <!-- <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label> -->
            <!-- <a href="#" class="notification">
                <i class='bx bxs-bell'></i>
                <span class="num">9</span>
            </a> -->
            <a href="profile.php" class="profile">
                <i class='bx bxs-user' style='font-size: 1.5em;'></i>
            </a>
        </nav>
        <main class="container">
            <div class="head-title">
                <div class="left">
                    <h1>Edit Users</h1>
                    <ul class="breadcrumb">
                        <li><a href="#">Dashboard</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="#">Edit Users</a></li>
                    </ul>
                </div>
            </div><br><br>
        <div class="row">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?edit_id=' . $edit_id; ?>" method="post" class="form">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your Name" required class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>">

                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>">

                <label for="number" class="form-label">Number</label>
                <input type="number" id="number" name="number" placeholder="Enter your Number" required class="form-control" value="<?php echo htmlspecialchars($user['number']); ?>">

                <label for="user_role" class="form-label">User Role</label>
                <select id="user_role" name="user_role" class="form-select">
                    <option value="1" <?php if ($user['user_role'] == 1) echo 'selected'; ?>>Administrator</option>
                    <option value="2" <?php if ($user['user_role'] == 2) echo 'selected'; ?> selected>Employee</option>
                </select>

                <input type="submit" name="save_user" value="Save" onclick="alert('Changes saved');" class="button">
                <input type="button" value="Cancel" onclick="window.location.href='users.php';" class="button button-outline">
            </form>
        </div>
    </main>

    <!-- Your custom scripts -->
    <script src="../JS/script.js"></script>
</body>

</html>
