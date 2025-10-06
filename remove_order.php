<?php
session_start();

// Database connection
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "food_delivery";

$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

if(isset($_POST['order_id']) && isset($_POST['table_name'])){
    $order_id   = intval($_POST['order_id']);
    $table_name = $_POST['table_name'];

    // Delete query
    $sql = "DELETE FROM $table_name WHERE id='$order_id' AND user_id='".$_SESSION['user_id']."'";

    if($conn->query($sql) === TRUE){
        header("Location: my_orders.php?msg=Order removed successfully");
        exit;
    } else {
        echo "Error deleting record: ".$conn->error;
    }
}
$conn->close();
?>
