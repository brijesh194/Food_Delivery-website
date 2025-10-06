<?php
session_start();
// Database connection
$servername = "localhost";
$username   = "root";   // apne DB ka username daalo
$password   = "";       // apne DB ka password daalo
$dbname     = "food_delivery"; // database ka naam jo tumne create kiya hai

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Form se data receive
$product_name   = $_POST['product_name'];
$product_price  = $_POST['product_price'];
$product_image  = $_POST['productImageInput'];
$user_id = $_SESSION['user_id'];
$user_name      = $_POST['user_name'];
$village        = $_POST['village'];
$city           = $_POST['city'];
$state          = $_POST['state'];
$zipcode        = $_POST['zipcode'];
$user_mobile    = $_POST['user_mobile'];
$payment_method = $_POST['payment_method'];
$quantity       = $_POST['quantity'];
$latitude       = $_POST['latitude'];
$longitude      = $_POST['longitude'];

// Insert query
$sql = "INSERT INTO orders_home 
(user_id, product_name, product_price, product_image, user_name, village, city, state, zipcode, user_mobile, payment_method, quantity, latitude, longitude)
VALUES 
(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isdssssssssidd", $user_id, $product_name, $product_price, $product_image, $user_name, $village, $city, $state, $zipcode, $user_mobile, $payment_method, $quantity, $latitude, $longitude);
$stmt->execute();


if ($stmt->execute()) {
    echo "
     <div id='successMessage' class='success-modal'>
        <div class='success-card'>
            <div class='checkmark'>
                <svg viewBox='0 0 52 52'>
                    <circle class='checkmark-circle' cx='26' cy='26' r='25' fill='none'/>
                    <path class='checkmark-check' fill='none' d='M14 27l7 7 17-17'/>
                </svg>
            </div>
            <h2>Order Successful!</h2>
            <p>Thank you <b>$user_name</b> for ordering <b>$product_name</b>.<br>
            Your order is being processed, we’ll contact you soon.</p>
            <button onclick=\"window.location.href='index.php'\">✨ Continue Shopping</button>
        </div>
    </div>

    <style>
      .success-modal {
        position: fixed;
        inset: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        background: linear-gradient(135deg, rgba(20,20,20,0.9), rgba(45,0,60,0.85));
        backdrop-filter: blur(6px);
        z-index: 9999;
        animation: fadeIn 0.5s ease forwards;
      }
      .success-card {
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,215,0,0.4);
        border-radius: 20px;
        padding: 40px 30px;
        width: 420px;
        text-align: center;
        color: #fff;
        box-shadow: 0 12px 35px rgba(0,0,0,0.5);
        font-family: 'Poppins', sans-serif;
        animation: floatIn 1s ease forwards, float 3s ease-in-out infinite;
      }
      .checkmark {
        margin-bottom: 20px;
      }
      .checkmark svg {
        width: 80px;
        height: 80px;
        stroke: #FFD700;
        stroke-width: 4;
        stroke-miterlimit: 10;
        box-shadow: 0 0 20px #ffd700;
        border-radius: 50%;
      }
      .checkmark-circle {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke-width: 4;
        stroke-miterlimit: 10;
        stroke: #FFD700;
        fill: none;
        animation: stroke 0.6s cubic-bezier(0.65,0,0.45,1) forwards;
      }
      .checkmark-check {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        animation: stroke 0.4s cubic-bezier(0.65,0,0.45,1) 0.6s forwards;
      }
      h2 {
        font-size: 24px;
        margin-bottom: 10px;
        color: #FFD700;
      }
      p {
        font-size: 15px;
        color: #f1f1f1;
        margin-bottom: 20px;
        line-height: 1.6;
      }
      button {
        padding: 12px 28px;
        background: linear-gradient(135deg,#FFD700,#FFA500);
        color: #111;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(255,215,0,0.4);
      }
      button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(255,215,0,0.6);
      }

      /* Animations */
      @keyframes fadeIn {
        from {opacity: 0;} to {opacity: 1;}
      }
      @keyframes stroke {
        100% { stroke-dashoffset: 0; }
      }
      @keyframes floatIn {
        0% {transform: translateY(50px) scale(0.9); opacity:0;}
        60% {transform: translateY(-10px) scale(1.05); opacity:1;}
        80% {transform: translateY(5px) scale(1);}
        100% {transform: translateY(0) scale(1);}
      }
      @keyframes float {
        0%, 100% {transform: translateY(0);}
        50% {transform: translateY(-8px);}
      }
    </style>
    ";
} else {
    echo "<p style='color:red; text-align:center;'>❌ Error: " . $conn->error . "</p>";
}
$stmt->close();
$conn->close();

?>
