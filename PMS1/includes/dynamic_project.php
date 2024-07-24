<?php
include "connection.php";

if (!isset($_SESSION['user_role'])) {
    header("Location: user_login.php");
    exit;
}


$user_role = $_SESSION['user_role'];

// Initialize variables
$sql = "";
$stmt = null;

// Prepare SQL based on user role
if ($user_role == 1) {
    // Admin can see all projects
    $sql = "SELECT * FROM project_list";
    $stmt = $conn->prepare($sql);
} elseif ($user_role == 2) {
    // Regular user can only see projects they are associated with
    $sql = "SELECT pl.* FROM project_list pl
            JOIN project_team_members pua ON pl.id = pua.project_id
            WHERE pua.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['user_id']);
} else {
    // Redirect or handle unauthorized access
    header("Location: user_login.php");
    exit();
}

// Execute query and handle errors
if ($stmt) {
    $stmt->execute();
    $projectResult = $stmt->get_result();
    if (!$projectResult) {
        echo "Error executing SQL: " . $stmt->error;
        exit;
    }
} else {
    echo "Error preparing SQL: " . $conn->error;
    exit;
}

// Close the prepared statement
$stmt->close();
?>
