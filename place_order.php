<?php

$conn = new mysqli("localhost", "root", "", "food_delivery");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = $_POST['name'];
    $mobile  = $_POST['mobile'];
    $address = $_POST['address'];
    $payment = $_POST['payment'];
    $cartData = json_decode($_POST['cartData'], true);

    if (!empty($cartData)) {
    foreach ($cartData as $product) {
        $product_id = $product['id'];
        $product_name = $product['name'];
        $product_price = $product['price'];
        $product_img = $product['img'];
        $quantity = isset($product['quantity']) ? $product['quantity'] : 1;

        $stmt = $conn->prepare("INSERT INTO orders (customer_name, mobile, address, payment_method, product_id, product_name, price, image, quantity, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
        $stmt->bind_param("ssssisisi", $name, $mobile, $address, $payment, $product_id, $product_name, $product_price, $product_img, $quantity);
        $stmt->execute();

        // ✅ Tracking table
        $order_id = $stmt->insert_id;
        $stmt2 = $conn->prepare("INSERT INTO orders_tracking (order_id, delivery_boy_lat, delivery_boy_lng) VALUES (?, 0, 0)");
        $stmt2->bind_param("i", $order_id);
        $stmt2->execute();
    }
    echo "
<div class='order-box'>
    <h2 class='order-title'>✅ Order placed successfully!</h2>
    <p class='order-link'><a href='order_status.php'>Track Your Order</a></p>
</div>
";

} else {
    echo "❌ No product selected!";
}
}
?>

<!-- Particles -->
<div class="particle" style="left:10%; animation-delay:0s;"></div>
<div class="particle" style="left:30%; animation-delay:2s;"></div>
<div class="particle" style="left:50%; animation-delay:4s;"></div>
<div class="particle" style="left:70%; animation-delay:6s;"></div>
<div class="particle" style="left:90%; animation-delay:8s;"></div>

<!-- Floating Food Emojis -->
<div class="food" style="left:10%; animation-delay:0s;">🍔</div>
<div class="food" style="left:25%; animation-delay:3s;">🍕</div>
<div class="food" style="left:45%; animation-delay:6s;">🍩</div>
<div class="food" style="left:65%; animation-delay:2s;">🥗</div>
<div class="food" style="left:80%; animation-delay:5s;">🥤</div>
<div class="food" style="left:10%; animation-delay:0s;">🍔</div>
<div class="food" style="left:25%; animation-delay:3s;">🍕</div>
<div class="food" style="left:45%; animation-delay:6s;">🍩</div>
<div class="food" style="left:65%; animation-delay:2s;">🥗</div>
<div class="food" style="left:80%; animation-delay:5s;">🥤</div>

<style>
    :root{
  --bg:#0f0f12;
  --card:#17171c;
  --muted:#9aa0a6;
  --text:#f3f4f6;
  --accent:#ff4b2b;
  --accent-2:#fe744f;
  --border:#24242b;
  --glass:rgba(255,255,255,0.06);
  --ok:#22c55e;
  --warn:#f59e0b;
}
/* Centering the message */
/* Base Styles (Desktop) */
body {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
  margin: 0;
  font-family: 'Poppins', sans-serif;
  background: #0a0a0a;
  overflow: hidden;
}

/* Gradient Blur Lights */
body::before, body::after {
  content: '';
  position: absolute;
  width: 500px;
  height: 500px;
  border-radius: 50%;
  filter: blur(180px);
  z-index: 0;
}
body::before { top: -100px; left: -100px; background: rgba(0, 255, 170, 0.2); }
body::after { bottom: -100px; right: -100px; background: rgba(0, 136, 255, 0.2); }

/* Order Box */
.order-box {
  position: relative;
  z-index: 2;
  text-align: center;
  padding: 40px 60px;
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 20px;
  backdrop-filter: blur(15px) saturate(180%);
  -webkit-backdrop-filter: blur(15px) saturate(180%);
  box-shadow: 0 8px 32px rgba(0, 255, 170, 0.3);
  animation: zoomIn 0.8s ease;
}

/* Success Title */
.order-title {
  color: #00ff88;
  font-size: 28px;
  font-weight: 700;
  margin-bottom: 50px;
  animation: glow 2s infinite alternate;
}

/* Link Button */
.order-link a {
  text-decoration: none;
  font-size: 18px;
  font-weight: 600;
  color: #0a0a0a;
  background: linear-gradient(135deg, #00ff88, #00ccff);
  padding: 12px 25px;
  border-radius: 10px;
  transition: 0.3s;
  box-shadow: 0 4px 20px rgba(0, 255, 170, 0.4);
}
.order-link a:hover {
  transform: scale(1.05);
  box-shadow: 0 6px 25px rgba(0, 204, 255, 0.6);
}

/* Glow Animation */
@keyframes glow {
  from { text-shadow: 0 0 8px #00ff88, 0 0 15px #00ccff; }
  to   { text-shadow: 0 0 20px #00ff88, 0 0 40px #00ccff; }
}

/* Zoom In Animation */
@keyframes zoomIn {
  0% { transform: scale(0.7); opacity: 0; }
  100% { transform: scale(1); opacity: 1; }
}

/* Floating Food Icons */
.food {
  position: absolute;
  font-size: 40px;
  animation: floatFood 12s linear infinite;
  z-index: 1;
  opacity: 0.9;
}
@keyframes floatFood {
  from { transform: translateY(110vh) rotate(0deg); opacity: 0; }
  10% { opacity: 1; }
  to { transform: translateY(-20vh) rotate(360deg); opacity: 0; }
}
/* Floating Particles */
.particle {
  position: absolute;
  width: 6px;
  height: 6px;
  background: rgba(0, 255, 170, 0.9);
  border-radius: 50%;
  filter: blur(2px);
  animation: float 10s infinite linear;
  z-index: 1;
}

@keyframes float {
  from { transform: translateY(100vh) translateX(0); opacity: 0; }
  10%  { opacity: 1; }
  to   { transform: translateY(-10vh) translateX(40px); opacity: 0; }
}
/* ===== Mobile Responsive ===== */
@media (max-width: 768px) {
  .order-box {
    padding: 25px 30px;
    border-radius: 15px;
  }

  .order-title {
    font-size: 22px;
    margin-bottom: 20px;
  }

  .order-link a {
    font-size: 16px;
    padding: 10px 20px;
  }

  .food {
    font-size: 25px; /* chhota emoji for mobile */
  }
}

@media (max-width: 480px) {
  .order-box {
    padding: 20px 20px;
  }

  .order-title {
    font-size: 20px;
  }

  .order-link a {
    font-size: 14px;
    padding: 8px 15px;
  }

  .food {
    font-size: 20px;
  }
}



</style>