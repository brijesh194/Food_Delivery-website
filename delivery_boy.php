<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Delivery Boy Tracking</title>
</head>
<body style="background:#111;color:#fff;font-family:Poppins;">
  <h2>📍 Delivery Boy Live Tracking</h2>
  <p>Location is being shared in real-time...</p>

<script>
const orderId = 123; // ⚠️ yahan backend se dynamic order_id bhejna hoga

if ("geolocation" in navigator) {
  navigator.geolocation.watchPosition(function(position) {
    fetch("update_location.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `order_id=${orderId}&lat=${position.coords.latitude}&lng=${position.coords.longitude}`
    });
  });
} else {
  alert("Geolocation not supported");
}
</script>
</body>
</html>
