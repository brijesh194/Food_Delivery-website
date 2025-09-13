<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Foodie - Online Food Ordering</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* ===== Global ===== */
* { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
body { background:#121212; color:#f5f5f5; }
a { text-decoration:none; color:inherit; }

/* ===== Navbar ===== */
nav { display:flex; justify-content:space-between; align-items:center; padding:15px 30px; background:#1e1e1e; position:sticky; top:0; z-index:1000; }
.logo { font-size:28px; font-weight:600; color:#ff4b2b; }
.nav-links { display:flex; gap:20px; align-items:center; }
.nav-links a { padding:8px 12px; transition:0.3s; }
.profile {
      position:relative;
      cursor:pointer;
      display:inline-block;
    }
    .dropdown {
      display:none;
      position:absolute;
      right:0;
      background:#222;
      padding:10px;
      border-radius:8px;
      min-width:100px;
    }
    .dropdown a {
      display:block;
      padding:8px;
      color:#fff;
      text-decoration:none;
      border-radius:6px;
    }
    .dropdown a:hover {
      background:#ff4b2b;
    }
    .profile:hover .dropdown {
      display:block;
    }
.nav-links a:hover { background:#ff4b2b; border-radius:5px; color:#fff; }
.search-bar { position:relative; }
.search-bar input { padding:7px 10px 7px 30px; border-radius:5px; border:none; outline:none; width:250px; background:#2a2a2a; color:#fff; }
.search-bar i { position:absolute; top:50%; left:10px; transform:translateY(-50%); color:#bbb; }
.hamburger { display:none; font-size:28px; cursor:pointer; color:#fff; }

/* ===== Hero Section ===== */
.hero { display:flex; justify-content:space-between; align-items:center; padding:50px 30px; background:url('https://images.unsplash.com/photo-1600891964599-f61ba0e24092?crop=entropy&cs=tinysrgb&fit=max&fm=1080') center/cover no-repeat; border-radius:10px; margin:20px 30px; min-height:350px; }
.hero-text { max-width:50%; }
.hero-text h1 { font-size:40px; margin-bottom:15px; }
.hero-text p { font-size:16px; margin-bottom:20px; color:#ddd; }
.hero-text button { padding:10px 20px; font-size:16px; border:none; background:#ff4b2b; color:#fff; border-radius:5px; cursor:pointer; transition:0.3s; }
.hero-text button:hover { background:#ff6b4b; }

/* ===== Section Titles ===== */
section { padding:40px 30px; }
section h2 { font-size:24px; margin-bottom:20px; color:#ff4b2b; }
.view-toggle { display:inline-block; margin-top:15px; padding:6px 12px; background:#ff4b2b; color:#fff; border-radius:5px; cursor:pointer; transition:0.3s; font-size:14px; margin-right:10px;}
.view-toggle:hover { background:#ff6b4b; }

/* ===== Cards Grid ===== */
.cards-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:20px; }
.card { background:#1e1e1e; border-radius:10px; overflow:hidden; box-shadow:0 0 10px rgba(0,0,0,0.5); cursor:pointer; border:2px solid #2a2a2a; transition:0.3s ease; }
.card img { width:100%; height:150px; object-fit:cover; }
.card-content { padding:10px; }
.card-content h3 { margin-bottom:5px; font-size:16px; }
.card-content p { font-size:13px; color:#bbb; margin-bottom:5px; }
.card-content .price { color:#ff4b2b; font-weight:600; margin-bottom:5px; }
.card-content .rating { color:#ffdd00; font-size:14px; }
.card:hover { border-color:#ff4b2b; box-shadow:0 10px 20px rgba(255,75,43,0.4); transform:translateY(-5px); }

/* ===== Categories Grid ===== */
.categories-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(100px,1fr)); gap:15px; }
.category { background:#1e1e1e; text-align:center; padding:10px; border-radius:10px; cursor:pointer; transition:0.3s; }
.category img { width:50px; height:50px; object-fit:cover; margin-bottom:8px; border-radius:50%; }
.category p { font-size:12px; }
.category:hover { background:#ff4b2b; color:#fff; transform:scale(1.05); }

/* ===== Featured Offers ===== */
.offers-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:20px; }
.offer-card { background:#1e1e1e; border:2px solid #2a2a2a; border-radius:10px; overflow:hidden; cursor:pointer; transition:0.3s ease; }
.offer-card img { width:100%; height:150px; object-fit:cover; }
.offer-content { padding:10px; }
.offer-content h3 { margin-bottom:5px; font-size:16px; color:#ff4b2b; }
.offer-content p { font-size:13px; color:#bbb; margin-bottom:5px; }
.offer-content .price { color:#ff4b2b; font-weight:600; }
.offer-card:hover { border-color:#ff4b2b; box-shadow:0 10px 20px rgba(255,75,43,0.4); transform:translateY(-5px); }

/* ===== Slider Section ===== */
.slider-container { position:relative; display:flex; align-items:center; gap:10px; }
.slider { display:flex; overflow:hidden; scroll-behavior:smooth; width:100%; }
.slide-card { min-width:200px; margin-right:15px; background:#1e1e1e; border-radius:10px; text-align:center; transition:0.3s; cursor:pointer; border:2px solid #2a2a2a; }
.slide-card img { width:100%; height:150px; object-fit:cover; border-radius:10px 10px 0 0; }
.slide-card h3 { padding:10px 0; color:#ff4b2b; }
.slide-card .price { color:#ff4b2b; font-weight:600; margin-bottom:10px; }
.slide-card:hover { border-color:#ff4b2b; box-shadow:0 10px 20px rgba(255,75,43,0.4); transform:translateY(-5px); }
.prev, .next { background:#ff4b2b; border:none; color:#fff; padding:10px 12px; cursor:pointer; border-radius:50%; font-size:18px; transition:0.3s; }
.prev:hover, .next:hover { background:#ff6b4b; }

.feature-card, .step-card {
    background:#222; padding:20px; border-radius:12px;
    width:200px; text-align:center; font-size:18px;
    transition:0.3s; cursor:pointer;
    box-shadow:0 0 10px rgba(0,0,0,0.3);
  }
  .feature-card:hover, .step-card:hover {
    border:2px solid #ff4b2b;
    transform:translateY(-5px);
  }
  .review-slider {
    display:flex; overflow-x:auto; gap:20px; scroll-behavior:smooth;
  }
  .review-card {
    flex:0 0 300px; background:#222; padding:20px;
    border-radius:12px; box-shadow:0 0 10px rgba(0,0,0,0.4);
    transition:0.3s;
  }
  .review-card:hover { transform:scale(1.05); border:2px solid #ff4b2b; }
  .app-btn {
    background:#ff4b2b; color:#fff; border:none; padding:12px 20px;
    border-radius:8px; cursor:pointer; font-size:16px; transition:0.3s;
  }
  .app-btn:hover { background:#ff1e00; transform:scale(1.05); }
  .newsletter-input {
    padding:12px; border:none; border-radius:8px; width:250px;
    outline:none;
  }
  section { animation: fadeUp 1s ease-in-out; }
  @keyframes fadeUp {
    from { opacity:0; transform:translateY(30px); }
    to { opacity:1; transform:translateY(0); }
  }
  
/* ===== Footer ===== */
footer { background:#1e1e1e; padding:20px 30px; text-align:center; margin-top:30px; }
footer p { margin-bottom:10px; font-size:14px; }
footer a { color:#ff4b2b; margin:0 8px; font-size:18px; }

/* ===== Responsive ===== */
@media(max-width:768px){
    .hero { flex-direction:column; text-align:center; min-height:300px; }
    .hero-text { max-width:100%; }
    .search-bar input { width:150px; }
    .nav-links { display:none; flex-direction:column; background:#1e1e1e; position:absolute; top:60px; right:0; width:200px; padding:15px; border-radius:10px; }
    .nav-links.show { display:flex; }
    .hamburger { display:block; }
    .nav-links .search-bar { margin-bottom:10px; }
    .slide-card { min-width:150px; margin-right:10px; }
    .cards-grid, .offers-grid, .categories-grid { gap:15px; }
}
</style>
</head>
<body>

<!-- Navbar -->
<nav>
    <div class="logo">Foodie</div>
    <div class="hamburger"><i class="fas fa-bars"></i></div>
    <div class="nav-links">
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search for restaurants, food...">
        </div>
        <a href="#">Home</a>
        <a href="#">Restaurants</a>
        <a href="#">Offers</a>
        <a href="#">About</a>
        <a href="#">Contact</a>
        <a href="#"><i class="fas fa-shopping-cart"></i></a>
        <?php if(isset($_SESSION['user'])): ?>
        <div class="profile">
          👤 <?php echo $_SESSION['user']; ?>
          <div class="dropdown">
            <a href="auth.php?logout=1">Logout</a>
          </div>
        </div>
      <?php else: ?>
        <a href="auth.php">Login</a>
      <?php endif; ?>
    </div>

</nav>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-text">
        <h1>Delicious Food Delivered To You</h1>
        <p>Explore the best restaurants and fast food in your city. Order now and enjoy!</p>
        <button>Order Now</button>
    </div>
</section>

<!-- Trending Restaurants -->
<section>
    <h2>Trending Restaurants</h2>
    <div class="cards-grid" id="trending-grid">
        <div class="card">
            <img src="https://images.unsplash.com/photo-1600891964599-f61ba0e24092?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=400" alt="">
            <div class="card-content"><h3>Pizza Hut</h3><p>Italian, Fast Food</p><span class="price">₹350</span><span class="rating">⭐ 4.5 | 30-40 min</span></div>
        </div>
        <div class="card">
            <img src="https://images.unsplash.com/photo-1562967916-eb82221dfb29?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=400" alt="">
            <div class="card-content"><h3>Burger King</h3><p>Burgers, Fast Food</p><span class="price">₹250</span><span class="rating">⭐ 4.3 | 25-35 min</span></div>
        </div>
        <div class="card">
            <img src="https://images.unsplash.com/photo-1551218808-94e220e084d2?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=400" alt="">
            <div class="card-content"><h3>Sushi World</h3><p>Japanese, Sushi</p><span class="price">₹500</span><span class="rating">⭐ 4.7 | 40-50 min</span></div>
        </div>
        <div class="card">
            <img src="https://images.unsplash.com/photo-1600891964599-f61ba0e24092?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=400" alt="">
            <div class="card-content"><h3>Domino's</h3><p>Italian, Fast Food</p><span class="price">₹400</span><span class="rating">⭐ 4.6 | 30-40 min</span></div>
        </div>
        <div class="card">
            <img src="https://images.unsplash.com/photo-1562967916-eb82221dfb29?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=400" alt="">
            <div class="card-content"><h3>McDonald's</h3><p>Burgers, Fast Food</p><span class="price">₹300</span><span class="rating">⭐ 4.4 | 25-35 min</span></div>
        </div>
        <div class="card">
            <img src="https://images.unsplash.com/photo-1551218808-94e220e084d2?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=400" alt="">
            <div class="card-content"><h3>Subway</h3><p>Sandwiches, Fast Food</p><span class="price">₹350</span><span class="rating">⭐ 4.5 | 20-30 min</span></div>
        </div>
    </div>
    <span class="view-toggle" data-target="trending">View All</span>
    <span class="view-toggle hide-btn" data-target="trending" style="display:none;">Hide</span>
</section>

<!-- Food Categories -->
<section>
    <h2>Food Categories</h2>
    <div class="categories-grid" id="categories-grid">
        <div class="category"><img src="https://img.icons8.com/color/96/pizza.png" alt=""><p>Pizza</p></div>
        <div class="category"><img src="https://img.icons8.com/color/96/hamburger.png" alt=""><p>Burger</p></div>
        <div class="category"><img src="https://img.icons8.com/color/96/sushi.png" alt=""><p>Sushi</p></div>
        <div class="category"><img src="https://img.icons8.com/color/96/cake.png" alt=""><p>Desserts</p></div>
        <div class="category"><img src="https://img.icons8.com/color/96/noodles.png" alt=""><p>Chinese</p></div>
    </div>
    <span class="view-toggle" data-target="categories">View All</span>
    <span class="view-toggle hide-btn" data-target="categories" style="display:none;">Hide</span>
</section>

<!-- Featured Offers -->
<section>
    <h2>Featured Offers</h2>
    <div class="offers-grid" id="offers-grid">
        <div class="offer-card">
            <img src="https://images.unsplash.com/photo-1604908177527-6e43259ee82f?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=400" alt="">
            <div class="offer-content">
                <h3>50% Off on Pizza</h3>
                <p>Order now and enjoy half price on all pizzas!</p>
                <span class="price">₹150 <del>₹300</del></span>
            </div>
        </div>
        <div class="offer-card">
            <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=400" alt="">
            <div class="offer-content">
                <h3>Burger Combo Deal</h3>
                <p>Buy 1 get 1 free on selected burgers!</p>
                <span class="price">₹200 <del>₹400</del></span>
            </div>
        </div>
    </div>
    <span class="view-toggle" data-target="offers">View All</span>
    <span class="view-toggle hide-btn" data-target="offers" style="display:none;">Hide</span>
</section>

<!-- Why Choose Us Section -->
<section style="padding:60px 20px; background:#181818; text-align:center;">
  <h2 style="font-size:32px; margin-bottom:40px;">Why Choose Us</h2>
  <div style="display:flex; flex-wrap:wrap; justify-content:center; gap:20px;">
    <div class="feature-card">⚡ Fast Delivery</div>
    <div class="feature-card">🍴 Fresh & Tasty</div>
    <div class="feature-card">💳 Secure Payments</div>
    <div class="feature-card">⭐ Top Rated</div>
  </div>
</section>

<!-- How It Works Section -->
<section style="padding:60px 20px; background:#121212; text-align:center;">
  <h2 style="font-size:32px; margin-bottom:40px;">How It Works</h2>
  <div style="display:flex; flex-wrap:wrap; justify-content:center; gap:30px;">
    <div class="step-card">1️⃣ Browse Restaurants</div>
    <div class="step-card">2️⃣ Choose Your Meal</div>
    <div class="step-card">3️⃣ Place Order</div>
    <div class="step-card">4️⃣ Enjoy Food</div>
  </div>
</section>

<!-- Customer Reviews Section -->
<section style="padding:60px 20px; background:#181818; text-align:center;">
  <h2 style="font-size:32px; margin-bottom:40px;">What Our Customers Say</h2>
  <div class="review-slider">
    <div class="review-card">"Super fast delivery and delicious food!" ⭐⭐⭐⭐⭐</div>
    <div class="review-card">"Loved the variety and offers!" ⭐⭐⭐⭐⭐</div>
    <div class="review-card">"Best app for ordering food at night!" ⭐⭐⭐⭐</div>
  </div>
</section>

<!-- App Download Section -->
<section style="padding:60px 20px; background:#121212; text-align:center;">
  <h2 style="font-size:32px; margin-bottom:20px;">Get Our App</h2>
  <p style="margin-bottom:20px;">Order food faster on our mobile app</p>
  <div style="display:flex; justify-content:center; gap:20px; flex-wrap:wrap;">
    <button class="app-btn">📲 Play Store</button>
    <button class="app-btn">🍎 App Store</button>
  </div>
</section>

<!-- Newsletter Section -->
<section style="padding:60px 20px; background:#181818; text-align:center;">
  <h2 style="font-size:32px; margin-bottom:20px;">Stay Updated</h2>
  <p style="margin-bottom:20px;">Get latest offers directly in your inbox</p>
  <form style="display:flex; justify-content:center; flex-wrap:wrap; gap:10px;">
    <input type="email" placeholder="Enter your email" class="newsletter-input" required>
    <button type="submit" class="app-btn">Subscribe</button>
  </form>
</section>


<!-- Footer -->
<footer>
    <p>© 2025 Foodie. All rights reserved.</p>
    <p>
        <a href="#"><i class="fab fa-facebook"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
    </p>
</footer>

<script>
// Hamburger toggle
const hamburger = document.querySelector('.hamburger');
const navLinks = document.querySelector('.nav-links');
hamburger.addEventListener('click', () => { navLinks.classList.toggle('show'); });

// View All + Hide functionality
const viewButtons = document.querySelectorAll('.view-toggle');
const extraData = {
    trending:[
        { img:"https://images.unsplash.com/photo-1600891964599-f61ba0e24092?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=400", name:"KFC", type:"Fast Food", price:"₹350", rating:"⭐ 4.5 | 30-40 min"},
        { img:"https://images.unsplash.com/photo-1562967916-eb82221dfb29?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=400", name:"Cafe Coffee", type:"Cafe, Drinks", price:"₹200", rating:"⭐ 4.2 | 15-20 min"},
        { img:"https://images.unsplash.com/photo-1551218808-94e220e084d2?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=400", name:"Taco Bell", type:"Mexican", price:"₹300", rating:"⭐ 4.3 | 25-35 min"},
        { img:"https://images.unsplash.com/photo-1600891964599-f61ba0e24092?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=400", name:"Domino's Special", type:"Italian", price:"₹400", rating:"⭐ 4.6 | 30-40 min"}
    ],
    categories:[
        { img:"https://img.icons8.com/color/96/salad.png", name:"Salads" },
        { img:"https://img.icons8.com/color/96/steak.png", name:"Steak" },
        { img:"https://img.icons8.com/color/96/coffee.png", name:"Coffee" },
        { img:"https://img.icons8.com/color/96/juice.png", name:"Juices" }
    ],
    offers:[
        { img:"https://images.unsplash.com/photo-1604908177527-6e43259ee82f?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=400", title:"Pasta Madness", desc:"Flat 40% off on all pastas!", price:"₹250 <del>₹400</del>"},
        { img:"https://images.unsplash.com/photo-1555939594-58d7cb561ad1?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=400", title:"Dessert Fiesta", desc:"Buy 2 desserts get 1 free!", price:"₹150 <del>₹300</del>"}
    ]
};

viewButtons.forEach(btn=>{
    btn.addEventListener('click',()=>{
        const target = btn.dataset.target;
        const grid = document.getElementById(target+'-grid');
        const hideBtn = document.querySelector('.hide-btn[data-target="'+target+'"]');

        if(btn.textContent.includes('View')){
            // Add extra
            extraData[target].forEach(item=>{
                const div = document.createElement('div');
                if(target==='trending'){
                    div.classList.add('card');
                    div.innerHTML = `<img src="${item.img}" alt=""><div class="card-content"><h3>${item.name}</h3><p>${item.type}</p><span class="price">${item.price}</span><span class="rating">${item.rating}</span></div>`;
                }
                if(target==='categories'){
                    div.classList.add('category');
                    div.innerHTML = `<img src="${item.img}" alt=""><p>${item.name}</p>`;
                }
                if(target==='offers'){
                    div.classList.add('offer-card');
                    div.innerHTML = `<img src="${item.img}" alt=""><div class="offer-content"><h3>${item.title}</h3><p>${item.desc}</p><span class="price">${item.price}</span></div>`;
                }
                grid.appendChild(div);
            });
            btn.style.display='none';
            hideBtn.style.display='inline-block';
        }
    });
});

// Hide button
const hideButtons = document.querySelectorAll('.hide-btn');
hideButtons.forEach(btn=>{
    btn.addEventListener('click',()=>{
        const target = btn.dataset.target;
        const grid = document.getElementById(target+'-grid');
        // Remove last n items
        const extraCount = extraData[target].length;
        for(let i=0;i<extraCount;i++){
            grid.removeChild(grid.lastElementChild);
        }
        btn.style.display='none';
        document.querySelector('.view-toggle[data-target="'+target+'"]').style.display='inline-block';
    });
});
</script>

</body>
</html>
