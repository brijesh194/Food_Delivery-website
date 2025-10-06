<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])){
    header("Location: admin_login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD']=='POST'){
    $order_id = $_POST['order_id'];
    $status   = $_POST['status'];

    $conn = new mysqli("localhost","root","","food_delivery");
    if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

    // Fetch order for user email
    $order = $conn->query("SELECT * FROM menu_orders WHERE id='$order_id'")->fetch_assoc();
    $user_email = $order['user_email'] ?? '';

    // Update status
    $stmt = $conn->prepare("UPDATE menu_orders SET payment_status=? WHERE id=?");
    $stmt->bind_param("si",$status,$order_id);
    $stmt->execute();

    // Optional: Send email to user about status update
    if($user_email){
        require 'phpmailer/PHPMailer.php';
        require 'phpmailer/SMTP.php';
        require 'phpmailer/Exception.php';
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        try{
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'krishnaprajapati2252@gmail.com';
            $mail->Password   = 'jtfd gslw jfqc tagd';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;
            $mail->setFrom('krishnaprajapati2252@gmail.com','Food Delivery');
            $mail->addAddress($user_email);
            $mail->isHTML(true);
            $mail->Subject = "Your Order Status Updated";
            $mail->Body    = "<h3>Hello ".$order['user_name'].",</h3>
            <p>Your order <b>".$order['product_name']."</b> is now <b>$status</b>.</p>
            <p>Quantity: ".$order['quantity']." | Price: ₹".$order['product_price']."</p>
            <p>Payment Method: ".$order['payment_method']."</p>";
            $mail->send();
        }catch(Exception $e){}
    }

    $stmt->close();
    $conn->close();
    header("Location: admin_dashboard.php");
    exit();
}
?>
