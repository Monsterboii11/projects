<?php
session_start();
include 'connection.php'; // Include your database connection script
include 'dynamic_role_title.php'; // Include file for dynamic title based on user role
include 'dynamic_project.php'; // Include file for dynamic project details

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details from the database based on user_id
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);

// Check if user exists in the database
if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Extract user details
    $username = $user['name'];
    $email = $user['email'];
    $number = $user['number'];
} else {
    // Handle case where user is not found (though this scenario shouldn't occur with active sessions)
    $username = "User Not Found";
    $email = "N/A";
    // Add more fields as needed
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../CSS/user_dashboard.css">
    <style>
        .profile-info {
            text-align: center;
        }

        .profile-info img {
            width: 100px; /* Adjust the width as needed */
            height: 100px; /* Adjust the height as needed */
            border-radius: 50%; /* Makes the image round */
            margin-bottom: 20px;
        }

        .profile-info h1 {
            margin-bottom: 10px;
            color: #333;
        }

        .profile-info p {
            color: #666;
        }
    </style>
</head>

<body>
    <!-- sidebar -->
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
            <li>
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
            </li>
			<!-- Users link (only visible to admin) -->
			<?php if ($_SESSION['user_role'] == 1) : ?>
				<li>
					<a href="users.php">
						<i class='bx bxs-group'></i>
						<span class="text">Users</span>
					</a>
				</li>
			<?php endif; ?>
        </ul>
        <ul class="side-menu">
        <li class="active">
                <a href="#" id="settings-link">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Profile</span>
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
            <a href="#" class="notification">
                <i class='bx bxs-bell'></i>
                <span class="num">9</span>
            </a>
            <a href="#" class="profile">
                <img src="icons8-user-30.png">
            </a>
        </nav>
        <!-- MAIN -->
        <main>
        <div class="head-title">
                    <div class="left">
                        <h1>Users</h1>
                        <ul class="breadcrumb">
                            <li>
                                <a href="#">Dashboard</a>
                            </li>
                            <li><i class='bx bx-chevron-right'></i></li>
                            <li>
                                <a class="active" href="#">Users</a>
                            </li>
                        </ul>
                    </div>
        </div> 
        <div class="profile-info">
                    <img src="icons8-user-30.png" alt="">
                    <h1><?php echo $username; ?></h1><br>
                    <!-- Display additional user details as needed -->
                    <p><?php echo $email; ?></p>
                    <!-- Add more fields as needed -->
                    <p><?php echo $number; ?> </p>
                </div>
        </div>  
        </main>
        <!-- MAIN -->
    </section>
    <!-- Content -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../JS/script.js"></script>
</body>

</html>
