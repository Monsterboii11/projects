<?php
include "connection.php";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $project_name = $_POST["project_name"];
    $status = intval($_POST["status"]); // Convert status to integer    
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];
    $description = $_POST["description"];
    
    // Retrieve user_id from session
    session_start();
    $user_id = $_SESSION['user_id'];

    // Prepare SQL statement for inserting project details
    $sql = "INSERT INTO project_list (project_name, status, start_date, end_date, description, user_id)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die("SQL error: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "sisssi", $project_name, $status, $start_date, $end_date, $description, $user_id);

    // Execute the insertion query
    if (mysqli_stmt_execute($stmt)) {
        $project_id = mysqli_insert_id($conn); // Get the last inserted project ID

        // Insert selected team members into project_team_members table
        if (isset($_POST['project_team_members']) && !empty($_POST['project_team_members'])) {
            $team_members = $_POST['project_team_members'];

            // Prepare SQL statement for inserting project team members
            $sql_team = "INSERT INTO project_team_members (project_id, user_id) VALUES (?, ?)";
            $stmt_team = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt_team, $sql_team)) {
                die("SQL error: " . mysqli_error($conn));
            }

            // Bind parameters and execute for each selected member
            foreach ($team_members as $member_id) {
                mysqli_stmt_bind_param($stmt_team, "ii", $project_id, $member_id);
                mysqli_stmt_execute($stmt_team);
            }

            mysqli_stmt_close($stmt_team);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        // Determine redirect based on user role
        if ($_SESSION['user_role'] == 1) {
            // If admin or project manager, redirect to projects.php
            header("Location: projects.php");
        } elseif ($_SESSION['user_role'] == 2) {
            // If team member, redirect to task.php
            header("Location: task.php");
        }
        exit();
    } else {
        // If execution fails, handle the error
        die("Error: " . mysqli_stmt_error($stmt));
    }
} else {
    // Redirect if form not submitted
    header("Location: add_project.php");
    exit();
}
?>
