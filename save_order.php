<?php
// Add PHPMailer namespaces at the top
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

// अगर user login नहीं है तो redirect
if (!isset($_SESSION['user_id'])) {
    echo "
    <div style='
        display:flex;
        justify-content:center;
        align-items:center;
        height:100vh;
        background:#111;
        font-family:Arial, sans-serif;
    '>
        <div style=\"
            background:rgba(255, 77, 77, 0.1);
            border:1px solid #ff4d4d;
            color:#ff4d4d;
            padding:20px 30px;
            border-radius:12px;
            font-size:18px;
            font-weight:bold;
            text-align:center;
            box-shadow:0 8px 25px rgba(0,0,0,0.6);
            animation:fadeIn 0.5s ease;
        \">
            ⚠️ Please login to place an order
            <br><br>
            <a href='auth.php' style=\"
                display:inline-block;
                margin-top:12px;
                padding:10px 20px;
                background:#ff4d4d;
                color:#fff;
                border-radius:8px;
                text-decoration:none;
                font-weight:600;
                transition:0.3s;
            \">Go to Login</a>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from {opacity:0; transform:scale(0.9);}
            to {opacity:1; transform:scale(1);}
        }
        a:hover {
            background:#ff1a1a !important;
        }
    </style>
    ";
    exit();
}

// Database Connection
$host = "localhost";
$user = "root";      
$password = "";      
$dbname = "food_delivery";

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// 🔹 User का email निकालो users टेबल से
$user_email = "";
$sql = "SELECT email FROM users WHERE id = ?";
$stmt_email = $conn->prepare($sql);
$stmt_email->bind_param("i", $user_id);
$stmt_email->execute();
$stmt_email->bind_result($user_email);
$stmt_email->fetch();
$stmt_email->close();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get POST data safely
    $product_name   = $_POST['product_name'] ?? '';
    $product_price  = $_POST['product_price'] ?? '';
    $product_image  = $_POST['product_image'] ?? '';
    $user_name      = $_POST['user_name'] ?? '';
    $user_id        = $_SESSION['user_id'];

    $village        = $_POST['village'] ?? '';
    $city           = $_POST['city'] ?? '';
    $zipcode        = $_POST['zipcode'] ?? '';
    $state          = $_POST['state'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    $user_mobile    = $_POST['user_mobile'] ?? '';
    $quantity       = $_POST['quantity'] ?? 1;

    $latitude       = $_POST['latitude'] ?? '';
    $longitude      = $_POST['longitude'] ?? '';

    // New: payment info (for Razorpay)
    $payment_status = "pending";
    $payment_id     = "";

    if ($payment_method === "Cash on Delivery") {
        $payment_status = "pending";
    } elseif ($payment_method === "UPI") {
        $payment_id = $_POST['razorpay_payment_id'] ?? '';
        $payment_status = $payment_id ? "success" : "failed";
    }

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO menu_orders 
        (user_id, product_name, product_price, product_image, user_name, village, city, zipcode, state, payment_method, payment_status, payment_id, user_mobile, quantity, latitude, longitude) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
    $stmt->bind_param(
        "issssssssssssiss",
        $user_id,
        $product_name,
        $product_price,
        $product_image,
        $user_name,
        $village,
        $city,
        $zipcode,
        $state,
        $payment_method,
        $payment_status,
        $payment_id,
        $user_mobile,
        $quantity,
        $latitude,
        $longitude
    );

    if ($stmt->execute()) {
        // PHPMailer: include and send email after successful order
        require 'phpmailer/Exception.php';
        require 'phpmailer/PHPMailer.php';
        require 'phpmailer/SMTP.php';

        $orderDetails = "
            <h2>Order Details</h2>
            <p><b>User:</b> $user_name ($user_mobile)</p>
            <p><b>Address:</b> $village, $city, $state - $zipcode</p>
            <p><b>Product:</b> $product_name</p>
            <p><b>Price:</b> ₹$product_price</p>
            <p><b>Quantity:</b> $quantity</p>
            <p><b>Payment Method:</b> $payment_method</p>
            <p><b>Payment Status:</b> $payment_status</p>
            <p><b>Payment ID:</b> $payment_id</p>
            <p><b>Location:</b> $latitude , $longitude</p>
            <br>
            <img src='$product_image' style='width:200px;border-radius:10px'>
        ";

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'krishnaprajapati2252@gmail.com';   // अपनी Gmail डालो
            $mail->Password   = 'jtfd gslw jfqc tagd';              // Gmail App Password डालो
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('krishnaprajapati2252@gmail.com', 'Food Delivery');

            // 🔹 Admin Email
            $mail->addAddress('krishnaprajapati2252@gmail.com', 'Admin');
            $mail->isHTML(true);
            $mail->Subject = "🛒 New Order Received!";
            $mail->Body    = $orderDetails;
            $mail->send();

            // 🔹 User Email (same details)
            if (!empty($user_email)) {
                $mail->clearAddresses();
                $mail->addAddress($user_email, $user_name);
                $mail->Subject = "✅ Your Order Confirmation";
                $mail->Body    = $orderDetails;
                $mail->send();
            }

        } catch (Exception $e) {
            // Error ignore करो
        }

        echo "
        <div class='order-box' style=\"
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
            background:#111;
            color:#fff;
            font-family:Arial, sans-serif;
        \">
            <div style=\"
                background:rgba(0,255,127,0.1);
                border:1px solid #00ff7f;
                padding:25px 35px;
                border-radius:15px;
                text-align:center;
                box-shadow:0 8px 25px rgba(0,0,0,0.6);
                animation:fadeIn 0.5s ease;
            \">
                <h2 style='color:#00ff7f'>✅ Order placed successfully!</h2>
                <p>Payment Status: <strong style='color:#ffd24d;'>$payment_status</strong></p>
            </div>
        </div>
        ";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
