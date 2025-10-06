<?php
session_start();
$conn = new mysqli("localhost","root","", "food_delivery");

if(!$conn) { die("Connection failed: ".$conn->connect_error); }

if(!isset($_SESSION['last_order_id'])){
    $result = $conn->query("SELECT MAX(id) as last_id FROM menu_orders")->fetch_assoc();
    $_SESSION['last_order_id'] = $result['last_id'] ?? 0;
}

$last_id = $_SESSION['last_order_id'];

$order = $conn->query("SELECT * FROM menu_orders WHERE id > $last_id ORDER BY id ASC LIMIT 1")->fetch_assoc();

if($order){
    $_SESSION['last_order_id'] = $order['id'];
    echo json_encode([
        'new_order'=>true,
        'user_name'=>$order['user_name'],
        'product_name'=>$order['product_name'],
        'quantity'=>$order['quantity'],
        'id'=>$order['id']
    ]);
} else {
    echo json_encode(['new_order'=>false]);
}
?>
