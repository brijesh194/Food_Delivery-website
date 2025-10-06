<?php
session_start();
if(isset($_SESSION['unread_orders'])){
    $_SESSION['unread_orders'] = []; // Clear unread orders
}
echo "ok";
?>
