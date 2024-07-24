<?php
session_start(); // Start session to access $_SESSION variables

// Include connection to database
include 'connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $projectId = $_POST['project_id'];

    // Directory where uploaded files will be saved
    $targetDir = "uploads/";

    // Create the uploads directory if it doesn't exist
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true); // Create directory with full permissions (for local development)
    }

    // File name
    $fileName = basename($_FILES["file"]["name"]);
    $targetFilePath = $targetDir . $fileName;

    // Check if file is selected
    if (!empty($_FILES["file"]["name"])) {
        // Upload file to server
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
            // Insert file details into database
            $insertSql = "INSERT INTO project_files (project_id, file_name, file_path, uploaded_by) 
                          VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insertSql);
            $userId = $_SESSION['user_id']; // Assuming you have user ID in session
            $stmt->bind_param("isss", $projectId, $fileName, $targetFilePath, $userId);

            if ($stmt->execute()) {
                // File uploaded successfully, display success message or redirect
                echo "<script type='text/javascript'>
                        alert('File uploaded Successfully!!');
                        window.location.href = 'project_description.php?id=" . urlencode($projectId) . "'; // Redirect with project_id
                      </script>";
            } else {
                echo "Error inserting file details into database.";
            }

            $stmt->close();
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Please select a file.";
    }
}

$conn->close();
