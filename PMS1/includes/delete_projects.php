<?php
session_start();
include 'connection.php'; // Include your database connection

// Validate and sanitize input (ensure delete_id is set and is numeric)
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Delete related rows in project_team_members table first
    $delete_team_members_sql = "DELETE FROM project_team_members WHERE project_id = ?";
    $delete_team_members_stmt = $conn->prepare($delete_team_members_sql);
    $delete_team_members_stmt->bind_param("i", $delete_id);
    $delete_team_members_stmt->execute();
    $delete_team_members_stmt->close();

    // Now delete the project from project_list table
    $delete_project_sql = "DELETE FROM project_list WHERE id = ?";
    $delete_project_stmt = $conn->prepare($delete_project_sql);
    $delete_project_stmt->bind_param("i", $delete_id);
    
    if ($delete_project_stmt->execute()) {
        // Redirect back to projects.php or wherever appropriate
        echo "<script type='text/javascript'>
        alert('Project deleted successfully');
        window.location.href = 'projects.php';
        </script>";
        exit;
    } else {
        // Handle deletion failure
        echo "Error deleting project.";
    }
    
    $delete_project_stmt->close();
} else {
    // Handle invalid delete request
    echo "Invalid delete request.";
}

// Close database connection
$conn->close();
?>
