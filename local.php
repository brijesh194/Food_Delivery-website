<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])){
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli("localhost","root","","food_delivery");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

// Stats Cards
$total_customers = $conn->query("SELECT COUNT(DISTINCT user_mobile) as total_customers FROM menu_orders")->fetch_assoc()['total_customers'];
$total_revenue = $conn->query("SELECT SUM(product_price * quantity) as revenue FROM menu_orders WHERE payment_status='Completed'")->fetch_assoc()['revenue'] ?? 0;
$total_orders = $conn->query("SELECT COUNT(*) as total FROM menu_orders")->fetch_assoc()['total'];
$pending_orders = $conn->query("SELECT COUNT(*) as total FROM menu_orders WHERE payment_status='pending'")->fetch_assoc()['total'];
$completed_orders = $conn->query("SELECT COUNT(*) as total FROM menu_orders WHERE payment_status='success' OR payment_status='Completed'")->fetch_assoc()['total'];
$cancelled_orders = $conn->query("SELECT COUNT(*) as total FROM menu_orders WHERE payment_status='Cancelled'")->fetch_assoc()['total'];

// Chart Data
$chart_data = $conn->query("SELECT product_name, SUM(quantity) as total_sold FROM menu_orders GROUP BY product_name");
$products=[]; $quantities=[];
while($row=$chart_data->fetch_assoc()){$products[]=$row['product_name'];$quantities[]=$row['total_sold'];}

// Latest Orders
$latest_orders = $conn->query("SELECT * FROM menu_orders ORDER BY id DESC LIMIT 10");

// Top Products
$top_products = $conn->query("SELECT product_name, product_image, SUM(quantity) as total_sold FROM menu_orders GROUP BY product_name ORDER BY total_sold DESC LIMIT 10");

// Monthly Sales
$monthly_sales = $conn->query("SELECT MONTH(created_at) AS month, SUM(product_price*quantity) AS revenue, COUNT(*) AS orders FROM menu_orders WHERE payment_status='Completed' GROUP BY MONTH(created_at)");
$months=[];$revenues=[];$orders_count=[];
while($row=$monthly_sales->fetch_assoc()){
    $months[]=date("F",mktime(0,0,0,$row['month'],10));
    $revenues[]=$row['revenue'];
    $orders_count[]=$row['orders'];
}

// Daily Revenue Last 7 Days
$daily_revenue = $conn->query("SELECT DATE(created_at) AS date, SUM(product_price*quantity) AS revenue FROM menu_orders WHERE payment_status='Completed' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY DATE(created_at)");
$days=[];$day_revenue=[];
while($row=$daily_revenue->fetch_assoc()){$days[]=$row['date'];$day_revenue[]=$row['revenue'];}

// Top Customers
$top_customers = $conn->query("SELECT user_name, user_mobile, COUNT(*) AS total_orders, SUM(product_price*quantity) AS total_spent FROM menu_orders WHERE payment_status='Completed' GROUP BY user_mobile ORDER BY total_orders DESC LIMIT 5");

// Payment Methods
$payment_stats = $conn->query("SELECT payment_method, COUNT(*) AS total FROM menu_orders WHERE payment_status='Completed' GROUP BY payment_method");
$methods=[];$method_count=[];
while($row=$payment_stats->fetch_assoc()){$methods[]=$row['payment_method'];$method_count[]=$row['total'];}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>🔥 Admin Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Roboto',sans-serif;}
body{background:#f5f7fa;color:#222;}
header{position:fixed;top:0;left:0;width:100%;height:60px;background:#fff;display:flex;justify-content:space-between;align-items:center;padding:0 20px;box-shadow:0 3px 15px rgba(0,0,0,0.1);z-index:1000;}
header h1{font-size:20px;color:#0077ff;font-weight:700;}
.logout{padding:8px 15px;background:#ff4d4d;color:#fff;border-radius:8px;}
#themeToggle{padding:8px 15px;border:none;border-radius:8px;background:#0077ff;color:#fff;cursor:pointer;}
.sidebar{position:fixed;top:60px;left:0;width:220px;height:100%;background:#fff;box-shadow:2px 0 12px rgba(0,0,0,0.05);padding-top:20px;}
.sidebar a{display:flex;align-items:center;padding:12px 20px;color:#0077ff;font-weight:500;margin-bottom:10px;border-radius:8px;transition:0.3s;}
.sidebar a:hover{background:#e6f0ff;}
.container{margin-left:240px;padding:80px 30px;}
.cards{display:flex;flex-wrap:wrap;gap:20px;margin-bottom:30px;}
.card{flex:1 1 220px;background:#fff;padding:20px;border-radius:15px;box-shadow:0 5px 15px rgba(0,0,0,0.08);transition:0.3s;text-align:center;}
.card:hover{transform:translateY(-5px);}
.card h3{font-size:16px;color:#0077ff;margin-bottom:10px;}
.card p{font-size:24px;font-weight:700;color:#222;}
.chart-container{background:#fff;padding:20px;border-radius:15px;box-shadow:0 5px 15px rgba(0,0,0,0.08);margin-bottom:30px;}
.section h2{margin-bottom:15px;color:#0077ff;}
table{width:100%;border-collapse:collapse;background:#fff;box-shadow:0 5px 15px rgba(0,0,0,0.05);border-radius:10px;overflow:hidden;margin-bottom:30px;}
th,td{padding:12px;text-align:left;border-bottom:1px solid #eee;}
th{background:#e6f0ff;color:#0077ff;}
tr:hover{background:#f0f8ff;}
.status-pending{color:#ffaa00;font-weight:600;}
.status-success,.status-completed{color:#00cc66;font-weight:600;}
.status-cancelled{color:#ff4d4d;font-weight:600;}
.search-box{margin-bottom:10px;}
.search-box input{width:100%;max-width:300px;padding:8px;border-radius:8px;border:1px solid #ccc;}
@media(max-width:992px){.container{margin-left:0;padding:90px 15px;}}
@media(max-width:768px){.cards{flex-direction:column;}}
</style>
</head>
<body>

<header>
<h1>🔥 Admin Dashboard</h1>
<div>
<button id="themeToggle">🌙 Dark</button>
<a class="logout" href="admin_logout.php">Logout</a>
</div>
</header>

<div class="sidebar">
<a href="#">🏠 Home</a>
<a href="#">📊 Dashboard</a>
<a href="#">🛒 Orders</a>
<a href="#">🍔 Products</a>
<a href="#">👥 Customers</a>
<a href="#">📈 Analytics</a>
<a href="#">⚙️ Settings</a>
</div>

<div class="container">

<!-- Stats Cards -->
<div class="cards">
<div class="card"><h3>Total Customers</h3><p><?php echo $total_customers; ?></p></div>
<div class="card"><h3>Total Revenue</h3><p>₹<?php echo $total_revenue; ?></p></div>
<div class="card"><h3>Total Orders</h3><p><?php echo $total_orders; ?></p></div>
<div class="card"><h3>Pending Orders</h3><p><?php echo $pending_orders; ?></p></div>
<div class="card"><h3>Completed Orders</h3><p><?php echo $completed_orders; ?></p></div>
<div class="card"><h3>Cancelled Orders</h3><p><?php echo $cancelled_orders; ?></p></div>
</div>

<!-- Charts -->
<div class="chart-container">
<h2>Top Selling Products</h2>
<canvas id="productChart"></canvas>
</div>

<div class="chart-container">
<h2>Monthly Sales & Orders</h2>
<canvas id="monthlyChart"></canvas>
</div>

<div class="chart-container">
<h2>Daily Revenue (Last 7 Days)</h2>
<canvas id="dailyChart"></canvas>
</div>

<div class="chart-container">
<h2>Payment Methods</h2>
<canvas id="paymentChart"></canvas>
</div>

<!-- Latest Orders -->
<div class="section">
<h2>Latest Orders</h2>
<div class="search-box"><input type="text" class="latestSearch" placeholder="Search latest orders..."></div>
<table class="latestTable">
<tr><th>ID</th><th>User</th><th>Product</th><th>Image</th><th>Qty</th><th>Payment</th><th>Status</th></tr>
<?php while($row=$latest_orders->fetch_assoc()): ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['user_name']."<br>".$row['user_mobile']; ?></td>
<td><?php echo $row['product_name']; ?></td>
<td><img src="<?php echo $row['product_image']; ?>" style="width:50px;height:50px;"></td>
<td><?php echo $row['quantity']; ?></td>
<td><?php echo $row['payment_status']; ?></td>
<td class="status-<?php echo strtolower($row['payment_status']); ?>"><?php echo $row['payment_status']; ?></td>
</tr>
<?php endwhile; ?>
</table>
</div>

<!-- Top Products Table -->
<div class="section">
<h2>Top Selling Products Table</h2>
<div class="search-box"><input type="text" class="topSearch" placeholder="Search top products..."></div>
<table class="topTable">
<tr><th>Product</th><th>Image</th><th>Total Sold</th></tr>
<?php while($row=$top_products->fetch_assoc()): ?>
<tr>
<td><?php echo $row['product_name']; ?></td>
<td><img src="<?php echo $row['product_image']; ?>" style="width:50px;height:50px;"></td>
<td><?php echo $row['total_sold']; ?></td>
</tr>
<?php endwhile; ?>
</table>
</div>

<!-- Top Customers Table -->
<div class="section">
<h2>Top Customers</h2>
<table>
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

</div>

<script>
// Theme Toggle
document.getElementById('themeToggle').addEventListener('click',()=>{
document.body.classList.toggle('dark-mode');
});

// Charts
const productChart = new Chart(document.getElementById('productChart').getContext('2d'),{
type:'bar',
data:{labels:<?php echo json_encode($products); ?>,datasets:[{label:'Quantity Sold',data:<?php echo json_encode($quantities); ?>,backgroundColor:'#0077ff'}]},
options:{responsive:true}
});

const monthlyChart = new Chart(document.getElementById('monthlyChart'),{
type:'line',
data:{
labels:<?php echo json_encode($months); ?>,
datasets:[
{label:'Revenue',data:<?php echo json_encode($revenues); ?>,borderColor:'#0077ff',backgroundColor:'rgba(0,119,255,0.2)'},
{label:'Orders',data:<?php echo json_encode($orders_count); ?>,borderColor:'#ff4d4d',backgroundColor:'rgba(255,77,77,0.2)'}
]
},
options:{responsive:true}
});

const dailyChart = new Chart(document.getElementById('dailyChart'),{
type:'bar',
data:{labels:<?php echo json_encode($days); ?>,datasets:[{label:'Revenue',data:<?php echo json_encode($day_revenue); ?>,backgroundColor:'#0077ff'}]},
options:{responsive:true}
});

const paymentChart = new Chart(document.getElementById('paymentChart'),{
type:'doughnut',
data:{labels:<?php echo json_encode($methods); ?>,datasets:[{data:<?php echo json_encode($method_count); ?>,backgroundColor:['#0077ff','#ff4d4d','#ffaa00','#00cc66','#ff00aa']}]},
options:{responsive:true}
});

// Table Search
function tableSearch(inputSelector, tableSelector){
const input=document.querySelector(inputSelector);
input.addEventListener('keyup',function(){
const filter=input.value.toLowerCase();
const rows=document.querySelectorAll(tableSelector+' tr');
rows.forEach((row,index)=>{if(index===0)return;row.style.display=row.textContent.toLowerCase().includes(filter)?'':'none';});
});
}
tableSearch('.latestSearch','.latestTable');
tableSearch('.topSearch','.topTable');
</script>
</body>
</html>
