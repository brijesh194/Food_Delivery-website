<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost","root","","food_delivery");
if($conn->connect_error) { echo json_encode(['error'=>'DB']); exit; }

$total_orders = $conn->query("SELECT COUNT(*) as total FROM menu_orders")->fetch_assoc()['total'];
$pending_orders = $conn->query("SELECT COUNT(*) as total FROM menu_orders WHERE (order_status='pending' OR payment_status='pending')")->fetch_assoc()['total'];
$completed_orders = $conn->query("SELECT COUNT(*) as total FROM menu_orders WHERE (order_status='completed' OR payment_status='success' OR payment_status='Completed')")->fetch_assoc()['total'];
$cancelled_orders = $conn->query("SELECT COUNT(*) as total FROM menu_orders WHERE (order_status='cancelled' OR payment_status='Cancelled')")->fetch_assoc()['total'];

echo json_encode([
    'total_orders' => (int)$total_orders,
    'pending_orders' => (int)$pending_orders,
    'completed_orders' => (int)$completed_orders,
    'cancelled_orders' => (int)$cancelled_orders
]);
$conn->close();


