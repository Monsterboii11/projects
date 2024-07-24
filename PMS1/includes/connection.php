<?php
$conn = mysqli_connect("localhost", "root", "", "project_ms");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
