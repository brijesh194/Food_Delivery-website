<?php
$host = "localhost";
$db   = "food_delivery";
$user = "root";        // apne server ka username
$pass = "";            // apne server ka password
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
