<?php
$conn = new mysqli("localhost", "root", "", "food_delivery");

$delivery_boy_id = $_GET['id'];
$res = $conn->query("SELECT latitude, longitude, updated_at 
                     FROM delivery_location 
                     WHERE delivery_boy_id=$delivery_boy_id LIMIT 1");

$row = $res->fetch_assoc();
echo json_encode($row);
?>
