<?php
include 'connection.php';

if (isset($_POST['adminLogin'])) {
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    $query = "SELECT email, password, name, id FROM admin WHERE email = '$email' AND password = '$password'";
    $query_run = mysqli_query($connection, $query);

    if (mysqli_num_rows($query_run) > 0) {
        $user_data = mysqli_fetch_assoc($query_run);
        session_start();
        $_SESSION['user_id'] = $user_data['id']; 
        $_SESSION['user_name'] = $user_data['name']; 

        echo "<script type='text/javascript'>
                window.location.href = 'admin_dashboard.php';
              </script>";
        exit;
    } else {
        echo "<script type='text/javascript'>
                alert('Please enter a correct email or password');
                window.location.href = 'admin_login.php';
              </script>";
        exit; 
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootsrap file -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!--  jQuery  -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- style file -->
    <link rel="stylesheet" href="../CSS/login.css">
</head>
<body>
<div class="container-login">
    <h3>Admin Login</h3>
    <form action="" method="post">
        <input type="email" id="email" name="email" placeholder="Enter Your Email" required><br><br>
        <input type="password" id="password" name="password" placeholder="Enter Your Password" required><br><br>
        <input type="submit" value="Login" name="adminLogin" class="btn btn-primary">
        <p class="registration-link">Don't have an account? <a href="user_registration.php">Register here</a></p>
    </form><br>
    <a href="index.php" class="btn btn-danger go-to-home">Go to Home</a>
</div>
    
</body>
</html>