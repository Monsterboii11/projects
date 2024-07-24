<?php
include 'connection.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if delete action is triggered
if (isset($_GET['delete_id']) && !empty($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Prepare SQL statement to delete user
    $delete_sql = "DELETE FROM users WHERE id = ?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $delete_sql)) {
        mysqli_stmt_bind_param($stmt, "i", $delete_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            echo "<script>alert('User deleted successfully'); window.location.href='users.php';</script>";
            exit();
        } else {
            echo "Error deleting record: " . mysqli_error($conn);
        }
    } else {
        echo "SQL error: " . mysqli_error($conn);
    }
} else {
    echo "<script type='text/javascript'>
    alert('No user id provided');
    window.location.href = 'users.php';
    </script>";
}
?>
