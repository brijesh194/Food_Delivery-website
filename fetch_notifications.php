<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])){
    echo json_encode(['unread_count'=>0,'messages'=>[]]);
    exit;
}

$conn = new mysqli("localhost","root","","food_delivery");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

// Initialize unread_orders in session
if(!isset($_SESSION['unread_orders'])){
    $_SESSION['unread_orders'] = [];
}

// Fetch new orders (id > last_seen_order_id)
$last_seen_id = $_SESSION['last_seen_order_id'] ?? 0;
$result = $conn->query("SELECT id, user_name, product_name, quantity FROM menu_orders WHERE id > $last_seen_id ORDER BY id ASC");

$new_orders = [];
$max_id = $last_seen_id;

while($row = $result->fetch_assoc()){
    $new_orders[] = $row;
    $_SESSION['unread_orders'][$row['id']] = $row; // Add to unread
    if($row['id'] > $max_id) $max_id = $row['id'];
}

// Update last_seen_order_id
$_SESSION['last_seen_order_id'] = $max_id;

// Prepare messages for dropdown (last 10 orders)
$dropdown_result = $conn->query("SELECT id, user_name, product_name, quantity FROM menu_orders ORDER BY id DESC LIMIT 10");
$dropdown_messages = [];
while($row = $dropdown_result->fetch_assoc()){
    $dropdown_messages[] = $row;
}

// Return unread count + dropdown orders
echo json_encode([
    'unread_count' => count($_SESSION['unread_orders']),
    'messages' => $dropdown_messages,
    'new_orders' => $new_orders // for popup only
]);
?>
