<?php
// Check if session is not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user session is set
if (!isset($_SESSION['user_id'])) {
    die("User session not found.");
}

$user_id = $_SESSION['user_id'];

// Determine the query based on user role
if (!isset($_SESSION['user_role'])) {
    die("User role not found.");
}

$user_role = $_SESSION['user_role'];

// Query to get the count of projects user is involved in
if ($user_role == 1) {
    // Admin can see all projects
    $sql = "SELECT COUNT(DISTINCT id) AS total_projects FROM project_list";
    $stmt = $conn->prepare($sql);
} elseif ($user_role == 2) {
    // Team Member can see projects they are assigned to
    $sql = "SELECT COUNT(DISTINCT pl.id) AS total_projects 
            FROM project_list pl
            JOIN project_team_members ptm ON pl.id = ptm.project_id
            WHERE ptm.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
} else {
    die("Invalid user role.");
}

// Execute query and fetch total projects count
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total_projects = $row['total_projects'];
    } else {
        $total_projects = 0; // Default value if no projects are found
    }
} else {
    die("Error preparing SQL statement: " . $conn->error);
}

// Close the database connection
$stmt->close();
$conn->close();

// Return the total projects count
echo $total_projects;
?>
