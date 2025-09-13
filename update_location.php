<?php
// Database connect
$conn = new mysqli("localhost", "root", "", "food_delivery");

// Delivery boy sends lat/lng
$delivery_boy_id = $_POST['id'];
$lat = $_POST['lat'];
$lng = $_POST['lng'];

$conn->query("REPLACE INTO delivery_location (delivery_boy_id, latitude, longitude) 
              VALUES ($delivery_boy_id, $lat, $lng)");

echo "Location updated";
?>
