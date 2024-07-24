<?php
session_start();
include 'connection.php'; // Ensure your database connection is correctly included

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Prepare statement to fetch user data
    $stmt = $conn->prepare("SELECT id, email, password, user_role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $row["password"])) {
            // Initialize session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_role'] = $row['user_role'];
            // Redirect based on user role
            switch ($row['user_role']) {
                case 1: // Administrator
                case 2: // Employee
                    header("Location: admin_dashboard.php");
                    exit();
                default:
                    // Handle unexpected role (optional)
                    echo "<script>alert('Invalid user role'); window.location.href = 'user_login.php';</script>";
                    exit();
            }
        } else {
            echo "<script>alert('Incorrect Email or Password.'); window.location.href = 'user_login.php';</script>";
        }
    } else {
        echo "<script>alert('Incorrect Email or Password.'); window.location.href = 'user_login.php';</script>";
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../CSS/login.css">
</head>
<body>
<div class="container-login">
    <h3>Login</h3>
    <form action="user_login.php" method="post">
        <input type="email" id="email" name="email" placeholder="Enter Your Email" required><br><br>
        <input type="password" id="password" name="password" placeholder="Enter Your Password" required><br><br>
        <input type="submit" value="Login" name="userLogin" class="btn btn-primary">
    </form><br>
</div>
</body>
</html>
