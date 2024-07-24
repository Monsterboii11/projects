<?php
session_start();
include "dynamic_role_title.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Description</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../CSS/user_dashboard.css">
    <style>
        /* Project details container */
        .project-details {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 100%;
            width: calc(100% - 40px);
            margin: 20px;
        }

        .project-details h1 {
            display: flex;
            justify-content: center;
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }

        /* Details list styling */
        .project-details p {
            margin: 10px 0px;
        }

        .project-details p strong {
            font-weight: bold;
            position: relative;
            /* Ensure relative positioning for absolute positioning */
        }

        .project-details p strong::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0px;
            width: 100%;
            height: 2px;
            background-color: blue;
            /* Red underline color */
        }

        .project-details .status {
            font-weight: bold;
            /* Adjust other styles as needed */
        }

        /* Define font colors for different status classes */
        .project-details .status.completed {
            color: blue;
            /* Change to desired color */
        }

        .project-details .status.process {
            color: brown;
            /* Change to desired color */
        }

        .project-details .status.pending {
            color: red;
            /* Change to desired color */
        }

        /* Project members list styling */
        .project-details .project-members {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .project-details .project-members h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }

        .project-details .project-members ul {
            list-style-type: none;
            padding: 0;
        }

        .project-details .project-members li {
            margin-bottom: 5px;
        }

        .project-details .project-members .member-name {
            font-weight: bold;
        }

        .project-details .project-members .member-role {
            font-style: italic;
        }

        /* Textarea styling */
        .project-details textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: none;
            /* Allow vertical resizing */
            font-family: 'poppins';
            font-size: 16px;
            line-height: 1.5;
            min-height: 100px;
            /* Minimum height for better UX */
        }

        /* Project name and status layout */
        .project-details .name-status {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .project-details .name-status .left {
            width: 50%;
            padding-right: 10px;
        }

        .project-details .name-status .right {
            width: 50%;
            padding-left: 10px;
        }

        /* Styling for comments section */
        /* Styling for comments section */
        .comments-section {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 45%;
            width: calc(50% - 40px);
            /* Adjust width to take 50% of the available space */
            margin: 20px;
            float: left;
            /* Float left to align side by side */
            clear: both;
            /* Clear previous floats */
            box-sizing: border-box;
            /* Include padding and border in width calculation */
        }

        .comments-section h2 {
            font-size: 20px;
            color: #333;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .comments-section ul {
            list-style-type: none;
            padding: 0;
        }

        .comments-section li {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .comments-section .comment-meta {
            font-size: 14px;
            color: #666;
        }

        .comments-section .comment-content {
            font-size: 16px;
            line-height: 1.6;
        }

        .comments-section .add-comment-form {
            margin-top: 20px;
        }

        .comments-section .add-comment-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: none;
            /* Allow vertical resizing */
            font-family: 'poppins';
            font-size: 16px;
            line-height: 1.5;
            min-height: 80px;
            /* Minimum height for better UX */
        }

        .comments-section .add-comment-form button {
            margin-top: 10px;
            padding: 8px 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
        }

        .comments-section .add-comment-form button:hover {
            background-color: #0056b3;
        }

        /* Styling for files section */
        .files-section {
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 45%;
            width: calc(50% - 40px);
            /* Adjust width to take 50% of the available space */
            margin: 20px;
            float: left;
            /* Float left to align side by side */
            box-sizing: border-box;
            /* Include padding and border in width calculation */
        }

        .files-section h2 {
            font-size: 20px;
            color: #333;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .add-file-form {
            margin-bottom: 20px;
        }

        .add-file-form input[type="file"] {
            margin-right: 10px;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 3px;
            background-color: #fff;
            font-size: 14px;
            line-height: 1.5;
        }

        .add-file-form button {
            padding: 8px 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
        }

        .add-file-form button:hover {
            background-color: #0056b3;
        }

        .files-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .files-list li {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .files-list li a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .files-list li a:hover {
            text-decoration: underline;
        }

        .files-list li .file-info {
            font-size: 14px;
            color: #666;
        }

        .files-list li .file-actions {
            display: flex;
            align-items: center;
        }

        .files-list li .file-actions a {
            margin-left: 10px;
            color: #007bff;
            text-decoration: none;
        }

        .files-list li .file-actions a:hover {
            text-decoration: underline;
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
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Search...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
            <a href="profile.php" class="profile">
                <i class='bx bxs-user' style='font-size: 1.5em;'></i>
            </a>
        </nav>
        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>View Projects</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Projects</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">View Projects</a>
                        </li>
                    </ul>
                </div>
                <div class="project-details">
                    <?php
                    // Include necessary files and database connection
                    include 'connection.php';

                    // Check if project ID is provided in the URL
                    if (isset($_GET['id'])) {
                        $projectId = $_GET['id'];

                        // Prepare and execute SQL to fetch project details
                        $sql = "SELECT * FROM project_list WHERE id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $projectId);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            $projectDetails = $result->fetch_assoc();

                            $status = strtolower($projectDetails['status']);
                            if ($status === "completed") {
                                $statusClass = "completed";
                            } elseif ($status === "process") {
                                $statusClass = "process";
                            } elseif ($status === "pending") {
                                $statusClass = "pending";
                            } else {
                                $statusClass = ""; // Default class if status is unknown
                            }
                            // Display project details
                            echo '<div class="name-status">';
                            echo '<div class="left">';
                            echo '<p><strong>Project Name:</strong><br> ' . htmlspecialchars($projectDetails['project_name']) . '</p>';
                            echo '<p><strong>Status:</strong><br> <span class="status ' . $statusClass . '">' . htmlspecialchars($projectDetails['status']) . '</span></p>';
                            echo '</div>';
                            echo '<div class="right">';
                            echo '<p><strong>Start Date:</strong><br> ' . htmlspecialchars($projectDetails['start_date']) . '</p>';
                            echo '<p><strong>End Date:</strong><br> ' . htmlspecialchars($projectDetails['end_date']) . '</p>';
                            echo '</div>';
                            echo '</div>';

                            echo '<label for="project-description"><strong>Description:</strong></label><br>';
                            echo '<textarea id="project-description" class="project-description" rows="4" readonly>' . htmlspecialchars($projectDetails['description']) . '</textarea>';

                            // Fetch project team members
                            $sqlMembers = "SELECT u.id, u.name FROM project_team_members ptm
                         INNER JOIN users u ON ptm.user_id = u.id
                         WHERE ptm.project_id = ?";
                            $stmtMembers = $conn->prepare($sqlMembers);
                            $stmtMembers->bind_param("i", $projectId);
                            $stmtMembers->execute();
                            $resultMembers = $stmtMembers->get_result();

                            echo '<p><strong>Project Members:</strong></p>';
                            echo '<ul>';
                            while ($row = $resultMembers->fetch_assoc()) {
                                echo '<li>' . htmlspecialchars($row['name']) . '</li>';
                            }
                            echo '</ul>';
                        } else {
                            echo 'Project not found.';
                        }

                        $stmt->close();
                        $stmtMembers->close();
                    } else {
                        echo 'Project ID not provided.';
                    }

                    $conn->close();
                    ?>
                </div>
                <!-- Comments Section -->
                <div class="comments-section">
                    <h2>Comments</h2>
                    <?php
                    // Include necessary files and database connection
                    include 'connection.php';

                    // Check if project ID is provided in the URL
                    if (isset($_GET['id'])) {
                        $projectId = $_GET['id'];

                        // Display existing comments
                        $sqlComments = "SELECT pc.comment, pc.timestamp, u.name AS commenter FROM project_comments pc
                            INNER JOIN users u ON pc.user_id = u.id
                            WHERE pc.project_id = ?
                            ORDER BY pc.timestamp DESC";
                        $stmtComments = $conn->prepare($sqlComments);
                        $stmtComments->bind_param("i", $projectId);
                        $stmtComments->execute();
                        $resultComments = $stmtComments->get_result();

                        echo '<ul>';
                        while ($row = $resultComments->fetch_assoc()) {
                            echo '<li>';
                            echo '<p><strong>' . htmlspecialchars($row['commenter']) . '</strong> - ' . htmlspecialchars($row['timestamp']) . '</p>';
                            echo '<p>' . htmlspecialchars($row['comment']) . '</p>';
                            echo '</li>';
                        }
                        echo '</ul>';

                        $stmtComments->close();
                    }
                    $conn->close();
                    ?>

                    <!-- Add Comment Form -->
                    <form action="add_comment.php" method="post" class="add-comment-form">
                        <input type="hidden" name="project_id" value="<?php echo htmlspecialchars($projectId); ?>">
                        <textarea name="comment" placeholder="Add a comment..." rows="3" required></textarea><br>
                        <button type="submit">Add Comment</button>
                    </form>
                </div>
                <!-- Comments Section -->

                <div class="files-section">
                    <h2>Attachments</h2>

                    <!-- Display Uploaded Files -->
                    <?php
                    include 'connection.php';

                    // Fetch and display files associated with the project
                    $sqlFiles = "SELECT id, file_name, file_path, upload_date FROM project_files WHERE project_id = ?";
                    $stmtFiles = $conn->prepare($sqlFiles);
                    $stmtFiles->bind_param("i", $projectId);
                    $stmtFiles->execute();
                    $resultFiles = $stmtFiles->get_result();

                    if ($resultFiles->num_rows > 0) {
                        echo '<ul>'; // Start an unordered list for files
                        while ($row = $resultFiles->fetch_assoc()) {
                            echo '<li>';
                            echo '<div>';
                            echo '<a href="' . htmlspecialchars($row['file_path']) . '" target="_blank">' . htmlspecialchars($row['file_name']) . '</a>';
                            echo ' - Uploaded on ' . htmlspecialchars($row['upload_date']);
                            echo '</div>';
                            echo '<div class="file-actions">';
                            echo '<form action="file_delete.php" method="post">';
                            echo '<input type="hidden" name="file_id" value="' . htmlspecialchars($row['id']) . '">';
                            echo '<button type="submit" name="delete_file">Delete</button>';
                            echo '</form>';
                            echo '</div>';
                            echo '</li>';
                        }

                        echo '</ul>'; // Close the unordered list
                    } else {
                        echo '<p>No files uploaded yet.</p>';
                    }
                    echo '<br><br>';

                    $stmtFiles->close();
                    $conn->close();
                    ?>

                    <!-- Add File Form -->
                    <form action="file_upload.php" method="post" enctype="multipart/form-data" class="add-file-form">
                        <input type="hidden" name="project_id" value="<?php echo htmlspecialchars($projectId); ?>">
                        <input type="file" name="file" required>
                        <button type="submit">Add File</button>
                    </form>
                </div>

        </main>
        <!-- MAIN -->
    </section>
    <script src="../JS/script.js"></script>
</body>

</html>