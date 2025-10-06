<?php
session_start();

// Database connection
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "food_delivery";

$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

$user_id = $_SESSION['user_id']; // current logged-in user

// Orders from orders_home
$sql_home = "SELECT id, product_name, product_price, product_image, payment_method, quantity, village, city, state, zipcode, 'orders_home' AS table_name, 'Home Order' AS order_type, created_at 
             FROM orders_home WHERE user_id='$user_id'";

// Orders from menu_orders
$sql_menu = "SELECT id, product_name, product_price, product_image, payment_method, quantity, village, city, state, zipcode, 'menu_orders' AS table_name, 'Menu Order' AS order_type, created_at 
             FROM menu_orders WHERE user_id='$user_id'";

// Combine
$sql = "($sql_home) UNION ALL ($sql_menu) ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Orders</title>
  <style>
    *{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
    body{
      min-height:100vh;
      display:flex;
      flex-direction:column;
      align-items:center;
      background:url('https://images.unsplash.com/photo-1600891964599-f61ba0e24092') no-repeat center center/cover;
      position:relative;
      padding:40px 0;
    }
    body::before{
      content:"";
      position:absolute;
      inset:0;
      background:rgba(0,0,0,0.5);
      backdrop-filter:blur(8px);
      z-index:0;
    }
    h1{
      position:relative;
      z-index:1;
      text-align:center;
      color:#fff;
      font-size:2.5rem;
      margin-bottom:25px;
      text-shadow:0 0 20px rgba(255,65,108,0.8);
    }
    .orders-container{
      position:relative;
      z-index:1;
      display:grid;
      grid-template-columns: repeat(4, 1fr);
      gap:25px;
      justify-content:center;
      width:90%;
      max-width:1400px;
      margin:0 auto;
    }
    @media(max-width:1024px){
      .orders-container{ grid-template-columns: repeat(2, 1fr); }
    }
    @media(max-width:600px){
      .orders-container{ grid-template-columns: 1fr; }
    }
    .order-card{
      background:rgba(255,255,255,0.08);
      border-radius:20px;
      overflow:hidden;
      backdrop-filter:blur(20px);
      box-shadow:0 10px 30px rgba(0,0,0,0.6);
      transition:transform 0.4s,box-shadow 0.4s;
    }
    .order-card:hover{
      transform:translateY(-10px) scale(1.03);
      box-shadow:0 20px 40px rgba(0,0,0,0.8);
    }
    .order-card img{
      width:100%;
      height:220px;
      object-fit:cover;
      border-bottom:2px solid rgba(255,255,255,0.2);
    }
    .order-details{
      padding:18px;
      color:#fff;
    }
    .order-details h3{
      font-size:1.2rem;
      margin-bottom:10px;
      color:#ffb347;
    }
    .order-details p{
      font-size:0.9rem;
      margin-bottom:6px;
      opacity:0.9;
    }
    .order-type{
      display:inline-block;
      margin-top:10px;
      padding:6px 14px;
      background:linear-gradient(135deg,#ff416c,#ff4b2b,#ffb347,#00f0ff);
      color:#fff;
      border-radius:20px;
      font-size:0.8rem;
      font-weight:700;
      text-transform:uppercase;
      box-shadow:0 0 12px rgba(255,65,108,0.6),0 0 8px rgba(0,255,255,0.4);
    }
    .remove-btn{
      margin-top:12px;
      padding:10px 18px;
      background:#ff4b2b;
      border:none;
      border-radius:10px;
      color:#fff;
      font-weight:600;
      cursor:pointer;
      transition:0.3s;
    }
    .remove-btn:hover{
      background:#ff1a1a;
      transform:scale(1.05);
    }
    .no-orders{
      grid-column:1/-1;
      text-align:center;
      font-size:1.2rem;
      color:#fff;
      opacity:0.8;
    }
  </style>
</head>
<body>
  <h1>My Orders</h1>
  <div class="orders-container">
    <?php
    if($result && $result->num_rows>0){
        while($row = $result->fetch_assoc()){
            echo '<div class="order-card">';
            echo '<img src="'.$row['product_image'].'" alt="'.$row['product_name'].'">';
            echo '<div class="order-details">';
            echo '<h3>'.$row['product_name'].'</h3>';
            echo '<p>Price: ₹'.$row['product_price'].'</p>';
            echo '<p>Quantity: '.$row['quantity'].'</p>';
            echo '<p>Payment: '.$row['payment_method'].'</p>';
            echo '<p>Address: '.$row['village'].', '.$row['city'].', '.$row['state'].' - '.$row['zipcode'].'</p>';
            echo '<p>Ordered on: '.$row['created_at'].'</p>';
            echo '<span class="order-type">'.$row['order_type'].'</span><br>';
            echo '<form method="POST" action="remove_order.php" onsubmit="return confirm(\'Are you sure you want to remove this order?\');">';
            echo '<input type="hidden" name="order_id" value="'.$row['id'].'">';
            echo '<input type="hidden" name="table_name" value="'.$row['table_name'].'">';
            echo '<button type="submit" class="remove-btn">Remove</button>';
            echo '</form>';
            echo '</div></div>';
        }
    }else{
        echo '<p class="no-orders">No orders found! Place your first order now.</p>';
    }
    $conn->close();
    ?>
  </div>
</body>
</html>
