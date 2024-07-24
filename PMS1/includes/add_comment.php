<?php
session_start();
include 'connection.php';

// Check if form is submitted and project_id and comment are set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['project_id'], $_POST['comment'])) {
    $projectId = $_POST['project_id'];
    $comment = $_POST['comment'];
    $userId = $_SESSION['user_id']; // Assuming user is logged in and user_id is stored in session

    // Prepare and execute SQL statement to insert comment into database
    $sql = "INSERT INTO project_comments (project_id, user_id, comment) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $projectId, $userId, $comment);

    if ($stmt->execute()) {
        // Comment inserted successfully
        header("Location: project_description.php?id=" . $projectId);
        exit();
    } else {
        // Handle error if comment insertion fails
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Redirect or handle error if form data is not complete
    header("Location: project_description.php");
    exit();
}

$conn->close();
?>
