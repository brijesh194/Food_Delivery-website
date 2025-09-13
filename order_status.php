<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Tracking</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<style>
body {font-family: Poppins, sans-serif; background:#111; color:#fff; margin:0; padding:0;}
.container {max-width:900px; margin:40px auto; padding:20px;}
.order-card {background:#1e1e1e; border-radius:15px; padding:20px; box-shadow:0 6px 25px rgba(0,0,0,0.5); margin-bottom:20px; animation:fadeIn 0.6s;}
.order-card img {width:100px; height:100px; border-radius:10px; object-fit:cover;}
.order-info {display:flex; gap:20px; align-items:center;}
.map {height:350px; width:100%; border-radius:12px; margin-top:15px;}
.progress-container {background:#333; border-radius:20px; overflow:hidden; margin-top:15px;}
.progress-bar {height:20px; width:0; background:linear-gradient(90deg,#ff8c00,#ff3c00); text-align:center; color:#fff; font-size:12px; transition:width 1s linear;}
@keyframes fadeIn {from{opacity:0; transform:translateY(20px);} to{opacity:1; transform:translateY(0);}}
</style>
</head>
<body>
<div class="container">
  <h1>🚚 Track Your Order</h1>

  <div class="order-card">
    <div class="order-info">
      <img src="https://images.unsplash.com/photo-1600891964599-f61ba0e24092?q=80&w=500" alt="Pizza">
      <div>
        <h3>Cheese & Crust Pizza</h3>
        <p>Qty: 1 | ₹410</p>
        <p>Status: <span style="color:orange;">On the way</span></p>
      </div>
    </div>

    <!-- Progress Bar -->
    <div class="progress-container">
      <div id="progressBar" class="progress-bar">0%</div>
    </div>

    <div id="map" class="map"></div>
  </div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
// ===== Map initialize =====
var map = L.map('map').setView([28.6139, 77.2090], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '&copy; OSM contributors'
}).addTo(map);

var marker = L.marker([28.6139, 77.2090]).addTo(map).bindPopup("Delivery Boy 🚴‍♂️");

// ===== Real Location (Delivery Boy GPS) =====
if (navigator.geolocation) {
  navigator.geolocation.watchPosition(
    function(position) {
      var lat = position.coords.latitude;
      var lon = position.coords.longitude;
      marker.setLatLng([lat, lon]);
      map.setView([lat, lon], 15);
    },
    function(error) {
      console.error("GPS Error: ", error);
    },
    {enableHighAccuracy: true, maximumAge: 0, timeout: 5000}
  );
} else {
  alert("Geolocation not supported on this browser.");
}

// ===== Progress Bar (Estimated Delivery Time) =====
let progress = 0;
let etaMinutes = 15; // 15 min delivery time
let interval = (etaMinutes * 60 * 1000) / 100; // convert to ms

let progressBar = document.getElementById("progressBar");
let timer = setInterval(() => {
  if(progress < 100){
    progress++;
    progressBar.style.width = progress + "%";
    progressBar.innerText = progress + "%";
  } else {
    clearInterval(timer);
    progressBar.innerText = "Delivered ✅";
  }
}, interval);
</script>
</body>
</html>
