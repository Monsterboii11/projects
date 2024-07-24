<?php
session_start();
include "dynamic_role_title.php"; // Include the file with dynamic role title

// Redirect to login page if user is not authenticated
if (!isset($_SESSION['user_role'])) {
    header("Location: user_login.php");
    exit;
}

// Restrict access based on user role if necessary
$user_role = $_SESSION['user_role'];
if (!in_array($user_role, [1, 2])) {
    header("Location: user_login.php");
    exit;
}

// Include database connection or connection file
include 'connection.php';

// Fetch task count based on user role
if ($_SESSION['user_role'] == 1) {
    // For admin, count all tasks
    $sql = "SELECT COUNT(*) AS task_count FROM task_list";
} else {
    // For other roles (e.g., employee), count only their assigned tasks
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT COUNT(*) AS task_count FROM task_list WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $task_count = $row['task_count'];
    $stmt->close();
}

// Fetch tasks based on user role
if ($_SESSION['user_role'] == 1) {
    // For admin, fetch all tasks
    $sql_tasks = "SELECT * FROM task_list";
    $result_tasks = $conn->query($sql_tasks); // Execute query and store result
} else {
    // For other roles (e.g., employee), fetch assigned tasks
    $user_id = $_SESSION['user_id'];
    $sql_tasks = "SELECT * FROM task_list WHERE user_id = ?";
    $stmt_tasks = $conn->prepare($sql_tasks);
    $stmt_tasks->bind_param("i", $user_id);
    $stmt_tasks->execute();
    $result_tasks = $stmt_tasks->get_result();
    $stmt_tasks->close();
}

// Close database connection (moved to end)
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../CSS/user_dashboard.css">
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
            <li class="active">
                <a href="#" id="tasks-link">
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
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Search...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <!-- <a href="#" class="notification">
                <i class='bx bxs-bell'></i>
                <span class="num">9</span>
            </a> -->
            <a href="profile.php" class="profile">
                <i class='bx bxs-user' style='font-size: 1.5em;'></i>
            </a>
        </nav>
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Tasks</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Tasks</a>
                        </li>
                    </ul>
                </div>
                <?php
                // Show "Add task" button only if user is an administrator or Project Manager
                if ($user_role == 1) {
                    echo '<a href="add_tasks.php" class="btn-download">
                          <i class="bx bxs-add-to-queue"></i>
                          <span class="text">Add Task</span>
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
                                <th>Task Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 1;
                            // Fetch and display tasks
                            while ($row = $result_tasks->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo $row['task_name']; ?></td>
                                    <td><?php echo $row['start_date']; ?></td>
                                    <td><?php echo $row['end_date']; ?></td>
                                    <td><?php echo $row['description']; ?></td>
                                    <td>
                                        <?php
                                        // Show delete option only if user is an administrator or Project Manager
                                        if ($user_role == 1) {
                                            echo '<a href="delete_task.php?delete_id=' . $row['id'] . '" class="delete">
                                                  <i class="bx bx-trash-alt"></i>
                                                  </a>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                                $count++;
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                    if ($result_tasks->num_rows == 0) {
                        echo "<br><p>No tasks found.</p>";
                    }
                    ?>
                </div>
            </div>
        </main>
    </section>
    <!-- Content -->
    <script src="../JS/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.action-icon').click(function() {
                var taskId = $(this).data('task-id');
                $('#dropdownMenu' + taskId).toggle();
            });

            $(document).click(function(event) {
                if (!$(event.target).closest('.action-icons').length) {
                    $('.dropdown-menu').hide();
                }
            });
        });
    </script>
</body>

</html>

<?php
// Close database connection at the end of the file
$conn->close();
?>
