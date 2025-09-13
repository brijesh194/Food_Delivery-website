<?php
session_start();
include 'db.php';
$order_id = intval($_GET['order_id'] ?? 0);
if(!$order_id){ echo "Order not found"; exit; }
$res = $conn->prepare("SELECT * FROM orders WHERE id=?");
$res->bind_param("i",$order_id);
$res->execute();
$row = $res->get_result()->fetch_assoc();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Order Success</title></head><body style="background:#111;color:#fff">
  <h2>Order Placed ✅</h2>
  <p>Thanks <?=htmlspecialchars($row['customer_name'])?> — your order #<?= $row['id'] ?> was received.</p>
  <p>Total: ₹<?= number_format($row['total_price'],2) ?></p>
  <p><a href="index.php">Back to Home</a> | <a href="orders_admin.php">Admin Orders</a></p>
</body></html>
