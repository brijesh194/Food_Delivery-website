<?php
$conn = new mysqli("localhost","root","","food_delivery");
if($conn->connect_error){
    die("DB Connection Failed: " . $conn->connect_error);
}
echo "DB Connected Successfully!";
?>
