<?php
// Database connect
$conn = new mysqli("localhost", "root", "", "food_delivery");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #1e1e2f, #252545);
      color: #fff;
      font-family: 'Poppins', sans-serif;
      margin: 0;
    }
    .checkout-container {
      max-width: 600px;
      margin: 50px auto;
      padding: 25px;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 16px;
      backdrop-filter: blur(10px);
      box-shadow: 0 6px 20px rgba(0,0,0,0.4);
      animation: fadeIn 1s ease-in-out;
    }
    @keyframes fadeIn {
      from {opacity:0; transform:translateY(20px);}
      to {opacity:1; transform:translateY(0);}
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      font-weight: 600;
      color: #ffcc70;
      text-shadow: 0 0 10px rgba(255,204,112,0.6);
    }
    input, textarea, select {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 10px;
      border: none;
      background: rgba(255,255,255,0.08);
      color: #fff;
      font-size: 15px;
      transition: 0.3s;
    }
    input:focus, textarea:focus, select:focus {
      background: rgba(255,255,255,0.15);
      box-shadow: 0 0 0 2px #ffcc70;
      outline: none;
    }
    button {
      width: 100%;
      padding: 14px;
      margin-top: 10px;
      border: none;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: transform 0.2s, box-shadow 0.3s;
    }
    button.place-btn {
      background: linear-gradient(90deg,#ff9966,#ff5e62);
      color: #fff;
    }
    button.place-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 18px rgba(255,94,98,0.5);
    }
    button.location-btn {
      background: linear-gradient(90deg,#36d1dc,#5b86e5);
      color: #fff;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }
    .two-col {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
    }
    @media(max-width:600px){
      .two-col {grid-template-columns: 1fr;}
    }
  </style>
</head>
<body>

<div class="checkout-container">
  <h2><i class="fa-solid fa-bag-shopping"></i> Checkout</h2>
  <form action="place_order.php" method="POST">

  
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="text" name="mobile" placeholder="Mobile Number" required>

    <input type="text" name="village" id="village" placeholder="Village" required>

    <div class="two-col">
      <input type="text" name="city" id="city" placeholder="City" required>
      <input type="text" name="state" id="state" placeholder="State" required>
    </div>

    <input type="text" name="zip_code" placeholder="Zip Code" required>

    <button type="button" class="location-btn" onclick="getLocation()">
      <i class="fa-solid fa-location-dot"></i> Use My Current Location
    </button>

    <textarea name="address" id="address" placeholder="Full Address" rows="3" required></textarea>

    <select name="payment" required>
      <option value="">Select Payment Method</option>
      <option value="COD">Cash on Delivery</option>
      <option value="Online">Online Payment</option>
    </select>

    <!-- ✅ Hidden input me cart ka JSON bhejna -->
    <input type="hidden" id="cartData" name="cartData">
    <input type="hidden" name="total_price" id="totalPrice">

    <button type="submit" class="place-btn">Place Order</button>
  </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const product = JSON.parse(localStorage.getItem("checkoutItem"));
  if(product){
    document.getElementById("cartData").value = JSON.stringify([product]);
  }
  

  document.querySelector("form").addEventListener("submit", function() {
    localStorage.removeItem("checkoutItem"); // Order ke baad remove
  });
});

// ✅ Current Location Fetch
async function getLocation(){
  if(navigator.geolocation){
    navigator.geolocation.getCurrentPosition(success, error);
  } else {
    alert("Geolocation is not supported by your browser.");
  }
}

async function success(position){
  const lat = position.coords.latitude;
  const lng = position.coords.longitude;
  const res = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
  const data = await res.json();

  if(data.address){
    document.getElementById("village").value = data.address.village || data.address.hamlet || "";
    document.getElementById("city").value = data.address.city || data.address.town || "";
    document.getElementById("state").value = data.address.state || "";
    document.getElementById("zip").value = data.address.postcode || "";
    document.getElementById("address").value = data.display_name;
  }
}

function error(){
  alert("Unable to fetch location. Please enter manually.");
}
</script>

</body>
</html>
