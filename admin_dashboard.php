<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])){
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli("localhost","root","","food_delivery");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

// Query to get users (latest first)
$sql = "SELECT * FROM users ORDER BY id DESC LIMIT 10";
$result = $conn->query($sql);

// Initialize last_order_id अगर नहीं है
if(!isset($_SESSION['last_order_id'])){
    $result = $conn->query("SELECT MAX(id) as last_id FROM menu_orders")->fetch_assoc();
    $_SESSION['last_order_id'] = $result['last_id'] ?? 0;
}

// Fetch stats
$total_customers = $conn->query("SELECT COUNT(DISTINCT user_mobile) as total_customers FROM menu_orders")->fetch_assoc()['total_customers'];
$total_revenue = $conn->query("SELECT SUM(product_price * quantity) as revenue FROM menu_orders WHERE payment_status='Completed'")->fetch_assoc()['revenue'];
$total_orders = $conn->query("SELECT COUNT(*) as total FROM menu_orders")->fetch_assoc()['total'];
$pending_orders = $conn->query("SELECT COUNT(*) as total FROM menu_orders WHERE payment_status='pending'")->fetch_assoc()['total'];
$completed_orders = $conn->query("SELECT COUNT(*) as total FROM menu_orders WHERE payment_status='success' OR payment_status='Completed'")->fetch_assoc()['total'];
$cancelled_orders = $conn->query("SELECT COUNT(*) as total FROM menu_orders WHERE payment_status='Cancelled'")->fetch_assoc()['total'];

// Fetch chart data
$chart_data = $conn->query("SELECT product_name, SUM(quantity) as total_sold FROM menu_orders GROUP BY product_name");
$products = [];
$quantities = [];
while($row=$chart_data->fetch_assoc()){
    $products[] = $row['product_name'];
    $quantities[] = $row['total_sold'];
}

// Fetch latest 10 orders
$latest_orders = $conn->query("SELECT * FROM menu_orders ORDER BY id DESC LIMIT 10");

// Fetch top 10 selling products
$top_products = $conn->query("SELECT product_name, product_image, SUM(quantity) as total_sold FROM menu_orders GROUP BY product_name ORDER BY total_sold DESC LIMIT 10");

// Monthly Sales for Advanced Analytics
$monthly_sales = $conn->query("
    SELECT MONTH(created_at) AS month, SUM(product_price*quantity) AS revenue, COUNT(*) AS orders 
    FROM menu_orders 
    WHERE payment_status='Completed' 
    GROUP BY MONTH(created_at)
");
$months = [];
$revenues = [];
$orders_count = [];
while($row = $monthly_sales->fetch_assoc()){
    $months[] = date("F", mktime(0,0,0,$row['month'],10));
    $revenues[] = $row['revenue'];
    $orders_count[] = $row['orders'];
}

// Daily revenue for last 7 days
$daily_revenue = $conn->query("
    SELECT DATE(created_at) AS date, SUM(product_price*quantity) AS revenue 
    FROM menu_orders 
    WHERE payment_status='Completed' 
    AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
    GROUP BY DATE(created_at)
");
$days = [];
$day_revenue = [];
while($row = $daily_revenue->fetch_assoc()){
    $days[] = $row['date'];
    $day_revenue[] = $row['revenue'];
}

// Top customers
$top_customers = $conn->query("
    SELECT user_name, user_mobile, COUNT(*) AS total_orders, SUM(product_price*quantity) AS total_spent
    FROM menu_orders
    WHERE payment_status='Completed'
    GROUP BY user_mobile
    ORDER BY total_orders DESC
    LIMIT 5
");

// Payment method stats
$payment_stats = $conn->query("
    SELECT payment_method, COUNT(*) AS total
    FROM menu_orders
    WHERE payment_status='Completed'
    GROUP BY payment_method
");
$methods = [];
$method_count = [];
while($row = $payment_stats->fetch_assoc()){
    $methods[] = $row['payment_method'];
    $method_count[] = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">

<title> Admin Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<!-- Font Awesome CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Roboto',sans-serif;}
body{background:#f9f9f9;color:#222;font-family:'Roboto',sans-serif;}
header{background:#fff;padding:22px;text-align:center;font-size:28px;font-weight:bold;position:relative;box-shadow:0 6px 20px rgba(0,0,0,0.1);color:#0077ff;letter-spacing:1px;}
.logout{position:absolute;right:20px;top:10px;padding:10px 20px;background:linear-gradient(90deg,#00cc66,#00ff7f);border-radius:10px;color:#fff;text-decoration:none;font-weight:600;transition:0.3s;}
.logout:hover{transform:translateY(-3px);box-shadow:0 4px 15px rgba(0,204,102,0.6);}
.container{padding:30px;}
.cards{display:flex;flex-wrap:wrap;gap:25px;margin-bottom:30px;justify-content:space-between;}
.card{flex:1;min-width:220px;background:#fff;border-radius:20px;border:1px solid #ddd;box-shadow:0 10px 30px rgba(0,0,0,0.05);padding:25px;text-align:center;transition:0.5s ease;position:relative;overflow:hidden;}
.card:hover{transform:translateY(-12px) scale(1.03);box-shadow:0 20px 50px rgba(0,0,0,0.1);}
.card h3{margin-bottom:12px;color:#0077ff;font-size:18px;position:relative;z-index:1;}
.card p{font-size:28px;font-weight:bold;color:#222;position:relative;z-index:1;}
table{width:100%;border-collapse:collapse;margin-top:20px;border-radius:14px;overflow:hidden;box-shadow:0 10px 35px rgba(0,0,0,0.1);background:#fff;}
th,td{padding:14px;text-align:left;border-bottom:1px solid #ddd;}
th{background:#e6e6e6;font-weight:700;color:#0077ff;}
tr:hover{background:#f0f0f0;}
.status-pending{color:#ffaa00;font-weight:bold;}
.status-success,.status-completed{color:#00cc66;font-weight:bold;}
.status-cancelled{color:#ff4d4d;font-weight:bold;}
button{padding:8px 14px;border:none;border-radius:8px;cursor:pointer;font-weight:600;transition:0.3s;}
button.update{background:linear-gradient(90deg,#00cc66,#00ff7f);color:#111;margin-right:5px;}
button.update:hover{transform:scale(1.05);}
button.cancel{background:linear-gradient(90deg,#ff4d4d,#ff1a1a);}
button.cancel:hover{transform:scale(1.05);}
select{padding:6px;border-radius:8px;border:1px solid #ccc;background:#f0f0f0;color:#222;}
.search-box{margin-bottom:15px;}
.search-box input{margin-left: 81%; padding:10px;width:100%;max-width:350px;border-radius:10px;border:1px solid #ccc;background:#f0f0f0;color:#222;transition:0.3s;}
.search-box input:focus{outline:none;box-shadow:0 0 10px #0077ff;}
.chart-container{width:100%;max-width:900px;margin:0 auto 35px auto;background:#fff;padding:25px;border-radius:18px;box-shadow:0 10px 30px rgba(0,0,0,0.05);}
.section{margin-bottom:35px;}
.section h2{margin-bottom:10px;font-size:22px;color:#0077ff;border-left:5px solid #0077ff;padding-left:10px;}
.section img{width:60px;height:60px;object-fit:cover;border-radius:10px;box-shadow:0 4px 15px rgba(0,0,0,0.05);}
.stats-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 30px;
}
.stats-row .section {
    flex: 1;
    min-width: 400px;
    display: flex;
    flex-direction: column;
}
/* chart-container की height fix कर दी */
.stats-row .chart-container {
    flex: 1;
    height: 350px;  /* दोनों charts same height */
    width: 70%;    /* full width */
}

@media(max-width:768px){.cards{flex-direction:column;}}
#orderNotification{position:fixed;top:20px;right:20px;background:#00cc66;color:#fff;padding:20px 25px;border-radius:16px;box-shadow:0 8px 30px rgba(0,0,0,0.3);font-size:16px;font-weight:bold;display:none;z-index:9999;animation:slideIn 0.5s ease;}
@keyframes slideIn{from{opacity:0;transform:translateX(120%);}to{opacity:1;transform:translateX(0);}}
#themeToggle{position:absolute;left:20px;top:20px;padding:10px 20px;border:none;border-radius:10px;background:linear-gradient(90deg,#0077ff,#00ff7f);color:#fff;font-weight:600;cursor:pointer;transition:0.3s;}
#themeToggle:hover{transform: scale(1.05);box-shadow:0 4px 15px rgba(0,127,255,0.6);}
@keyframes slideIn{from{opacity:0;transform:translateX(120%);}to{opacity:1;transform:translateX(0);}}
.shake{
    animation: shake 0.5s;
}
@keyframes shake{
    0%{transform:rotate(0deg);}
    25%{transform:rotate(10deg);}
    50%{transform:rotate(-10deg);}
    75%{transform:rotate(10deg);}
    100%{transform:rotate(0deg);}
}
#notifList div{
    padding: 12px 15px;
    border-bottom: 1px solid #eee;
    cursor: pointer;
    transition: 0.3s;
    display: flex;
    align-items: center;
    gap: 10px;
}
/* Scrollbar hide */
#notifList::-webkit-scrollbar {
    width: 0;
    background: transparent;
}
#notifList {
    scrollbar-width: none;
}
#notifList div:hover{
    background: linear-gradient(90deg, #0077ff, #00cc66);
    color: #fff;
}
#notifList div img{
    width: 40px;
    height: 40px;
    border-radius: 8px;
    object-fit: cover;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
#notifList div .notif-text{
    flex:1px;
    font-size: 14px;
    line-height: 1.4;
}
#notifList div .notif-time{
    font-size: 12px;
    color: #666;
}
</style>
</head>
<body>

<header>
 👨🏻‍💼 Admin Dashboard
<!-- Notification Icon -->
<div style="position:absolute;right:220px;top:18px;">
    <button id="notifIcon" style="position:relative; font-size:20px; cursor:pointer; ">
        <i class="fa fa-bell"></i>
        <span id="notifCount" style="position:absolute; top:-5px; right:-8px; background:red; color:white; font-size:12px; padding:2px 6px; border-radius:50%; display:none;">0</span>
    </button>
    <div id="notifList" style="display:none; position:absolute; right:0; top:35px; width:300px; max-height:400px; overflow-y:auto; background:#fff; color:#111; border-radius:10px; box-shadow:0 5px 20px rgba(0,0,0,0.3); z-index:999;"></div>
</div>

<!-- Alert popup -->
<div id="orderNotification" style="position:fixed; top:20px; right:20px; background:linear-gradient(135deg,#ff4d4d,#ff1a1a); color:#fff; padding:20px 25px; border-radius:16px; box-shadow:0 8px 30px rgba(0,0,0,0.8); font-size:16px; font-weight:bold; display:none; z-index:9999; animation:slideIn 0.5s ease;"></div>
<audio id="notificationSound" src="notification.mp3" preload="auto"></audio>



<a class="logout" href="admin_logout.php">Logout</a>
<button id="themeToggle">🌙 Dark</button>
</header>
<!-- Popup Notification -->
<!-- <div id="orderPopup" style="display:none;position:fixed;top:20px;right:20px;background:#00cc66;color:#fff;padding:15px 20px;border-radius:12px;box-shadow:0 6px 20px rgba(0,0,0,0.3);font-weight:bold;z-index:9999;animation:slideIn 0.5s ease;"></div>
<audio id="notifSound" src="notification.mp3" preload="auto"></audio> -->
<div class="container">

    <div class="cards">
        <div class="card"><h3>Total 👥 Customers</h3><p class="count-up" data-target="<?php echo $total_customers ?? 0; ?>">0</p></div>
        <div class="card"><h3>Total 💰 Revenue</h3><p class="count-up" data-target=" <?php echo $total_revenue ?? 0; ?>">0</p></div>
        <div class="card"><h3>Total Orders</h3><p><?php echo $total_orders; ?></p></div>
        <div class="card"><h3>Pending</h3><p><?php echo $pending_orders; ?></p></div>
        <div class="card"><h3>Completed</h3><p><?php echo $completed_orders; ?></p></div>
        <div class="card"><h3>Cancelled</h3><p><?php echo $cancelled_orders; ?></p></div>
    </div>

    <!-- Chart Section -->
    <div class="chart-container">
        <canvas id="productChart"></canvas>
    </div>

    <!-- Advanced Analytics: Monthly Sales -->
    <div class="section">
        <h2>📊 Monthly Sales & Orders</h2>
        <div class="chart-container">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>

    <!-- Advanced Analytics: Daily Revenue -->
     <div class="stats-row">
    <div class="section">
        <h2>💰 Daily Revenue (Last 7 Days)</h2>
        <div class="chart-container">
            <canvas id="dailyChart"></canvas>
        </div>
    </div>

     <!-- Payment Method Stats -->
    <div class="section">
        <h2>💳 Payment Method Stats</h2>
        <div class="chart-container">
            <canvas id="paymentChart"></canvas>
        </div>
    </div>
     </div>

    <!-- Top Customers -->
    <div class="section">
        <h2>🏅 Top Customers</h2>
        <div class="search-box">
            <input type="text" class="customerSearch" placeholder="Search customer by name/mobile...">
        </div>
        <table class="customerTable">
            <tr><th>Name</th><th>Mobile</th><th>Total Orders</th><th>Total Spent</th></tr>
            <?php while($row=$top_customers->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['user_name']; ?></td>
                <td><?php echo $row['user_mobile']; ?></td>
                <td><?php echo $row['total_orders']; ?></td>
                <td>₹<?php echo $row['total_spent']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

   

    <!-- Latest Orders Section -->
    <div class="section">
        <h2>🆕 Latest Orders</h2>
        <div class="search-box">
            <input type="text" class="latestSearch" placeholder="Search latest orders by user/product...">
        </div>
        <table class="latestTable">
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Product</th>
                <th>Image</th>
                <th>Qty</th>
                <th>Payment</th>
                <th>Status</th>
            </tr>
            <?php while($row=$latest_orders->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['user_name']."<br>".$row['user_mobile']; ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td><img src="<?php echo $row['product_image']; ?>" alt="Product"></td>
                <td><?php echo $row['quantity']; ?></td>
                <td><?php echo $row['payment_status']; ?></td>
                <td class="status-<?php echo strtolower($row['payment_status']); ?>"><?php echo $row['payment_status']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <!-- Top Selling Products Section -->
    <div class="section">
        <h2>🏆 Top Selling Products</h2>
        <div class="search-box">
            <input type="text" class="topSearch" placeholder="Search top products...">
        </div>
        <table class="topTable">
            <tr>
                <th>Product</th>
                <th>Image</th>
                <th>Total Sold</th>
            </tr>
            <?php while($row=$top_products->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['product_name']; ?></td>
                <td><img src="<?php echo $row['product_image']; ?>" alt="Product"></td>
                <td><?php echo $row['total_sold']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <!-- Main Orders Table -->
    <div class="section">
        <h2> 🛒🛍️ Order Products</h2>
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Search all orders by user/product...">
        </div>
        <table id="ordersTable">
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Product</th>
                <th>Image</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Address</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php
            $orders = $conn->query("SELECT * FROM menu_orders ORDER BY id DESC");
            while($row=$orders->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['user_name']."<br>".$row['user_mobile']; ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td><img src="<?php echo $row['product_image']; ?>" alt="Product" style="width:60px;height:60px;object-fit:cover;border-radius:10px;box-shadow:0 4px 15px rgba(0,0,0,0.05);"></td>
                <td><?php echo $row['quantity']; ?></td>
                <td>₹<?php echo $row['product_price']; ?></td>
                <td>
                <?php 
                $full_address = $row['village'].", ".$row['city'].", ".$row['state']." - ".$row['zipcode']; 
                echo "<div style='font-size:14px;color:#222;'>$full_address</div>";
                ?>
                <br>
                <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($full_address); ?>" target="_blank" style="display:inline-flex; align-items:center; gap:6px; padding:6px 12px; background:linear-gradient(90deg,#2a7b9b,#57c785,#eddd53); color:#fff; text-decoration:none; border-radius:8px; font-size:13px; font-weight:200; box-shadow:0 4px 12px rgba(0,127,0,0.3); transition:0.3s;">
                    <img src="https://www.reshot.com/preview-assets/icons/U8SKFGC7RP/location-U8SKFGC7RP.svg" alt="map" width="20" height="20"> View on Map
                </a>
            </td>
                <td><?php echo $row['payment_method']."<br>".$row['payment_status']; ?></td>
                <td class="status-<?php echo strtolower($row['payment_status']); ?>"><?php echo $row['payment_status']; ?></td>
                <td>
                    <form method="POST" action="update_order_status.php" style="display:inline;">
                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                        <select name="status" required>
                            <option value="">Change Status</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                        <button type="submit" class="update">Update</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
    

</div>


<div id="orderNotification"></div>
<!-- 🔥 Users Section -->
<section style="margin:20px; background:#fff; border-radius:12px; box-shadow:0px 4px 10px rgba(0,0,0,0.1); padding:20px;">
    <h2 style="font-size:20px; margin-bottom:15px; color:#333;">👥 Recently Logged In Users</h2>
    <table style="width:100%; border-collapse:collapse; text-align:left;">
        <thead>
            <tr style="background:#f4f4f4; color:#333;">
                <th style="padding:10px; border-bottom:2px solid #ddd;">ID</th>
                <th style="padding:10px; border-bottom:2px solid #ddd;">Username</th>
                <th style="padding:10px; border-bottom:2px solid #ddd;">Email</th>
                <th style="padding:10px; border-bottom:2px solid #ddd;">Registered Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr style='border-bottom:1px solid #eee;'>";
                    echo "<td style='padding:10px;'>" . $row["id"] . "</td>";
                    echo "<td style='padding:10px;'>" . $row["name"] . "</td>";
                    echo "<td style='padding:10px;'>" . $row["email"] . "</td>";
                    echo "<td style='padding:10px;'>" . $row["created_at"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='padding:10px; text-align:center; color:#999;'>No users found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</section>
<script>
// Count up animation
document.querySelectorAll('.count-up').forEach(el=>{
    let target = +el.getAttribute('data-target');
    let count = 0;
    let increment = target / 80;
    let interval = setInterval(()=>{
        count+=increment;
        if(count>=target) count=target;
        el.textContent = Math.floor(count);
        if(count==target) clearInterval(interval);
    },20);
});

// Product Chart
const productCtx = document.getElementById('productChart').getContext('2d');
new Chart(productCtx,{
    type:'bar',
    data:{
        labels: <?php echo json_encode($products); ?>,
        datasets:[{
            label:'Total Sold',
            data:<?php echo json_encode($quantities); ?>,
            backgroundColor:'rgba(0,123,255,0.6)',
            borderRadius:6
        }]
    },
    options:{responsive:true,plugins:{legend:{display:false}}}
});

// Monthly Chart
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
new Chart(monthlyCtx,{
    type:'line',
    data:{
        labels: <?php echo json_encode($months); ?>,
        datasets:[
            {label:'Revenue', data:<?php echo json_encode($revenues); ?>, borderColor:'#0077ff', backgroundColor:'rgba(0,119,255,0.2)', fill:true, tension:0.4},
            {label:'Orders', data:<?php echo json_encode($orders_count); ?>, borderColor:'#00cc66', backgroundColor:'rgba(0,204,102,0.2)', fill:true, tension:0.4}
        ]
    },
    options:{responsive:true,plugins:{legend:{position:'top'}}}
});

// Daily Revenue Chart
const dailyCtx = document.getElementById('dailyChart').getContext('2d');
new Chart(dailyCtx,{
    type:'bar',
    data:{labels:<?php echo json_encode($days); ?>, datasets:[{label:'Revenue', data:<?php echo json_encode($day_revenue); ?>, backgroundColor:'#00cc66', borderRadius:6}]},
    options:{responsive:true,plugins:{legend:{display:false}}}
});

// Payment Chart
const paymentCtx = document.getElementById('paymentChart').getContext('2d');
new Chart(paymentCtx,{
    type:'doughnut',
    data:{labels:<?php echo json_encode($methods); ?>, datasets:[{data:<?php echo json_encode($method_count); ?>, backgroundColor:['#0077ff','#00cc66','#ffaa00','#ff4d4d']}]},
    options:{responsive:true,plugins:{legend:{position:'bottom'}}}
});

// Search Filters
function setupSearch(inputClass, tableClass){
    const input = document.querySelector(inputClass);
    const table = document.querySelector(tableClass);
    input.addEventListener('keyup',()=>{
        let filter = input.value.toLowerCase();
        Array.from(table.getElementsByTagName('tr')).forEach((row,i)=>{
            if(i===0) return;
            row.style.display = Array.from(row.cells).some(cell=>cell.textContent.toLowerCase().includes(filter))?'':'none';
        });
    });
}
setupSearch('.latestSearch','.latestTable');
setupSearch('.topSearch','.topTable');
setupSearch('.customerSearch','.customerTable');
setupSearch('#searchInput','#ordersTable');

// Dark/Light Toggle
const themeBtn = document.getElementById('themeToggle');
themeBtn.addEventListener('click',()=>{
    document.body.classList.toggle('dark-theme');
    if(document.body.classList.contains('dark-theme')){
        document.body.style.background='#1f1f1f';
        document.body.style.color='#f0f0f0';
        themeBtn.textContent='☀️ Light';
    }else{
        document.body.style.background='#f9f9f9';
        document.body.style.color='#222';
        themeBtn.textContent='🌙 Dark';
    }
});

// Real-time Order Notification
setInterval(()=>{
    fetch('check_new_orders.php')
    .then(r=>r.json())
    .then(data=>{
        if(data.new_orders>0){
            const notif = document.getElementById('orderNotification');
            notif.textContent = `🆕 ${data.new_orders} New Orders Received!`;
            notif.style.display='block';
            setTimeout(()=>{notif.style.display='none';},4000);
        }
    });
},5000);
</script>

<script>
    const notifIcon = document.getElementById('notifIcon');
const notifCount = document.getElementById('notifCount');
const notifList = document.getElementById('notifList');
const orderPopup = document.getElementById('orderNotification');
const notifSound = document.getElementById('notificationSound');

let popupShownIds = [];

// Toggle dropdown on click
notifIcon.addEventListener('click', ()=>{
    if(notifList.style.display==='block'){
        notifList.style.display='none';
    } else {
        notifList.style.display='block';
        // Mark notifications as read
        fetch('mark_notifications_read.php')
        .then(()=>{ notifCount.style.display='none'; popupShownIds = []; });
    }
});

// अगर बाहर क्लिक हो तो dropdown बंद कर दो
document.addEventListener('click', (e)=>{
    if(!notifIcon.contains(e.target) && !notifList.contains(e.target)){
        notifList.style.display='none';
    }
});

// Fetch notifications every 5s
function fetchNotifications(){
    fetch('fetch_notifications.php')
    .then(res=>res.json())
    .then(data=>{
        // Update unread count
        if(data.unread_count > 0){
            notifCount.style.display='inline-block';
            notifCount.textContent = data.unread_count;
        }

        // Show popup for new orders only
        data.new_orders.forEach(order => {
            if(!popupShownIds.includes(order.id)){
                showPopup(order);
                popupShownIds.push(order.id);
            }
        });

        // Update dropdown with last 10 orders
notifList.innerHTML = '';
if(data.messages.length===0){
    notifList.innerHTML = '<div style="padding:15px; text-align:center; color:#666;">No recent orders</div>';
} else {
    data.messages.forEach(order => {
        let div = document.createElement('div');

        // Add product image if available
        let imgHTML = order.product_image ? `<img src="${order.product_image}" alt="Product">` : `<div style="width:40px;height:40px;border-radius:8px;background:#ccc;"></div>`;

        div.innerHTML = `
            ${imgHTML}
            <div class="notif-text">
                <strong>Order #${order.id}</strong><br>
                ${order.user_name} ordered <b>${order.product_name}</b> x${order.quantity}
            </div>
            
        `;
        notifList.appendChild(div);
    });
}
    });
}
// Show popup with auto hide + sound
function showPopup(order){
    orderPopup.innerHTML = `🚨 New Order!<br><b>${order.user_name}</b> ordered <b>${order.product_name}</b> x${order.quantity}`;
    orderPopup.style.display='block';

    // Play sound safely
    if(notifSound.readyState >= 2){ // loaded
        notifSound.currentTime = 0;
        notifSound.play().catch(e=>console.log("Sound play error:", e));
    }

    // Auto hide popup after 5s
    setTimeout(()=>{
        orderPopup.style.display='none';
    },5000);
}

setInterval(fetchNotifications,5000);

</script>
</body>
</html>
