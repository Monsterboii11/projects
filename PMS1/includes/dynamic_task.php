<?php
include 'connection.php'; // Ensure this file is correctly included and connects to the database

// Fetch the task count based on user role
if ($_SESSION['user_role'] == 1) {
    // For admin, count all tasks
    $sql = "SELECT COUNT(*) AS task_count FROM task_list";
} else {
    // For other roles (e.g., employee), count only their assigned tasks
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT COUNT(*) AS task_count FROM task_list WHERE user_id = $user_id";
}

$resultTodo = $conn->query($sql);

// Check if query was successful
if ($resultTodo && $resultTodo->num_rows > 0) {
    $row = $resultTodo->fetch_assoc();
    $task_count = $row['task_count'];
} else {
    $task_count = 0; // Default to 0 if no tasks found or query fails
}

// Close database connection
$conn->close();
?>
