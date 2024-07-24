<?php
include "connection.php";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $number = $_POST["number"];
    $password = $_POST["password"];
    $user_role = intval($_POST["user_role"]); // Convert status to integer

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check_email_query = "SELECT COUNT(*) AS count FROM users WHERE email = ?";
    $stmt_check_email = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt_check_email, $check_email_query)) {
        die("SQL error: " . mysqli_stmt_error($stmt_check_email));
    }

    mysqli_stmt_bind_param($stmt_check_email, "s", $email);
    mysqli_stmt_execute($stmt_check_email);
    mysqli_stmt_store_result($stmt_check_email);
    mysqli_stmt_bind_result($stmt_check_email, $count);
    mysqli_stmt_fetch($stmt_check_email);

    if ($count > 0) {
        // Email already exists
        mysqli_stmt_close($stmt_check_email);
        mysqli_close($conn);
        echo "<script type='text/javascript'>
              alert('Email already exists. Please use a different email.');
              window.location.href = 'add-users.php';
              </script>";
        exit();
    }

    mysqli_stmt_close($stmt_check_email);

    // Proceed to insert new user if email doesn't exist
    $insert_query = "INSERT INTO users (name, email, number, password, user_role)
                     VALUES (?, ?, ?, ?, ?)";

    $stmt_insert = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt_insert, $insert_query)) {
        die("SQL error: " . mysqli_stmt_error($stmt_insert));
    }

    mysqli_stmt_bind_param($stmt_insert, "ssssi", $name, $email, $number, $hashed_password, $user_role);

    if (mysqli_stmt_execute($stmt_insert)) {
        // If insertion is successful, redirect with success message
        mysqli_stmt_close($stmt_insert);
        mysqli_close($conn);
        echo "<script type='text/javascript'>
              alert('User added successfully');
              window.location.href = 'users.php';
              </script>";
        exit();
    } else {
        // If execution fails, handle the error
        die("Error: " . mysqli_stmt_error($stmt_insert));
    }
} else {
    // Redirect if form not submitted
    header("Location: users.php");
    exit();
}
?>
