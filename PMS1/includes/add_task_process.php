<?php
session_start();
include 'connection.php';

// Redirect to login page if user is not authenticated
if (!isset($_SESSION['user_role'])) {
    header("Location: user_login.php");
    exit;
}

// Validate user role for adding tasks (e.g., only admin or project manager)
$user_role = $_SESSION['user_role'];
if (!in_array($user_role, [1, 2])) {
    header("Location: user_login.php"); // Redirect to login page or access denied page
    exit;
}

// Example SQL query in add_task_process.php
$task_name = mysqli_real_escape_string($conn, $_POST['task_name']);
$status = intval($_POST['status']); // Convert to integer for safety
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$description = mysqli_real_escape_string($conn, $_POST['description']);

// Ensure that the selected project member (user_id) is valid for the logged-in user's role
if ($user_role == 2) {
    // Project manager (user_role == 2) can only assign tasks to themselves
    $project_members = $_SESSION['user_id']; // Assign task to themselves
} else {
    // Admin (user_role == 1) can assign tasks to any project manager
    $project_members = intval($_POST['project_members']); // Convert to integer for safety
}

// Insert task into database using prepared statement for better security
$stmt = $conn->prepare("INSERT INTO task_list (task_name, status, start_date, end_date, user_id, description) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sissss", $task_name, $status, $start_date, $end_date, $project_members, $description);

// Execute the statement and check for success
if ($stmt->execute()) {
    // Redirect or show success message
    header("Location: task.php");
    exit();
} else {
    // Handle error
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
