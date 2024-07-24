<?php
session_start();
include 'connection.php'; // Ensure this file contains your database connection settings
include 'dynamic_role_title.php'; // Include file for dynamic role titles
include 'dynamic_project.php'; // Include file for dynamic project details

// Check if user is logged in and has a valid user role
if (isset($_SESSION['user_role'])) {
    $user_role = $_SESSION['user_role'];

    // Handle search input safely
    $projectSearchQuery = '';
    if (isset($_GET['search'])) {
        $projectSearchQuery = mysqli_real_escape_string($conn, $_GET['search']);
    }

    // SQL query to fetch projects based on user role
    if ($user_role == 1) {
        // Admin can see all projects
        $sql = "SELECT * FROM project_list";
    } elseif ($user_role == 2) {
        // Regular user can only see projects they are associated with
        $sql = "SELECT pl.* FROM project_list pl
                JOIN project_team_members pua ON pl.id = pua.project_id
                WHERE pua.user_id = ?";
    } else {
        // Redirect or handle unauthorized access
        header("Location: user_login.php");
        exit();
    }

    // Append WHERE clause if search query is provided
    if (!empty($projectSearchQuery)) {
        if ($user_role == 1) {
            $sql .= " WHERE project_name LIKE '%$projectSearchQuery%'";
        } elseif ($user_role == 2) {
            $sql .= " AND pl.project_name LIKE '%$projectSearchQuery%'";
        }
    }
    // Prepare SQL statement
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters if applicable
        if ($user_role == 2) {
            $stmt->bind_param("i", $_SESSION['user_id']);
        }

        // Execute SQL query
        $stmt->execute();
        $projectResult = $stmt->get_result();

        // Check if query execution was successful
        if (!$projectResult) {
            die('Error executing query: ' . $conn->error);
        }
    } else {
        die('Error preparing SQL: ' . $conn->error);
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
        <style>
            .dropdown-menu {
                display: none;
                position: absolute;
                background-color: #f9f9f9;
                min-width: 160px;
                box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
                z-index: 1;
            }

            .dropdown-menu.show {
                display: block;
            }

            .dropdown-menu a {
                color: black;
                padding: 5px 8px;
                text-decoration: none;
                display: block;
            }

            .dropdown-menu a:hover {
                background-color: #f1f1f1;
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
                <a href="#" class="nav-link">Dashboard</a>
                <form action="projects.php" method="GET">
                    <div class="form-input">
                        <input type="search" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($projectSearchQuery); ?>">
                        <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                    </div>
                </form>
                <a href="profile.php" class="profile">
                    <i class='bx bxs-user' style='font-size: 1.5em;'></i>
                </a>
            </nav>
            <!-- MAIN -->
            <main>
                <div class="head-title">
                    <div class="left">
                        <h1>Projects</h1>
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
                    <?php
                    // Show "Add project" button only if user is an administrator
                    if ($user_role == 1) {
                        echo '<a href="add_project.php" class="btn-download">
                          <i class="bx bxs-add-to-queue"></i>
                          <span class="text">Add Projects</span>
                          </a>';
                    }
                    ?>

                </div>
                <div class="table-data">
                    <div class="order">
                        <table>
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Project Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count = 1;
                                // Check if there are any projects found
                                if ($projectResult->num_rows > 0) {
                                    while ($row = $projectResult->fetch_assoc()) {
                                ?>
                                        <tr id="projectRow<?php echo $row['id']; ?>" class="project-row">
                                            <td><?php echo $count; ?></td>
                                            <td><?php echo $row['project_name']; ?></td>
                                            <td><?php echo $row['start_date']; ?></td>
                                            <td><?php echo $row['end_date']; ?></td>
                                            <td>
                                                <span class="status <?php echo strtolower($row['status']); ?>">
                                                    <?php echo $row['status']; ?>
                                                </span>
                                            </td>

                                            <td>
                                                <div class="dropdown">
                                                    <button class="action-button" type="button" id="dropdownMenuButton<?php echo $row['id']; ?>" aria-haspopup="true" aria-expanded="false">
                                                        Action
                                                    </button>
                                                    <div class="dropdown-menu" id="dropdownMenu<?php echo $row['id']; ?>">
                                                        <a class="dropdown-item" href="project_description.php?id=<?php echo $row['id']; ?>">View</a>
                                                        <?php if ($_SESSION['user_role'] == 1) : ?>
                                                            <a class="dropdown-item" href="edit_project.php?id=<?php echo $row['id']; ?>">Edit</a>
                                                            <a class="dropdown-item" href="#" onclick="confirmDelete(<?php echo $row['id']; ?>); return false;">Delete</a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                <?php
                                        $count++;
                                    }
                                } else {
                                    // Output if no projects are found
                                    echo '<tr><td colspan="6">Project not available</td></tr>';
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>

                </div>
            </main>
            <!-- MAIN -->
        </section>
        <!-- Content -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="../JS/script.js"></script>
        <!-- JavaScript code to handle row click and display project details -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <!-- Your custom script to handle row click and redirect to project_description.php -->

        <script>
            // JavaScript function to confirm deletion
            function confirmDelete(projectId) {
                if (confirm("Are you sure you want to delete this project?")) {
                    window.location.href = 'delete_projects.php?delete_id=' + projectId;
                }
            }
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var actionButtons = document.querySelectorAll(".action-button");

                // Function to close all dropdown menus except the specified one
                function closeOtherDropdowns(exceptDropdown) {
                    var dropdownMenus = document.querySelectorAll(".dropdown-menu");
                    dropdownMenus.forEach(function(menu) {
                        if (menu !== exceptDropdown) {
                            menu.classList.remove("show");
                        }
                    });
                }

                // Add click event listener to all .action-button elements
                actionButtons.forEach(function(button) {
                    button.addEventListener("click", function(event) {
                        event.stopPropagation(); // Prevents event from bubbling up to document
                        var dropdownMenu = button.nextElementSibling; // Get the next sibling which is .dropdown-menu
                        dropdownMenu.classList.toggle("show"); // Toggle the class to show/hide dropdown
                        closeOtherDropdowns(dropdownMenu); // Close other dropdowns
                    });
                });

                // Close dropdown menu when clicking outside
                document.addEventListener("click", function(event) {
                    var dropdownMenus = document.querySelectorAll(".dropdown-menu");
                    dropdownMenus.forEach(function(menu) {
                        if (!menu.contains(event.target)) { // Close if the click is outside the dropdown menu
                            menu.classList.remove("show");
                        }
                    });
                });
            });
        </script>


    </body>

    </html>

<?php
    // Close the result set and database connection
    $stmt->close();
    $conn->close();
} else {
    // Redirect to login page if user is not logged in
    header("Location: user_login.php");
    exit;
}
?>