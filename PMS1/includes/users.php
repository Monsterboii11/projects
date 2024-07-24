<?php
// Check if a session is not active
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start or resume session
}

include 'connection.php'; // Include your database connection
include "dynamic_role_title.php";


// Check if user is logged in and has a valid user role
if (isset($_SESSION['user_role'])) {
    $user_role = $_SESSION['user_role'];

    // Only continue if the user is logged in and is either an administrator or has another valid role
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Initialize variables
    $search_query = "";
    $where_clause = "";

    // Check if search query is provided
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search_query = $_GET['search'];
        // Prepare WHERE clause for SQL query
        $where_clause = " WHERE name LIKE '%$search_query%'";
    }

    // SQL query to fetch users with optional WHERE clause for search
    $sql = "SELECT * FROM users" . $where_clause;
    $result = $conn->query($sql);

    // Map status values to corresponding labels
    $user_roles = [
        1 => "Administrator",
        2 => "Employee",
    ];

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Users</title>
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
                <li class="active">
                    <a href="#" id="team-link">
                        <i class='bx bxs-group'></i>
                        <span class="text">Users</span>
                    </a>
                </li>
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
                <form action="#" method="get">
                    <div class="form-input">
                        <input type="search" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search_query); ?>">
                        <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                    </div>
                </form>
                <a href="profile.php" class="profile">
                    <i class='bx bxs-user' style='font-size: 1.5em; margin-left:auto'></i>
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
                    <?php
                    // Show "Add user" button only if user is an administrator
                    if ($user_role == 1) {
                        echo '<a href="add-users.php" class="btn-download">
                        <i class="bx bxs-add-to-queue"></i>
                        <span class="text">Add user</span>
                      </a>';
                    }
                    ?>
                </div>
                <div class="table-data">
                    <div class="order">
                        <div class="head">
                            <h3>Users List</h3>
                            <i class='bx bx-search'></i>
                            <i class='bx bx-filter'></i>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Users Name</th>
                                    <th>Email</th>
                                    <th>Number</th>
                                    <th>Role</th>
                                    <?php
                                    // Check if user is an admin or project manager to show Action column
                                    if ($user_role == 1 || $user_role == 2) {
                                        echo "<th>Action</th>";
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = $result->fetch_assoc()) {
                                    // Display row if user is an administrator or if the user role is not Administrator
                                    if ($user_role == 1 || $row['user_role'] != 1) {
                                        $user_role_label = isset($user_roles[$row['user_role']]) ? $user_roles[$row['user_role']] : '';
                                        echo "
                                               <tr>
                                               <td>" . $row['name'] . "</td>
                                               <td>" . $row['email'] . "</td>
                                               <td>" . $row['number'] . "</td>
                                               <td>" . $user_role_label . "</td>
                                               <td>
                                               <div class='dropdown'>
                                               <button class='action-button' type='button' id='dropdownMenuButton" . $row['id'] . "' aria-haspopup='true' aria-expanded='false'>
                                                  Action
                                               </button>
                                               <div class='dropdown-menu' id='dropdownMenu" . $row['id'] . "'>
                                               <a class='dropdown-item' href='edit_users.php?edit_id=" . $row['id'] . "'>Edit</a>
                                               <a class='dropdown-item' href='delete_users.php?delete_id=" . $row['id'] . "'>Delete</a>
                                         </div>
                                    </div>
                                </td>
                            </tr>
                        ";
                                    }
                                }
                                ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </main>
            <!-- MAIN -->
        </section>
        <script src="../JS/script.js"></script>
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
} else {
    // Redirect to login page if user is not logged in
    header("Location: user_login.php");
    exit;
}
?>