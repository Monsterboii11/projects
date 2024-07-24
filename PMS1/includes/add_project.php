<?php
session_start();
include "dynamic_role_title.php";
// Handle form submission if POST method is used
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if project_team_members[] is set and not empty
    if (isset($_POST['project_team_members']) && !empty($_POST['project_team_members'])) {
        // Loop through selected members
        foreach ($_POST['project_team_members'] as $member_id) {
            // Process each selected member ID
            echo "Selected member ID: " . $member_id . "<br>";
            // You can perform database operations or other tasks here
        }
    } else {
        echo "No team members selected.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../CSS/user_dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.0.1/dist/css/multi-select-tag.css">
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
        .form-select,
        .form-control-textarea {
            font-family: 'poppins';
            width: calc(100% - 16px);
            /* Adjust for padding */
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .form-control-textarea {
            width: 100%;
            /* Adjust width as needed */
            height: 250px;
            /* Set a fixed height */
            min-height: 150px;
            /* Ensure a minimum height */
            max-height: 300px;
            /* Optionally set a maximum height */
            resize: none;
        }


        .button {
            background-color: #007bff;
            /* Blue color for Save button */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .button:hover {
            background-color: #0056b3;
            /* Darker shade on hover */
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
            <li>
                <a href="profile.php" class="settings-link">
                    <i class='bx bxs-user'></i>
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
        <!-- MAIN -->
        <main class="container">
            <div class="head-title">
                <div class="left">
                    <h1>New project</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Projects</a>
                        </li>
                    </ul>
                </div>
            </div><br><br>
            <div class="row">
                <form action="process-form.php" method="post" class="form">
                    <label for="project_name" class="form-label">Project Name</label>
                    <input type="text" id="project_name" name="project_name" placeholder="Enter your Project Name" required class="form-control">

                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="1">Pending</option>
                        <option value="2" selected>Process</option>
                        <option value="3">Completed</option>
                    </select>

                    <label for="start_date" class="form-label">Start date</label>
                    <input type="date" id="start_date" name="start_date" class="form-control">

                    <label for="end_date" class="form-label">End date</label>
                    <input type="date" id="end_date" name="end_date" class="form-control">


                    </select>
                    <label for="project_team_members" class="form-label">Project Team members</label>
                    <select id="project_team_members" name="project_team_members[]" class="form-select" multiple>
                        <?php
                        // Include database connection
                        include 'connection.php';

                        // Query to fetch team members
                        $sql = "SELECT id, name FROM users WHERE user_role = 2"; // Adjust user_role as per your database schema
                        $result = $conn->query($sql);

                        // Check if query was successful
                        if ($result && $result->num_rows > 0) {
                            // Loop through results and generate options
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                            }
                        } else {
                            echo '<option value="">No team members found</option>';
                            if (!$result) {
                                die("Error: " . $conn->error); // Display error message if query fails
                            }
                        }

                        // Close database connection
                        $conn->close();
                        ?>
                    </select>

                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" rows="4" cols="50" class="form-control-textarea" style="resize: none;"></textarea>

                    <input type="submit" name="save_project" value="Save" class="button">
                    <input type="button" value="Cancel" onclick="window.location.href='user_dashboard.php';" class="button button-outline">
                </form>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <script src="../JS/script.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.0.1/dist/js/multi-select-tag.js"></script>
    <script>
        new MultiSelectTag('project_team_members');
    </script>
</body>

</html>