<?php
session_start(); // Start session to access $_SESSION variables

// Include connection to database
include 'connection.php';

// Check if file ID is provided via POST request
if (isset($_POST['file_id'])) {
    $fileId = $_POST['file_id'];

    // Fetch file details to get file path
    $sqlFetchFile = "SELECT file_path, project_id FROM project_files WHERE id = ?";
    $stmtFetchFile = $conn->prepare($sqlFetchFile);
    $stmtFetchFile->bind_param("i", $fileId);
    $stmtFetchFile->execute();
    $resultFetchFile = $stmtFetchFile->get_result();

    if ($resultFetchFile->num_rows > 0) {
        $fileData = $resultFetchFile->fetch_assoc();
        $filePath = $fileData['file_path'];
        $projectId = $fileData['project_id']; // Get project ID from database

        // Check if file exists
        if (file_exists($filePath)) {
            // Delete file from server
            if (unlink($filePath)) {
                // File deleted successfully, now delete record from database
                $sqlDeleteFile = "DELETE FROM project_files WHERE id = ?";
                $stmtDeleteFile = $conn->prepare($sqlDeleteFile);
                $stmtDeleteFile->bind_param("i", $fileId);

                if ($stmtDeleteFile->execute()) {
                    // Redirect back to project description page after deletion
                    if (!empty($projectId)) {
                        echo "<script>alert('File deleted successfully.'); window.location.href = 'project_description.php?project_id=" . urlencode($projectId) . "';</script>";
                    } else {
                        echo "Error: Project ID not found.";
                    }
                } else {
                    echo "Error deleting file record from database.";
                }

                $stmtDeleteFile->close();
            } else {
                echo "Error deleting file from server.";
            }
        } else {
            echo "File not found.";
        }
    } else {
        echo "File details not found in database.";
    }

    $stmtFetchFile->close();
    $conn->close();
} else {
    echo "File ID not provided.";
}
