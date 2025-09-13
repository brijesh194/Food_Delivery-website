<?php
session_start();
if(isset($_SESSION['login_success']) && $_SESSION['login_success'] === true){
    unset($_SESSION['login_success']); 
    $username = isset($_SESSION['user']) ? $_SESSION['user'] : "User"; 
    echo '
    <div id="login-overlay">
        <canvas id="particles"></canvas>
        <div class="logo-box">
            <div class="logo-glow">⚡</div>
            <div class="checkmark">&#10003;</div>
        </div>
        <h2 class="welcome">Welcome Back!</h2>
        <h3 class="username" id="typed-username"></h3>
    </div>
    <script>
        const text = "'.$username.'";
        let i = 0;
        function typeWriter() {
            if (i < text.length) {
                document.getElementById("typed-username").innerHTML += text.charAt(i);
                i++;
                setTimeout(typeWriter, 150); // typing speed
            }
        }
        setTimeout(typeWriter, 3500); // 3.5 sec baad start hoga (Welcome Back ke baad)
        
        setTimeout(() => {
            document.getElementById("login-overlay").style.display = "none";
        }, 10000);
    </script>
    ';
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Foodie — Order Food Online</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


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
*{box-sizing:border-box}
html,body{margin:0;padding:0;background:var(--bg);color:var(--text);font-family:Poppins,system-ui,-apple-system,Segoe UI,Roboto,Arial}
a{text-decoration:none;color:inherit}
img{display:block;max-width:100%}
button{cursor:pointer}
.container{max-width:1200px;margin:0 auto;padding:0 20px}

/* Custom stylish scrollbar */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: rgba(0,0,0,0.2);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(180deg, #ff6b6b, #f7b42c);
  border-radius: 10px;
}
/* Scrollbar hide karne ke liye */
/* ::-webkit-scrollbar {
  width: 0px;
  background: transparent; 
}

body {
  scrollbar-width: none; 
  -ms-overflow-style: none; 
}

body::-webkit-scrollbar {
  display: none; 
} */

/* ===== Navbar (Glass) ===== */
nav.nav{
  position:sticky;top:0;z-index:1000;
  backdrop-filter: blur(12px);
  background:linear-gradient(180deg, rgba(15,15,18,.7), rgba(15,15,18,.3));
  border-bottom:1px solid var(--glass);
}
.nav-row{display:flex;align-items:center;gap:16px; padding:14px 0;}
.brand{display:flex;align-items:center;gap:10px;font-weight:700;font-size:22px}
.brand i{color:var(--accent)}
.location{
  display:flex;align-items:center;gap:8px;
  padding:8px 12px;border:1px solid var(--glass);border-radius:12px;background:#121219;
}
.location select{background:#121219;border:none;color:var(--text);outline:none}
.search{
  flex:1;display:flex;align-items:center;gap:10px;
  padding:10px 14px;border:1px solid var(--glass);border-radius:14px;background:#121219;
}
.search input{flex:1;background:transparent;border:none;color:var(--text);outline:none}
.nav-links{display:flex;align-items:center;gap:14px}
.nav-links a,.nav-links button.minimal{
  padding:10px 12px;border-radius:12px;border:1px solid transparent;background:transparent;color:var(--text);
}
.nav-links a:hover{background:var(--glass)}
.cta{
  background:linear-gradient(135deg,var(--accent),var(--accent-2));
  border:none;color:#fff;padding:10px 16px;border-radius:12px;font-weight:600;
  box-shadow:0 6px 20px rgba(255,75,43,.2);
}
.profile{position:relative}
.avatar{
  width:38px;height:38px;border-radius:50%;display:grid;place-items:center;
  background:#1e1e24;border:1px solid var(--glass)
}
.dropdown{position:absolute;right:0;top:48px;background:#121219;border:1px solid var(--glass);border-radius:14px;min-width:160px;display:none}
.dropdown a{display:block;padding:10px 12px;border-bottom:1px solid var(--glass)}
.dropdown a:last-child{border-bottom:0}
.profile.open .dropdown{display:block}
.hamburger{display:none;font-size:22px;border:1px solid var(--glass);border-radius:10px;padding:8px; margin-left: 10px;background: transparent;color: white;}

/* ===== Mobile Drawer ===== */
.drawer{display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:999}
.drawer .panel{position:absolute;right: 8px;top:68px;height:100%;width:85%;max-width:360px;background:#121219;border-left:1px solid var(--glass);padding:18px;overflow:auto}
.drawer .panel .search{margin:10px 0}

/* ===== Hero ===== */
.hero{padding:50px 0 30px}
.hero-wrap{
  position:relative;overflow:hidden;border-radius:20px;border:1px solid var(--glass);
  background:linear-gradient(135deg,#16161c 0%,#121219 60%),url('https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?q=80&w=1600&auto=format&fit=crop') center/cover;
}
.hero-grid{display:grid;grid-template-columns:1.1fr .9fr;gap:20px;padding:42px}
.hero h1{font-size:40px;line-height:1.15;margin:0}
.hero p{color:var(--muted);margin:12px 0 20px}
.hero .big-search{display:flex;gap:10px;align-items:center}
.hero .big-search .search{padding:14px 16px;border-radius:16px}
.kpis{display:flex;gap:18px;margin-top:18px;flex-wrap:wrap}
.kpi{display:flex;gap:10px;align-items:center;background:#13131a;border:1px solid var(--glass);padding:10px 12px;border-radius:12px}
.hero-img{
  border-radius:16px;overflow:hidden;border:1px solid var(--glass);
  height:100%;min-height:280px;background:url('https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=1600&auto=format&fit=crop') center/cover;
}

/* ===== Featured Collections (banners) ===== */
.collections{padding:14px 0 6px}
.col-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.col{
  position:relative;border-radius:18px;overflow:hidden;border:1px solid var(--border);background:#15151a;
  transition:.35s transform, .35s box-shadow, .35s border-color;
}
.col:hover{transform:translateY(-6px);border-color:var(--accent);box-shadow:0 20px 40px rgba(255,75,43,.18)}
.col img{width:100%;height:180px;object-fit:cover;opacity:.92}
.col .label{position:absolute;left:14px;bottom:14px;background:rgba(0,0,0,.55);backdrop-filter:blur(6px);padding:8px 10px;border-radius:10px;border:1px solid var(--glass);font-weight:600}

/* ===== Offers Slider ===== */
.section-head{display:flex;justify-content:space-between;align-items:center;margin:26px 0 14px}
.controls button{border:1px solid var(--glass);background:#17171c;padding:8px 10px;border-radius:10px}
.slider{display:flex;gap:14px;overflow:hidden;scroll-behavior:smooth}
.offer{
  min-width:260px;background:#15151b;border:1px solid var(--border);border-radius:16px;overflow:hidden;
  transition:.3s;position:relative;
}
.offer:hover{transform:translateY(-4px);border-color:var(--accent);box-shadow:0 14px 30px rgba(254,116,79,.18)}
.offer img{height:130px;width:100%;object-fit:cover}
.badge{position:absolute;top:10px;left:10px;background:linear-gradient(135deg,var(--accent),var(--accent-2));color:#fff;font-weight:700;font-size:12px;padding:6px 9px;border-radius:999px}

/* ===== Trending Restaurants Grid ===== */
.grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
.card{
  background:#15151b;border:1px solid var(--border);border-radius:16px;overflow:hidden;transition:.3s;
}
.card:hover{transform:translateY(-4px);border-color:var(--accent);box-shadow:0 16px 36px rgba(255,75,43,.18)}
.card img{height:160px;width:100%;object-fit:cover}
.card .content{padding:12px}
.meta{display:flex;justify-content:space-between;align-items:center;margin-top:6px;color:var(--muted);font-size:13px}
.rating{display:inline-flex;gap:6px;align-items:center;background:#112416;color:#a7f3d0;border:1px solid #1f3d28;padding:4px 8px;border-radius:10px;font-weight:600}
.btn{display:inline-flex;gap:8px;align-items:center;padding:10px 12px;border-radius:12px;border:1px solid var(--muted);background:#1b1b22}
.btn:hover{
  border: 1px solid var(--accent) ;
}
.btn.btn-primary{border:none;background:linear-gradient(135deg,var(--accent),var(--accent-2));color:#fff}
.btn:hover{filter:brightness(1.05)}

/* ===== Categories Badges ===== */
.badges{display:flex;flex-wrap:wrap;gap:10px}
.badge-pill{padding:10px 14px;border-radius:999px;border:1px solid var(--glass);background:#13131a;color:#ddd;display:inline-flex;gap:8px;align-items:center}
.badge-pill i{color:var(--accent)}

/* ===== Why Choose Us ===== */
.usp{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
.usp .tile{background:#15151b;border:1px solid var(--border);border-radius:16px;padding:16px;transition:.3s}
.usp .tile:hover{transform:translateY(-4px);border-color:var(--accent);box-shadow:0 16px 36px rgba(255,75,43,.18)}
.usp .tile i{font-size:22px;color:var(--ok)}

/* ===== How It Works ===== */
.steps{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
.step{background:#15151b;border:1px solid var(--border);border-radius:16px;padding:16px;transition: .3s;}
.step:after{content:"";position:absolute;top:50%;right:-8px;width:16px;height:2px;background:var(--glass)}
.step:last-child:after{display:none}
.step:hover{transform:translateY(-4px);border-color:var(--accent);box-shadow:0 16px 36px rgba(255,75,43,.18)}

/* ===== Testimonials Slider ===== */
.t-slider{display:flex;gap:16px;overflow:hidden;scroll-behavior:smooth}
.t-card{min-width:320px;background:#15151b;border:1px solid var(--border);border-radius:16px;padding:16px;display:flex;gap:12px;transition: 0.3s;}
.t-card img{width:52px;height:52px;border-radius:50%;object-fit:cover;border:1px solid var(--glass)}
.stars{color:#fbbf24}
.t-card:hover{transform:translateY(-4px);border-color:var(--accent);box-shadow:0 16px 36px rgba(255,75,43,.18)}

/* ===== App Download ===== */
.app{display:grid;grid-template-columns:1fr 1fr;gap:18px;align-items:center}
.store{display:flex;gap:12px;flex-wrap:wrap}
.store .btn{padding:12px 14px}

/* ===== Newsletter ===== */
.news{display:grid;grid-template-columns:1.2fr .8fr;gap:16px;align-items:center;background:linear-gradient(135deg,#15151b,#121219);border:1px solid var(--glass);padding:18px;border-radius:18px; transition: .3s;}
.input{display:flex;gap:10px;align-items:center;border:1px solid var(--glass);border-radius:14px;background:#121219;padding:12px}
.input input{flex:1;background:transparent;border:none;color:var(--text);outline:none}
.news:hover{transform:translateY(-4px);border-color:var(--accent);box-shadow:0 16px 36px rgba(255,75,43,.18)}
.input:hover{border-color:var(--accent); transition: .5s;}
/* ===== Footer ===== */
footer{margin-top:40px;border-top:1px solid var(--glass);background:#0e0e11}
.f-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:16px;padding:28px 0;color:#c9cbd1}
footer a{color:#c9cbd1}
footer a:hover{color:#fff}

.btn_face {
    display: inline-flex;
    gap: 8px;
    align-items: center;
    padding: 10px 12px;
    border-radius: 12px;
    border: 1px solid var(--glass);
    background: #1b1b22;
}
.btn_face:hover{
  border: 1px solid #3582f6c5 ;
}
.btn_inst {
    display: inline-flex;
    gap: 8px;
    align-items: center;
    padding: 10px 12px;
    border-radius: 12px;
    border: 1px solid var(--glass);
    background: #1b1b22;
}
.btn_inst:hover{
  border: 1px solid #dc39a8ff ;
}
.btn_twi {
    display: inline-flex;
    gap: 8px;
    align-items: center;
    padding: 10px 12px;
    border-radius: 12px;
    border: 1px solid var(--glass);
    background: #1b1b22;
}
.btn_twi:hover{
  border: 1px solid var(--text) ;
}
.fa-brands_face, .fab{
  font-family: "Font Awesome 6 Brands";
  font-size:20px;
  color: #3582f6c5;
}
.fa-brands_inst, .fab{
  font-family: "Font Awesome 6 Brands";
  font-size:20px;
  color: #dc39a8ff;
}
.fa-brands_twi, .fab{
  font-family: "Font Awesome 6 Brands";
  font-size:20px;
  /* color: #3582f6c5; */
}
/* ===== Back to top ===== */
#toTop{position:fixed;right:18px;bottom:18px;width:44px;height:44px;border-radius:50%;display:grid;place-items:center;background:linear-gradient(135deg,var(--accent),var(--accent-2));border:none;color:#fff;box-shadow:0 10px 24px rgba(254,116,79,.3);opacity:0;visibility:hidden;transition:.25s}
#toTop.show{opacity:1;visibility:visible}

/* ===== Reveal animation ===== */
.reveal{opacity:0;transform:translateY(18px);transition:.6s ease}
.reveal.show{opacity:1;transform:none}

/* ===== Responsive ===== */
@media (max-width:800px){
  .nav-links{display:none}
  .hamburger{display:inline-flex}
  .hero-grid{grid-template-columns:1fr; gap:20px;}
  .grid{grid-template-columns:repeat(2,1fr);gap:14px;}
  .usp{grid-template-columns:repeat(2,1fr);}
  .steps{grid-template-columns:repeat(2,1fr);}
  .col-grid{grid-template-columns:repeat(2,1fr);}
  .app{grid-template-columns:1fr;}
  .f-grid{grid-template-columns:1fr 1fr;}
  .hero .kpis{flex-direction:column; gap:12px;}
  .big-search{flex-direction:column;}
  .big-search .btn{width:100%;}
}

@media (max-width:520px){
  .grid{grid-template-columns:1fr;}
  .search{display: none;}
  .col-grid{grid-template-columns:1fr;}
  .usp{grid-template-columns:1fr;}
  .steps{grid-template-columns:1fr;}
  .f-grid{grid-template-columns:1fr;}
  .badges{flex-direction:column;}
  .hero h1{font-size:28px;line-height:1.3;}
  .hero p{font-size:14px;}
  .hero .search input{font-size:14px;}
  .hero .kpi{font-size:12px;padding:8px 10px;}
  .t-card{min-width:250px;}
  .app img{width:100%;height:auto;}
  .news{grid-template-columns:1fr;}
  .news .input{flex-direction:column;gap:8px;}
  .news button{width:100%;}
}
.add-to-cart i {
  transition: transform 0.3s ease, color 0.3s ease;
}
.add-to-cart i:hover {
  transform: scale(1.2);
  color: #ff4b2b;
}
.toast {
  position: fixed;
  top: 80px;
  right: 30px;
  background:linear-gradient(135deg,var(--accent),var(--accent-2));
  color: #fff;
  padding: 14px 20px;
  border-radius: 10px;
  font-weight: 500;
  font-family: Poppins, sans-serif;
  box-shadow: 0 6px 20px rgba(0,0,0,0.3);
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.5s, transform 0.5s;
}

.toast.show {
  opacity: 1;
  transform: translateY(0);
  pointer-events: auto;
}

/* login animation */
#login-overlay {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: radial-gradient(circle at center, #0f0f0f, #000000);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  z-index: 99999;
  overflow: hidden;
}

/* Particle Canvas */
#particles {
  position: absolute;
  top:0; left:0;
  width:100%; height:100%;
  z-index:1;
}

/* Logo Glow Box */
.logo-box {
  position: relative;
  z-index: 2;
  display: flex;
  justify-content: center;
  align-items: center;
}

.logo-glow {
  font-size: 80px;
  color: #ffcc00;
  text-shadow: 0 0 20px #ff8800, 0 0 40px #ff4400, 0 0 80px #ff2200;
  animation: pulse 2s infinite;
}

.checkmark {
  position: absolute;
  font-size: 100px;
  color: #00ff88;
  opacity: 0;
  animation: checkIn 2s ease forwards 2s;
}

@keyframes pulse {
  0% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.2); opacity: 0.8; }
  100% { transform: scale(1); opacity: 1; }
}

@keyframes checkIn {
  0% { transform: scale(0); opacity: 0; }
  100% { transform: scale(1); opacity: 1; }
}
/* show user name */
.username {
  margin-top: 10px;
  font-size: 26px;
  font-weight: 600;
  z-index: 2;
  color: #ffcc00;
  text-shadow: 0 0 15px #ffaa00, 0 0 30px #ff6600;
  display: inline-block;
  border-right: 2px solid #ffcc00; /* cursor */
  white-space: nowrap;
  overflow: hidden;
  animation: blink 0.7s infinite;
}

@keyframes blink {
  50% { border-color: transparent; }
}

/* Welcome Text */
.welcome {
  margin-top: 20px;
  font-size: 32px;
  font-weight: bold;
  z-index: 2;
  color: #fff;
  text-shadow: 0 0 15px #00ffcc, 0 0 30px #00ffaa;
  animation: fadeUp 2s ease forwards 3s;
  opacity: 0;
}

@keyframes fadeUp {
  from { transform: translateY(20px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}


</style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="nav">
  <div class="container nav-row">
    <button class="hamburger" id="openDrawer"><i class="fa-solid fa-bars"></i></button>
    <a class="brand" href="index.php"><i class="fa-solid fa-pizza-slice"></i> Foodie</a>

    <div class="location">
      <i class="fa-solid fa-location-dot"></i>
      <select aria-label="Select location">
        <option value="all">All Locations</option>
        <option>Barabanki</option>
        <option>jahangirabad</option>
        <option>Dobha</option>
        <option>Awas vikas clony</option>
        <option>sutmile</option>
      </select>
    </div>

    <div class="search" role="search">
      <i class="fa-solid fa-magnifying-glass"></i>
      <input type="text" placeholder="Search for restaurants, cuisines, dishes…" />
    </div>

    <div class="nav-links">
      <a href="#offers">Offers</a>
      <a href="#trending">Restaurants</a>
      <a href="Menu Page.php">Menu</a>
      <a href="#app">Get App</a>
      <a href="cart.php" class="cart-link" style="position:relative;">
  <i class="fa-solid fa-cart-shopping"></i>
  <span id="cart-count" style="position:absolute;top:-8px;right:-12px;background:var(--accent);color:#fff;font-size:12px;padding:2px 6px;border-radius:999px;">0</span>
</a>

      <?php if(isset($_SESSION['user'])): ?>
        <div class="profile" id="profile">
          <div class="avatar"><i class="fa-solid fa-user"></i></div>
          <div class="dropdown">
            <a href="#"><i class="fa-regular fa-user"></i> Profile</a>
            <a href="#"><i class="fa-solid fa-bag-shopping"></i> Orders</a>
            <a href="auth.php?logout=1"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
          </div>
        </div>
      <?php else: ?>
        <a class="cta" href="auth.php">Login / Register</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<!-- Mobile Drawer -->
<div class="drawer" id="drawer">
  <div class="panel">
    <div style="display:flex;justify-content:space-between;align-items:center;">
      <div class="brand"><i class="fa-solid fa-pizza-slice"></i> Foodie</div>
      <button class="hamburger" id="closeDrawer"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="search" style="margin-top:14px">
      <i class="fa-solid fa-magnifying-glass"></i>
      <input type="text" placeholder="Search for restaurants, cuisines, dishes…" />
    </div>
    <div style="display:flex;flex-direction:column;gap:8px;margin-top:12px">
      <a href="#offers">Offers</a>
      <a href="#trending">Restaurants</a>
      <a href="cart.php" class="cart-link" style="position:relative;">
  <i class="fa-solid fa-cart-shopping"></i>
  <span id="cart-count" style="position:absolute;top:-8px;right:-12px;background:var(--accent);color:#fff;font-size:12px;padding:2px 6px;border-radius:999px;">0</span>
</a>

      <a href="#app">Get App</a>
      
      <?php if(isset($_SESSION['user'])): ?>
        <a href="#"><i class="fa-regular fa-user"></i> Profile</a>
        <a href="#"><i class="fa-solid fa-bag-shopping"></i> Orders</a>
        <a href="auth.php?logout=1"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
      <?php else: ?>
        <a class="cta" href="auth.php" style="text-align:center;margin-top:8px">Login / Register</a>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- ===== HERO ===== -->
<section class="hero">
  <div class="container hero-wrap reveal">
    <div class="hero-grid">
      <div>
        <h1>Craving something delicious?<br/>We deliver it fast.</h1>
        <p>Discover top restaurants near you. Exclusive deals, safe payments, on-time delivery.</p>
        <div class="big-search">
          <div class="search" style="flex:1">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Try “Margherita Pizza” or “Biryani”" />
          </div>
          <button class="btn btn-primary"><i class="fa-solid fa-bolt"></i> Search</button>
        </div>
        <div class="kpis">
          <div class="kpi"><i class="fa-solid fa-clock"></i> 30–40 min avg</div>
          <div class="kpi"><i class="fa-solid fa-shield-heart"></i> Safe & Hygienic</div>
          <div class="kpi"><i class="fa-solid fa-tag"></i> Daily Offers</div>
        </div>
      </div>
      <div class="hero-img" aria-hidden="true"></div>
    </div>
  </div>
</section>

<div id="toast" class="toast"></div>

<!-- ===== FEATURED COLLECTIONS ===== -->
<section class="collections container reveal">
  <div class="section-head">
    <h2>Featured Collections</h2>
  </div>
  <div class="col-grid">
    <a class="col" href="#">
      <img src="https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=1400&auto=format&fit=crop" alt="">
      <span class="label">Late Night Delivery</span>
    </a>
    <a class="col" href="#">
      <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=1400&auto=format&fit=crop" alt="">
      <span class="label">Healthy Choices</span>
    </a>
    <a class="col" href="#">
      <img src="https://images.unsplash.com/photo-1546069901-eacef0df6022?q=80&w=1400&auto=format&fit=crop" alt="">
      <span class="label">Best of 2025</span>
    </a>
  </div>
</section>

<!-- ===== OFFERS SLIDER ===== -->
<section id="offers" class="container reveal" style="margin-top:26px">
  <div class="section-head">
    <h2>Top Offers</h2>
    <div class="controls">
      <button id="offerPrev"><i class="fa-solid fa-chevron-left"></i></button>
      <button id="offerNext"><i class="fa-solid fa-chevron-right"></i></button>
    </div>
  </div>
  <div class="slider" id="offerSlider" aria-label="Offers">
    <!-- cards -->
    <a class="offer" href="#">
      <span class="badge">FLAT 50% OFF</span>
      <img src="https://images.unsplash.com/photo-1542281286-9e0a16bb7366?q=80&w=1200&auto=format&fit=crop" alt="">
      <div class="content" style="padding:12px">
        <h3>Pizza Fiesta</h3>
        <div class="meta"><span>Use code: PIZZA50</span><span class="warn">Today</span></div>
      </div>
    </a>
    <a class="offer" href="#" data-location="Barabanki">
      <span class="badge">BUY 1 GET 1</span>
      <img src="https://images.unsplash.com/photo-1550547660-d9450f859349?q=80&w=1200&auto=format&fit=crop" alt="">
      <div class="content" style="padding:12px">
        <h3>Burger Mania</h3>
        <div class="meta"><span>BK outlets</span><span>All day</span></div>
      </div>
    </a>
    <a class="offer" href="#" data-location="jahangirabad">
      <span class="badge">30% CASHBACK</span>
      <img src="https://images.unsplash.com/photo-1568051243853-8ea9b9414a83?q=80&w=1200&auto=format&fit=crop" alt="">
      <div class="content" style="padding:12px">
        <h3>Sushi Lovers</h3>
        <div class="meta"><span>Wallet only</span><span>Ends soon</span></div>
      </div>
    </a>
    <a class="offer" href="#" data-location="dobha">
      <span class="badge">FREE DESSERT</span>
      <img src="https://images.unsplash.com/photo-1499636136210-6f4ee915583e?q=80&w=1200&auto=format&fit=crop" alt="">
      <div class="content" style="padding:12px">
        <h3>Date Night</h3>
        <div class="meta"><span>On ₹699+</span><span>Fri–Sun</span></div>
      </div>
    </a>

    <a class="offer" href="#" data-location="barabanki">
      <span class="badge">FLAT 50% OFF</span>
      <img src="https://images.unsplash.com/photo-1542281286-9e0a16bb7366?q=80&w=1200&auto=format&fit=crop" alt="">
      <div class="content" style="padding:12px">
        <h3>Pizza Fiesta</h3>
        <div class="meta"><span>Use code: PIZZA50</span><span class="warn">Today</span></div>
      </div>
    </a>
    <a class="offer" href="#" data-location="barabanki">
      <span class="badge">BUY 1 GET 1</span>
      <img src="https://images.unsplash.com/photo-1550547660-d9450f859349?q=80&w=1200&auto=format&fit=crop" alt="">
      <div class="content" style="padding:12px">
        <h3>Burger Mania</h3>
        <div class="meta"><span>BK outlets</span><span>All day</span></div>
      </div>
    </a>
    <a class="offer" href="#" data-location="barabanki">
      <span class="badge">30% CASHBACK</span>
      <img src="https://images.unsplash.com/photo-1568051243853-8ea9b9414a83?q=80&w=1200&auto=format&fit=crop" alt="">
      <div class="content" style="padding:12px">
        <h3>Sushi Lovers</h3>
        <div class="meta"><span>Wallet only</span><span>Ends soon</span></div>
      </div>
    </a>
    <a class="offer" href="#" data-location="barabanki">
      <span class="badge">FREE DESSERT</span>
      <img src="https://images.unsplash.com/photo-1499636136210-6f4ee915583e?q=80&w=1200&auto=format&fit=crop" alt="">
      <div class="content" style="padding:12px">
        <h3>Date Night</h3>
        <div class="meta"><span>On ₹699+</span><span>Fri–Sun</span></div>
      </div>
    </a>
  </div>
</section>

<!-- ===== TRENDING RESTAURANTS ===== -->
<section id="trending" class="container reveal" style="margin-top:28px">
  <div class="section-head">
    <h2>Popular Near You</h2>
    <a class="btn" href="#"><i class="fa-solid fa-filter"></i> Filters</a>
  </div>
  <div class="grid">
    <!-- card -->
    <article class="card" data-location="jahangirabad">
      <img src="https://images.unsplash.com/photo-1600891964599-f61ba0e24092?q=80&w=1400&auto=format&fit=crop" alt="">
      <div class="content">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <h3>Cheese & Crust</h3>
          <span class="rating"><i class="fa-solid fa-star"></i> 4.6</span>
        </div>
        <div class="meta"><span>Pizza, Italian</span><span>30–40 min</span></div>
        <div style="margin-top:10px;display:flex;gap:10px">
          <button class="btn btn-primary"><i class="fa-solid fa-bag-shopping"></i> <a href="checkout.php">Order Now</a></button>
          <button class="btn add-to-cart"
  data-id="1"
  data-name="Cheese & Crust"
  data-price="299"
  data-img="https://images.unsplash.com/photo-1600891964599-f61ba0e24092?q=80&w=1400&auto=format&fit=crop">
  <i class="fa-regular fa-heart"></i>
</button>
        </div>
      </div>
    </article>

    <article class="card" data-location="jahangirabad">
      <img src="https://images.unsplash.com/photo-1562967916-eb82221dfb29?q=80&w=1400&auto=format&fit=crop" alt="">
      <div class="content">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <h3>Burger Vault</h3>
          <span class="rating"><i class="fa-solid fa-star"></i> 4.4</span>
        </div>
        <div class="meta"><span>Burgers, Fast Food</span><span>25–35 min</span></div>
        <div style="margin-top:10px;display:flex;gap:10px">
          <button class="btn btn-primary"><i class="fa-solid fa-bag-shopping"></i> Order Now</button>
          <button class="btn add-to-cart"
          data-id="2"
          data-name="Burger Vault"
          data-price="199"
          data-img="https://images.unsplash.com/photo-1562967916-eb82221dfb29?q=80&w=1400&auto=format&fit=crop">
  <i class="fa-regular fa-heart"></i>
          </button>
        </div>
      </div>
    </article>

    <article class="card" data-location="jahangirabad">
      <img src="https://images.unsplash.com/photo-1551218808-94e220e084d2?q=80&w=1400&auto=format&fit=crop" alt="">
      <div class="content">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <h3>Sushi World</h3>
          <span class="rating"><i class="fa-solid fa-star"></i> 4.8</span>
        </div>
        <div class="meta"><span>Japanese, Sushi</span><span>40–50 min</span></div>
        <div style="margin-top:10px;display:flex;gap:10px">
          <button class="btn btn-primary"><i class="fa-solid fa-bag-shopping"></i> Order Now</button>
          <button class="btn add-to-cart"
  data-id="3"
  data-name="Sushi World"
  data-price="299"
  data-img="https://images.unsplash.com/photo-1600891964599-f61ba0e24092?q=80&w=1400&auto=format&fit=crop">
  <i class="fa-regular fa-heart"></i>
</button>
        </div>
      </div>
    </article>

    <article class="card" data-location="jahangirabad">
      <img src="https://images.unsplash.com/photo-1550547660-d9450f859349?q=80&w=1400&auto=format&fit=crop" alt="">
      <div class="content">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <h3>Stacked & Saucy</h3>
          <span class="rating"><i class="fa-solid fa-star"></i> 4.5</span>
        </div>
        <div class="meta"><span>Sandwiches, Subs</span><span>20–30 min</span></div>
        <div style="margin-top:10px;display:flex;gap:10px">
          <button class="btn btn-primary"><i class="fa-solid fa-bag-shopping"></i> Order Now</button>
          <button class="btn add-to-cart"
  data-id="4"
  data-name="Stacked & Saucy"
  data-price="299"
  data-img="https://images.unsplash.com/photo-1600891964599-f61ba0e24092?q=80&w=1400&auto=format&fit=crop">
  <i class="fa-regular fa-heart"></i>
</button>
        </div>
      </div>
    </article>


    <article class="card" data-location="jahangirabad">
      <img src="https://images.unsplash.com/photo-1550547660-d9450f859349?q=80&w=1400&auto=format&fit=crop" alt="">
      <div class="content">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <h3>Stacked & Saucy</h3>
          <span class="rating"><i class="fa-solid fa-star"></i> 4.5</span>
        </div>
        <div class="meta"><span>Sandwiches, Subs</span><span>20–30 min</span></div>
        <div style="margin-top:10px;display:flex;gap:10px">
          <button class="btn btn-primary"><i class="fa-solid fa-bag-shopping"></i> Order Now</button>
          <button class="btn add-to-cart"
  data-id="5"
  data-name="Stacked & Saucy"
  data-price="299"
  data-img="https://images.unsplash.com/photo-1600891964599-f61ba0e24092?q=80&w=1400&auto=format&fit=crop">
  <i class="fa-regular fa-heart"></i>
</button>
        </div>
      </div>
    </article>


    <article class="card">
      <img src="https://images.unsplash.com/photo-1550547660-d9450f859349?q=80&w=1400&auto=format&fit=crop" alt="">
      <div class="content">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <h3>Stacked & Saucy</h3>
          <span class="rating"><i class="fa-solid fa-star"></i> 4.5</span>
        </div>
        <div class="meta"><span>Sandwiches, Subs</span><span>20–30 min</span></div>
        <div style="margin-top:10px;display:flex;gap:10px">
          <button class="btn btn-primary"><i class="fa-solid fa-bag-shopping"></i> Order Now</button>
          <button class="btn add-to-cart"
  data-id="1"
  data-name="Cheese & Crust"
  data-price="299"
  data-img="https://images.unsplash.com/photo-1600891964599-f61ba0e24092?q=80&w=1400&auto=format&fit=crop">
  <i class="fa-regular fa-heart"></i>
</button>
        </div>
      </div>
    </article>

    <article class="card" data-location="New delhi">
      <img src="https://images.unsplash.com/photo-1550547660-d9450f859349?q=80&w=1400&auto=format&fit=crop" alt="">
      <div class="content">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <h3>Stacked & Saucy</h3>
          <span class="rating"><i class="fa-solid fa-star"></i> 4.5</span>
        </div>
        <div class="meta"><span>Sandwiches, Subs</span><span>20–30 min</span></div>
        <div style="margin-top:10px;display:flex;gap:10px">
          <button class="btn btn-primary"><i class="fa-solid fa-bag-shopping"></i> Order Now</button>
          <button class="btn add-to-cart"
  data-id="1"
  data-name="Cheese & Crust"
  data-price="299"
  data-img="https://images.unsplash.com/photo-1600891964599-f61ba0e24092?q=80&w=1400&auto=format&fit=crop">
  <i class="fa-regular fa-heart"></i>
</button>
        </div>
      </div>
    </article>

    <article class="card">
      <img src="https://images.unsplash.com/photo-1550547660-d9450f859349?q=80&w=1400&auto=format&fit=crop" alt="">
      <div class="content">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <h3>Stacked & Saucy</h3>
          <span class="rating"><i class="fa-solid fa-star"></i> 4.5</span>
        </div>
        <div class="meta"><span>Sandwiches, Subs</span><span>20–30 min</span></div>
        <div style="margin-top:10px;display:flex;gap:10px">
          <button class="btn btn-primary"><i class="fa-solid fa-bag-shopping"></i> Order Now</button>
          <button class="btn add-to-cart"
  data-id="1"
  data-name="Cheese & Crust"
  data-price="299"
  data-img="https://images.unsplash.com/photo-1600891964599-f61ba0e24092?q=80&w=1400&auto=format&fit=crop">
  <i class="fa-regular fa-heart"></i>
</button>
        </div>
      </div>
    </article>
  </div>
</section>

<!-- ===== CATEGORIES BADGES ===== -->
<section class="container reveal" style="margin-top:24px">
  <div class="section-head">
    <h2>Eat What You Love</h2>
  </div>
  <div class="badges">
    <span class="badge-pill"><i class="fa-solid fa-pizza-slice"></i> Pizza</span>
    <span class="badge-pill"><i class="fa-solid fa-burger"></i> Burgers</span>
    <span class="badge-pill"><i class="fa-solid fa-bowl-rice"></i> Biryani</span>
    <span class="badge-pill"><i class="fa-solid fa-ice-cream"></i> Desserts</span>
    <span class="badge-pill"><i class="fa-solid fa-mug-hot"></i> Beverages</span>
    <span class="badge-pill"><i class="fa-solid fa-fish"></i> Sushi</span>
    <span class="badge-pill"><i class="fa-solid fa-bacon"></i> Barbecue</span>
    <span class="badge-pill"><i class="fa-solid fa-bowl-food"></i> North Indian</span>
  </div>
</section>

<!-- ===== WHY CHOOSE US ===== -->
<section class="container reveal" style="margin-top:26px">
  <div class="section-head"><h2>Why Choose Foodie</h2></div>
  <div class="usp">
    <div class="tile"><i class="fa-solid fa-truck-fast"></i> <h3>Superfast Delivery</h3><p class="muted">Average 30–40 minutes in metro cities.</p></div>
    <div class="tile"><i class="fa-solid fa-shield-heart"></i> <h3>Hygiene Assured</h3><p class="muted">Partner kitchens follow safety standards.</p></div>
    <div class="tile"><i class="fa-solid fa-badge-percent"></i> <h3>Best Offers</h3><p class="muted">Exclusive bank & wallet deals daily.</p></div>
    <div class="tile"><i class="fa-solid fa-headset"></i> <h3>24×7 Support</h3><p class="muted">We’re here whenever you’re hungry.</p></div>
  </div>
</section>

<!-- ===== HOW IT WORKS ===== -->
<section class="container reveal" style="margin-top:26px">
  <div class="section-head"><h2>How It Works</h2></div>
  <div class="steps">
    <div class="step"><h3>1. Set Location</h3><p class="muted">Enable location or choose your city.</p></div>
    <div class="step"><h3>2. Browse Menus</h3><p class="muted">Find your cravings from top spots.</p></div>
    <div class="step"><h3>3. Pay Securely</h3><p class="muted">UPI, Cards, Wallets & COD supported.</p></div>
    <div class="step"><h3>4. Track Live</h3><p class="muted">Real-time order tracking till delivery.</p></div>
  </div>
</section>

<!-- ===== TESTIMONIALS SLIDER ===== -->
<section class="container reveal" style="margin-top:26px">
  <div class="section-head">
    <h2>What Customers Say</h2>
    <div class="controls">
      <button id="tPrev"><i class="fa-solid fa-chevron-left"></i></button>
      <button id="tNext"><i class="fa-solid fa-chevron-right"></i></button>
    </div>
  </div>
  <div class="t-slider" id="tSlider" aria-label="Testimonials">
    <div class="t-card">
      <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=300&q=80&auto=format&fit=crop" alt="">
      <div>
        <h3>Riya Sharma</h3>
        <div class="stars">★★★★★</div>
        <p class="muted">“Hot food, on time, great discounts. My go-to app!”</p>
      </div>
    </div>
    <div class="t-card">
      <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=300&q=80&auto=format&fit=crop" alt="">
      <div>
        <h3>Aman Verma</h3>
        <div class="stars">★★★★★</div>
        <p class="muted">“Love the variety. Tracking is super accurate.”</p>
      </div>
    </div>
    <div class="t-card">
      <img src="https://images.unsplash.com/photo-1547425260-76bcadfb4f2c?w=300&q=80&auto=format&fit=crop" alt="">
      <div>
        <h3>Neha Kapoor</h3>
        <div class="stars">★★★★☆</div>
        <p class="muted">“Great late-night delivery options in my area.”</p>
      </div>
    </div>
  </div>
</section>

<!-- ===== APP DOWNLOAD ===== -->
<section id="app" class="container reveal" style="margin-top:30px">
  <div class="app">
    <div>
      <h2>Get the Foodie App</h2>
      <p class="muted">Faster ordering, real-time tracking & exclusive app-only offers.</p>
      <div class="store">
        <a class="btn btn-primary" href="#"><i class="fa-brands fa-google-play"></i> Play Store</a>
        <a class="btn btn-primary" href="#"><i class="fa-brands fa-apple"></i> App Store</a>
      </div>
    </div>
    <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?q=80&w=1400&auto=format&fit=crop" alt="App preview" style="border-radius:16px;border:1px solid var(--glass)">
  </div>
</section>

<!-- ===== NEWSLETTER ===== -->
<section class="container reveal" style="margin-top:30px">
  <div class="news">
    <div>
      <h2>Don’t miss hot deals</h2>
      <p class="muted">Subscribe to get the latest offers & new launches in your inbox.</p>
    </div>
    <form class="input" onsubmit="event.preventDefault(); alert('Subscribed! 🎉');">
      <i class="fa-regular fa-envelope"></i>
      <input type="email" placeholder="Enter your email" required>
      <button class="btn btn-primary" type="submit">Subscribe</button>
    </form>
  </div>
</section>

<!-- ===== FOOTER ===== -->
<footer>
  <div class="container f-grid">
    <div>
      <div class="brand"><i class="fa-solid fa-pizza-slice"></i> Foodie</div>
      <p class="muted" style="margin-top:8px">Order from the best restaurants near you. Fast delivery, secure payments, great offers.</p>
      <p>Customers Care: +91 926-098-9080<br><pre style="font-size: 16px; font-family: 'Poppins';">Alternet Number: +91 800-520-3889</pre></p>
    </div>
    <div>
      <h4>Company</h4>
      <a href="#">About</a><br/>
      <a href="#">Careers</a><br/>
      <a href="#">Blog</a>
    </div>
    <div>
      <h4>Support</h4>
      <a href="#">Help Center</a><br/>
      <a href="#">Partner with us</a><br/>
      <a href="#">FAQs</a>
    </div>
    <div>
      <h4>Legal</h4>
      <a href="#">Terms</a><br/>
      <a href="#">Privacy</a><br/>
      <a href="#">Refunds</a>
    </div>
  </div>
  <div class="container" style="border-top:1px solid var(--glass);padding:14px 0;display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap">
    <span class="muted">© 2025 Foodie. All rights reserved.</span>
    <div style="display:flex;gap:10px">
      <a href="#" class="btn_face"><i class="fa-brands_face fa-facebook"></i></a>
      <a href="#" class="btn_inst"><i class="fa-brands_inst fa-instagram"></i></a>
      <a href="#" class="btn_twi"><i class="fa-brands_twi fa-x-twitter"></i></a>
    </div>
  </div>
</footer>

<button id="toTop" aria-label="Back to top"><i class="fa-solid fa-arrow-up"></i></button>

<!-- login animation  -->
<script>
const canvas = document.getElementById("particles");
const ctx = canvas.getContext("2d");
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

let particles = [];

for(let i=0; i<120; i++){
  particles.push({
    x: Math.random()*canvas.width,
    y: Math.random()*canvas.height,
    r: Math.random()*3+1,
    dx: (Math.random()-0.5)*2,
    dy: (Math.random()-0.5)*2,
    color: "hsl(" + Math.random()*360 + ", 100%, 50%)"
  });
}

function drawParticles(){
  ctx.clearRect(0,0,canvas.width,canvas.height);
  particles.forEach(p=>{
    ctx.beginPath();
    ctx.arc(p.x, p.y, p.r, 0, Math.PI*2);
    ctx.fillStyle = p.color;
    ctx.fill();
    p.x += p.dx;
    p.y += p.dy;

    if(p.x<0 || p.x>canvas.width) p.dx*=-1;
    if(p.y<0 || p.y>canvas.height) p.dy*=-1;
  });
}

setInterval(drawParticles, 30);
</script>
<!-- close login animation -->

<script>
// ========== MOBILE DRAWER ==========
const drawer = document.getElementById("drawer");
document.getElementById("openDrawer").onclick = () => drawer.style.display = "block";
document.getElementById("closeDrawer").onclick = () => drawer.style.display = "none";
drawer.onclick = e => { if(e.target === drawer) drawer.style.display = "none"; };

// ========== PROFILE DROPDOWN ==========
const profile = document.getElementById("profile");
if(profile){
  profile.addEventListener("click", ()=> profile.classList.toggle("open"));
}

// ========== SCROLL REVEAL ==========
const reveals = document.querySelectorAll(".reveal");
window.addEventListener("scroll",()=>{
  reveals.forEach(el=>{
    const rect = el.getBoundingClientRect();
    if(rect.top < window.innerHeight - 60) el.classList.add("show");
  });
});

// ========== BACK TO TOP ==========
const toTop = document.getElementById("toTop");
window.addEventListener("scroll",()=>{
  if(window.scrollY>300) toTop.classList.add("show"); else toTop.classList.remove("show");
});
if(toTop) toTop.onclick = ()=> window.scrollTo({top:0,behavior:"smooth"});

// ========== OFFERS SLIDER ==========
const slider = document.getElementById("offerSlider");
document.getElementById("offerPrev").onclick = ()=> slider.scrollBy({left:-280,behavior:"smooth"});
document.getElementById("offerNext").onclick = ()=> slider.scrollBy({left:280,behavior:"smooth"});

// ========== LIVE SEARCH WITH LOCATION ==========
function setupLiveSearchWithLocation(){
  const searchInputs = document.querySelectorAll(".search input"); // all search bars
  const locationSelect = document.querySelector(".location select"); // navbar location dropdown
  const cards = document.querySelectorAll(".card, .offer, .col"); // items to filter

  function filterCards(){
    const query = document.querySelector(".search input").value.toLowerCase();
    const selectedLocation = locationSelect.value.toLowerCase();

    cards.forEach(card=>{
      const text = card.innerText.toLowerCase();
      const cardLocation = card.getAttribute("data-location") 
                          ? card.getAttribute("data-location").toLowerCase() 
                          : "";

      // condition: matches search + matches location (or All)
      if(
        (text.includes(query)) && 
        (selectedLocation === "all" || cardLocation === selectedLocation)
      ){
        card.style.display = "";
        card.style.opacity = "1";
      } else {
        card.style.display = "none";
      }
    });
  }

  // search input event
  searchInputs.forEach(input=>{
    input.addEventListener("keyup", filterCards);
  });

  // location change event
  locationSelect.addEventListener("change", filterCards);
}
setupLiveSearchWithLocation();
</script>
<script>
  function showToast(message) {
  const toast = document.getElementById('toast');
  toast.innerText = message;
  toast.classList.add('show');

  setTimeout(() => {
    toast.classList.remove('show');
  }, 8000); // toast 2 sec dikhe
}

</script>

<script>
let cart = JSON.parse(localStorage.getItem("cart")) || [];

function updateCartCount() {
    <?php if(isset($_SESSION['user'])): ?>
        const cart = JSON.parse(localStorage.getItem("cart")) || [];
        document.getElementById("cart-count").innerText = cart.length;
    <?php else: ?>
        document.getElementById("cart-count").innerText = 0;
    <?php endif; ?>
}

document.querySelectorAll(".add-to-cart").forEach(btn => {
  btn.addEventListener("click", () => {

    // ✅ Ajax ya fetch request ke liye server se user login check karenge
    fetch('check_login.php')
      .then(res => res.json())
      .then(data => {
        if(!data.loggedIn) {
          showToast('Please login first!'); // toast dikhega
          setTimeout(() => {
            window.location.href = 'auth.php'; // thoda delay ke baad redirect
          }, 8000); // 2 sec delay
        } else {
          // user logged in hai → normal add-to-cart logic
          addToCart(btn);
        }
      });


    const id = btn.getAttribute("data-id");
    const name = btn.getAttribute("data-name");
    const price = btn.getAttribute("data-price");
    const img = btn.getAttribute("data-img");

    const product = { id, name, price, img };

    if (!cart.some(item => item.id === id)) {
      cart.push(product);
      localStorage.setItem("cart", JSON.stringify(cart));
      updateCartCount();

      // ✅ Button update → Heart Icon filled
      btn.innerHTML = `<i class="fa-solid fa-heart"></i>`;
      btn.style.background = "transparent";  // remove button background
      btn.style.color = "red";               // red heart
      btn.style.fontSize = "20px";
      btn.style.cursor = "default";
      btn.disabled = true;
    }
  });
});

updateCartCount();
</script>

</body>
</html>
