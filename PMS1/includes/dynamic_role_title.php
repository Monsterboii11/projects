<?php

// Check if user is logged in and has a valid user role
if (isset($_SESSION['user_role']) && isset($_SESSION['user_email'])) {
    $user_role = $_SESSION['user_role'];
    $user_email = $_SESSION['user_email'];

    // Determine the greeting based on user role
    switch ($user_role) {
        case 1: // Administrator
            $greeting = 'Administrator';
            break;
        case 2: // Employee
            $greeting = 'Employee';
            break;
        default:
            $greeting = 'Welcome';
            break;
    }
} else {
    // Redirect to login page if session variables are not set
    header("Location: user_login.php");
    exit;
}

?>