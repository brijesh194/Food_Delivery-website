<?php
// Database configuration
$servername = "localhost";   // agar XAMPP ya Localhost use kar rahe ho
$username = "root";          // default XAMPP username
$password = "";              // default XAMPP password
$dbname = "food_delivery";   // tumhara database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// echo "Connected successfully";
?>
