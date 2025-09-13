<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Luxury Menu — Foodie Premium</title>

<!-- Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

<style>
  :root{
    --bg-1:#070607;
    --bg-2:#0f0e12;
    --card-bg: rgba(255,255,255,0.04);
    --glass-border: rgba(255,215,0,0.18);
    --gold: #ffd24d;
    --muted: #a8a8b3;
    --accent: linear-gradient(90deg,#ffd24d,#ff9b3d);
    --glass-shadow: 0 10px 40px rgba(0,0,0,0.6);
    --glass-glow: 0 6px 35px rgba(255,210,77,0.08);
    --radius: 18px;
  }

  /* Page background + particles canvas */
  html,body{height:100%;margin:0;font-family:"Poppins",system-ui,-apple-system,Segoe UI,Roboto,Arial;background:
    radial-gradient(circle at 10% 10%, rgba(255,215,77,0.02), transparent 8%),
    linear-gradient(135deg,var(--bg-1),var(--bg-2));color:#fff;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;}

  /* Page container centers the menu */
  .page {
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:48px 20px;
    position:relative;
    overflow:hidden;
  }

  /* floating particles layer (pointer-events none) */
  .particles {
    position:absolute;inset:0;z-index:0;pointer-events:none;
  }

  /* MAIN GLASS BOX */
  .menu-wrap{
    width:100%;
    max-width:1100px;
    background:var(--card-bg);
    border-radius:var(--radius);
    padding:28px;
    display:grid;
    gap:20px;
    grid-template-columns: 360px 1fr;
    border:1px solid var(--glass-border);
    box-shadow:var(--glass-shadow), var(--glass-glow);
    backdrop-filter: blur(10px) saturate(120%);
    z-index:2;
    transform: translateY(10px);
    animation:boxIn 800ms cubic-bezier(.2,.9,.3,1) both;
  }

  @keyframes boxIn{from{opacity:0;transform:translateY(24px) scale(.995)} to{opacity:1;transform:none}}

  /* LEFT: Categories + specials */
  .menu-left{
    padding:14px 10px;
    display:flex;
    flex-direction:column;
    gap:18px;
  }
  .brand {
    display:flex;align-items:center;gap:12px;margin-bottom:6px;
  }
  .brand .logo {
    width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,#ffb84d,#ff6a3d);
    display:grid;place-items:center;color:#111;font-weight:700;font-size:20px;box-shadow:0 6px 18px rgba(255,107,45,0.15);
  }
  .brand h3{font-family:"Playfair Display",serif;margin:0;font-size:20px;color:var(--gold);letter-spacing:0.6px}

  .today-badge{
    background: linear-gradient(90deg,#ffb84d,#ff8a3d);
    color:#111;padding:10px;border-radius:12px;font-weight:600;display:flex;align-items:center;gap:10px;box-shadow:0 6px 24px rgba(255,168,85,0.12);
  }
  .today-badge .spark{font-size:18px;transform:rotate(-15deg)}

  .search-wrap{
    margin-top:6px;
    display:flex;gap:8px;
    align-items:center;
    background:rgba(255,255,255,0.02);
    padding:10px;border-radius:12px;
    border:1px solid rgba(255,255,255,0.03);
  }
  .search-wrap input{
    flex:1;background:transparent;border:none;color:#fff;outline:none;font-size:14px;padding:6px 8px;
  }
  .search-wrap .icon{color:var(--muted);font-size:16px}

  .categories{
    display:flex;flex-direction:column;gap:8px;margin-top:6px;
  }
  .cat-btn {
    display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:12px;color:var(--muted);
    background:transparent;border:1px solid rgba(255,255,255,0.02);cursor:pointer;font-weight:600;transition:all .28s;
  }
  .cat-btn i{width:28px;height:28px;border-radius:8px;display:grid;place-items:center;background:rgba(255,255,255,0.02);color:var(--gold)}
  .cat-btn:hover{transform:translateX(6px);color:var(--gold);box-shadow:0 8px 30px rgba(255, 180, 60, 0.06)}

  /* RIGHT: the menu list */
  .menu-right{padding:10px 6px;overflow:auto;max-height:75vh}
  .menu-header{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:14px}
  .menu-header h2{font-family:"Playfair Display",serif;margin:0;font-size:22px;color:var(--gold)}
  .menu-header .filters{display:flex;gap:8px;align-items:center}

  /* category section */
  .section{padding:18px 8px;border-radius:12px;margin-bottom:18px;background:linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.00)); border:1px solid rgba(255,255,255,0.02)}
  .section h3{font-family:"Playfair Display",serif;margin:0 0 8px 0;color:#fff;font-size:18px;display:flex;align-items:center;gap:10px}
  .section h3 .fa{color:var(--gold);font-size:18px}

  /* text-only menu list (luxury) */
  .menu-list{display:flex;flex-direction:column;gap:10px;padding-top:6px}
  .menu-item{
    display:flex;align-items:center;justify-content:space-between;gap:16px;padding:12px;border-radius:10px;
    transition:all .28s;cursor:pointer;
    border-left:2px solid transparent;
  }
  .menu-item .left{display:flex;flex-direction:column;gap:4px}
  .name{font-weight:600;color:#fff;font-size:16px;letter-spacing:0.2px}
  .desc{color:var(--muted);font-size:13px}
  .price{color:var(--gold);font-weight:700;min-width:72px;text-align:right}

  .menu-item:hover{transform:translateX(8px);box-shadow:0 18px 40px rgba(0,0,0,0.6);border-left:2px solid rgba(255,200,70,0.18);color:var(--gold)}
  .menu-item:focus{outline:none;box-shadow:0 18px 50px rgba(255,200,70,0.06)}

  /* subtle item micro-icon */
  .pill{
    display:inline-flex;align-items:center;gap:8px;padding:6px 10px;border-radius:999px;background:rgba(255,255,255,0.02);color:var(--muted);font-size:12px
  }

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


  /* small screen responsive -> single column */
  @media (max-width:980px){
    .menu-wrap{grid-template-columns:1fr; padding:20px; max-width:760px}
    .menu-right{max-height:60vh}
  }
  @media (max-width:520px){
    .menu-wrap{padding:16px;border-radius:14px}
    .brand h3{font-size:18px}
    .menu-header h2{font-size:18px}
    .menu-item{padding:10px}
    .price{min-width:60px}
  }

  /* floating particle styling */
  .particle{
    position:absolute;opacity:0.12;filter:drop-shadow(0 6px 20px rgba(0,0,0,0.8));
    transform-origin:center; will-change: transform, top, left;
  }

  /* small subtle entrance */
  .reveal{opacity:0;transform:translateY(10px);transition:all .6s cubic-bezier(.2,.9,.3,1)}
  .reveal.show{opacity:1;transform:none}
</style>
</head>
<body>
  <div class="page" aria-live="polite">
    <!-- particle svg layer -->
    <svg class="particles" width="100%" height="100%" aria-hidden="true">
      <!-- we'll append animated small icons via JS into this SVG -->
    </svg>

    <!-- main glass box -->
    <main class="menu-wrap" role="main">

      <!-- LEFT: brand + search + categories -->
      <aside class="menu-left" aria-label="Menu controls">
        <div class="brand">
          <div class="logo" aria-hidden="true">F</div>
          <div>
            <h3>Foodie</h3>
            <div style="color:var(--muted);font-size:13px">Luxury Menu</div>
          </div>
        </div>

        <div class="today-badge" title="Today's special">
          <div class="spark"><i class="fa-solid fa-star"></i></div>
          <div>
            <div style="font-size:13px;color:#111;font-weight:700">Today's Special</div>
            <div style="font-size:13px;color:#0e0e0e;font-weight:600">Truffle Margherita</div>
          </div>
        </div>

        <div class="search-wrap" role="search" aria-label="Search menu items">
          <i class="icon fa fa-magnifying-glass"></i>
          <input id="searchInput" placeholder="Search dishes, e.g. 'pizza'..." aria-label="Search dishes" />
          <button id="clearSearch" title="Clear search" style="border:none;background:transparent;color:var(--muted);font-size:14px;cursor:pointer">✕</button>
        </div>

        <nav class="categories" aria-label="Categories">
          <button class="cat-btn" data-target="starters"><i class="fa-solid fa-plate-wheat"></i> Starters</button>
          <button class="cat-btn" data-target="pizza"><i class="fa-solid fa-pizza-slice"></i> Pizza</button>
          <button class="cat-btn" data-target="burgers"><i class="fa-solid fa-burger"></i> Burgers</button>
          <button class="cat-btn" data-target="main"><i class="fa-solid fa-drumstick-bite"></i> Main Course</button>
          <button class="cat-btn" data-target="drinks"><i class="fa-solid fa-wine-glass-alt"></i> Drinks</button>
          <button class="cat-btn" data-target="desserts"><i class="fa-solid fa-cake-candles"></i> Desserts</button>
          <button class="cat-btn" data-target="specials"><i class="fa-solid fa-gift"></i> Specials</button>
        </nav>

        <div style="margin-top:auto;color:var(--muted);font-size:13px;padding-top:8px">
          Tip: Hover an item to preview and click to view details.
        </div>
      </aside>

      <!-- RIGHT: menu content -->
      <section class="menu-right" aria-label="Menu items">
        <div class="menu-header">
          <h2>Explore our carefully curated menu</h2>
          <div class="filters">
            <div class="pill" aria-hidden="true"><i class="fa fa-clock"></i> Fast Delivery</div>
            <div class="pill" aria-hidden="true"><i class="fa fa-leaf"></i> Veg Options</div>
          </div>
        </div>

        <!-- Sections (each has id to scroll into) -->
        <!-- We'll include many items; each item has data-slug to redirect -->
        <article id="starters" class="section reveal">
          <h3><i class="fa-solid fa-plate-wheat"></i> Starters</h3>
          <div class="menu-list" data-category="starters">
            <div tabindex="0" class="menu-item" data-slug="tomato-soup" data-img="https://images.unsplash.com/photo-1600891964599-f61ba0e24092?auto=format&fit=crop&w=120&q=80" role="link" aria-label="Tomato Soup — 120 rupees">
              <div class="left">
                <div class="name">Tomato Basil Soup</div>
                <div class="desc">Velvety tomato, basil oil drizzle</div>
              </div>
              <div class="price">₹120</div>
            </div>

            <div tabindex="0" class="menu-item" data-slug="crispy-rolls">
              <div class="left">
                <div class="name">Crispy Veg Rolls</div>
                <div class="desc">Golden fried rolls with herbed filling</div>
              </div>
              <div class="price">₹149</div>
            </div>

            <div tabindex="0" class="menu-item" data-slug="garlic-bread">
              <div class="left">
                <div class="name">Garlic Herb Bread</div>
                <div class="desc">Buttery garlic, oregano crust</div>
              </div>
              <div class="price">₹129</div>
            </div>
          </div>
        </article>

        <article id="pizza" class="section reveal">
          <h3><i class="fa-solid fa-pizza-slice"></i> Pizza</h3>
          <div class="menu-list" data-category="pizza">
            <div tabindex="0" class="menu-item" data-slug="margherita">
              <div class="left"><div class="name">Margherita (Truffle option)</div><div class="desc">San Marzano tomato, fior di latte</div></div>
              <div class="price">₹299</div>
            </div>

            <div tabindex="0" class="menu-item" data-slug="pepperoni">
              <div class="left"><div class="name">Pepperoni Classic</div><div class="desc">Smoky pepperoni, mozzarella</div></div>
              <div class="price">₹349</div>
            </div>

            <div tabindex="0" class="menu-item" data-slug="farmhouse">
              <div class="left"><div class="name">Farmhouse Delight</div><div class="desc">Roasted veggies, basil finish</div></div>
              <div class="price">₹339</div>
            </div>
          </div>
        </article>

        <article id="burgers" class="section reveal">
          <h3><i class="fa-solid fa-burger"></i> Burgers</h3>
          <div class="menu-list" data-category="burgers">
            <div tabindex="0" class="menu-item" data-slug="classic-veg-burger">
              <div class="left"><div class="name">Classic Veg Burger</div><div class="desc">Crispy patty, chef's sauce</div></div>
              <div class="price">₹149</div>
            </div>

            <div tabindex="0" class="menu-item" data-slug="cheese-burst">
              <div class="left"><div class="name">Cheese Burst Burger</div><div class="desc">Double cheese melt</div></div>
              <div class="price">₹199</div>
            </div>
          </div>
        </article>

        <article id="main" class="section reveal">
          <h3><i class="fa-solid fa-drumstick-bite"></i> Main Course</h3>
          <div class="menu-list" data-category="main">
            <div tabindex="0" class="menu-item" data-slug="alfredo-pasta">
              <div class="left"><div class="name">Creamy Alfredo Pasta</div><div class="desc">Parmesan cream, garlic crumbs</div></div>
              <div class="price">₹320</div>
            </div>

            <div tabindex="0" class="menu-item" data-slug="chicken-biryani">
              <div class="left"><div class="name">Hyderabadi Chicken Biryani</div><div class="desc">Slow-cooked spices, saffron rice</div></div>
              <div class="price">₹420</div>
            </div>
          </div>
        </article>

        <article id="drinks" class="section reveal">
          <h3><i class="fa-solid fa-wine-glass-alt"></i> Drinks</h3>
          <div class="menu-list" data-category="drinks">
            <div tabindex="0" class="menu-item" data-slug="lime-soda">
              <div class="left"><div class="name">Fresh Lime Soda</div><div class="desc">Lemon, fizz, mint</div></div>
              <div class="price">₹79</div>
            </div>

            <div tabindex="0" class="menu-item" data-slug="cold-coffee">
              <div class="left"><div class="name">Iced Cold Coffee</div><div class="desc">Vanilla whipped cream</div></div>
              <div class="price">₹129</div>
            </div>
          </div>
        </article>

        <article id="desserts" class="section reveal">
          <h3><i class="fa-solid fa-cake-candles"></i> Desserts</h3>
          <div class="menu-list" data-category="desserts">
            <div tabindex="0" class="menu-item" data-slug="lava-cake">
              <div class="left"><div class="name">Chocolate Lava Cake</div><div class="desc">Warm center, vanilla ice cream</div></div>
              <div class="price">₹179</div>
            </div>

            <div tabindex="0" class="menu-item" data-slug="red-velvet">
              <div class="left"><div class="name">Red Velvet Slice</div><div class="desc">Cream cheese frosting</div></div>
              <div class="price">₹210</div>
            </div>
          </div>
        </article>

        <article id="specials" class="section reveal">
          <h3><i class="fa-solid fa-gift"></i> Chef's Specials</h3>
          <div class="menu-list" data-category="specials">
            <div tabindex="0" class="menu-item" data-slug="truffle-margherita">
              <div class="left"><div class="name">Truffle Margherita</div><div class="desc">Black truffle, buffalo mozzarella</div></div>
              <div class="price">₹599</div>
            </div>
          </div>
        </article>

      </section>
    </main>
  </div>

<!-- Product Details Popup -->
<div id="singleProductModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.7);backdrop-filter:blur(4px);z-index:9999;justify-content:center;align-items:center;">
  <div style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,215,0,0.2);border-radius:20px;padding:28px;max-width:400px;width:90%;text-align:center;box-shadow:0 12px 40px rgba(0,0,0,0.8);animation:modalIn 400ms ease forwards;position:relative;">

    <!-- CLOSE ICON -->
    <span onclick="closeModal()" style="position:absolute;top:12px;right:15px;color:#ffd24d;font-size:30px;font-weight:bold;cursor:pointer;">&times;</span>

    <!-- IMAGE -->
    <img id="modalImage" src="" alt="Product Image" style="width:80%;border-radius:12px;margin-bottom:18px;object-fit:cover; transition: all 0.3s ease">
    <h3 id="modalName" style="color:#ffd24d;margin-bottom:12px;font-family:'Playfair Display',serif;"></h3>
    <p id="modalDesc" style="color:#fff;margin-bottom:18px;font-size:14px;"></p>
    <div id="modalPrice" style="color:#ffd24d;font-weight:700;margin-bottom:20px;font-size:18px;"></div>
    <button onclick="openOrderForm()" style="padding:12px 24px;border:none;border-radius:12px;background:#ffb84d;color:#111;font-weight:600;cursor:pointer;transition:0.3s;">Order Now</button>
  </div>
</div>

<!-- Order Form Modal -->
<div id="orderFormModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.7);backdrop-filter:blur(4px);z-index:10000;justify-content:center;align-items:center;">
  <div style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,215,0,0.2);border-radius:20px;padding:28px;max-width:450px;width:90%;text-align:center;box-shadow:0 12px 40px rgba(0,0,0,0.8);position:relative;">
    
    <!-- Close Icon -->
    <span onclick="closeOrderForm()" style="position:absolute;top:12px;right:15px;color:#ffd24d;font-size:30px;font-weight:bold;cursor:pointer;">&times;</span>
    
    <h2 style="color:#ffd24d;margin-bottom:20px;">Place Your Order</h2>

    <form id="orderForm" action="save_order.php" method="POST" style="display:flex;flex-direction:column;gap:12px;">
      <!-- Hidden fields for product -->
      <input type="hidden" name="product_name" id="orderProductName">
      <input type="hidden" name="product_price" id="orderProductPrice">
      <input type="hidden" name="product_image" id="orderProductImage">

      <!-- User Details -->
      <input type="text" name="user_name" placeholder="Full Name" required style="padding:10px;border-radius:8px;border:none;background:rgba(0,0,0,0.2)">
      <input type="text" name="village" id="userVillage" placeholder="Village" required style="padding:10px;border-radius:8px;border:none; background:rgba(0,0,0,0.2)">
      <input type="text" name="city" id="userCity" placeholder="City" required style="padding:10px;border-radius:8px;border:none; background:rgba(0,0,0,0.2)">
      <input type="text" name="zipcode" id="userZipcode" placeholder="Zipcode" required style="padding:10px;border-radius:8px;border:none; background:rgba(0,0,0,0.2)">
      <input type="text" name="state" id="userState" placeholder="State" required style="padding:10px;border-radius:8px;border:none; background:rgba(0,0,0,0.2)">
      <input type="text" name="user_mobile" placeholder="Mobile Number" required style="padding:10px;border-radius:8px;border:none; background:rgba(0,0,0,0.2)">
      <input type="number" name="quantity" placeholder="Quantity" value="1" min="1" required style="padding:10px;border-radius:8px;border:none;background:rgba(0,0,0,0.2)">

      <!-- Payment Method -->
      <select name="payment_method" required style="padding:10px;border-radius:8px;border:none; background:rgba(0,0,0,0.2); color:#fff">
        <option style="color: black; " value="">Select Payment Method</option>
        <option style="color: #070607;" value="Cash on Delivery">Cash on Delivery</option>
        <option style="color: #070607;" value="UPI">UPI</option>
        <option style="color: #070607;" value="Credit/Debit Card">Credit/Debit Card</option>
      </select>

      <!-- Current Location Button -->
      <button type="button" onclick="getCurrentLocation()" style="padding:10px;border-radius:8px;border:none;background:#ffb84d;color:#111;font-weight:600;cursor:pointer;">Use My Current Location</button>

      <!-- Submit Button -->
      <button type="submit" style="padding:12px;border-radius:12px;border:none;background:#ffd24d;color:#111;font-weight:700;cursor:pointer;">Place Order</button>
    </form>
  </div>
</div>

<!-- Fullscreen overlay container (unchanged) -->
<div id="fullscreenImageOverlay">
  <span onclick="closeFullscreenImage()">&times;</span>
  <img id="fullscreenImage" src="" alt="Full Image">
</div>

<style>
  input{
    color:#ffd24d ;
  }
  input:focus{
    outline: none;
    background: rgba(255, 180, 60, 0.06);
    box-shadow: 0 0 18px rgba(255,215,0,0.2);
  }

  select:focus{
    outline: none;
    background: rgba(255, 180, 60, 0.06);
    box-shadow: 0 0 18px rgba(255,215,0,0.2);
  }
  /* Hover box shadow + scale */
  #modalImage:hover {
    box-shadow: 0 20px 30px rgba(255, 216, 77, 0.39);
    transform: scale(1.05);
    transition: transform 0.3s ease;
  }

  /* Fullscreen overlay */
  #fullscreenImageOverlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(5px);
    justify-content: center;
    align-items: center;
    z-index: 11000;
  }
  #fullscreenImageOverlay img { max-width: 60%; max-height: 60%; border-radius: 12px; }
  #fullscreenImageOverlay span { position: absolute; top: 20px; right: 25px; font-size: 32px; color: #ffd24d; cursor: pointer; }

  @keyframes modalIn {from{opacity:0;transform:scale(0.95)}to{opacity:1;transform:scale(1)}}
</style>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
  // जब भी modal open हो तब product details hidden fields में डालो
  function openOrderForm(name, price, image) {
    document.getElementById("orderProductName").value  = name;
    document.getElementById("orderProductPrice").value = price;
    document.getElementById("orderProductImage").value = image;

    document.getElementById("modalProductName").innerText  = name;
    document.getElementById("modalProductPrice").innerText = "₹" + price;
    document.getElementById("modalProductImage").src = image;

    document.getElementById("orderModal").style.display = "flex";
  }

  // Modal बंद करने का function
  function closeOrderForm() {
    document.getElementById("orderModal").style.display = "none";
  }

  // Place Order Button Handle
  document.getElementById("placeOrderBtn").addEventListener("click", function(e){
    e.preventDefault();

    const paymentMethod = document.querySelector("input[name='payment_method']:checked").value;
    const productName  = document.getElementById("orderProductName").value;
    const productPrice = document.getElementById("orderProductPrice").value;
    const productImage = document.getElementById("orderProductImage").value;

    if(paymentMethod === "upi"){
      // 🔹 Razorpay Integration
      var options = {
        "key": "rzp_test_xxxxxxxxxx", // यहाँ अपना Razorpay Key डालो
        "amount": productPrice * 100, // पैसे पैसे में (₹100 = 10000)
        "currency": "INR",
        "name": "Luxury Restaurant",
        "description": productName,
        "image": productImage,
        "handler": function (response){
          // ✅ Payment success → अब form submit करो
          document.getElementById("orderForm").submit();
        },
        "prefill": {
          "name": document.getElementById("username").value,
          "email": "demo@example.com", 
          "contact": document.getElementById("mobile").value
        },
        "theme": {
          "color": "#F37254"
        }
      };
      var rzp1 = new Razorpay(options);
      rzp1.open();
    }
    else {
      // 🔹 Cash on Delivery → direct form submit
      document.getElementById("orderForm").submit();
    }
  });
</script>

<script>
  const singlePriceItems = ['tomato-soup','garlic-bread','crispy-rolls','lime-soda','cold-coffee','lava-cake','red-velvet'];
  const modal = document.getElementById('singleProductModal');
  const modalName = document.getElementById('modalName');
  const modalDesc = document.getElementById('modalDesc');
  const modalPrice = document.getElementById('modalPrice');
  const modalImage = document.getElementById('modalImage');

  const orderFormModal = document.getElementById('orderFormModal');
  const orderProductName = document.getElementById('orderProductName');
  const orderProductPrice = document.getElementById('orderProductPrice');
  const orderProductImage = document.getElementById('orderProductImage');

  // image links for each product
  const productImages = {
    'tomato-soup': 'https://plus.unsplash.com/premium_photo-1722427244478-d40cfe83cc9c?q=80&w=924&auto=format&fit=crop',
    'garlic-bread': 'https://images.unsplash.com/photo-1556008531-57e6eefc7be4?q=80&w=1157&auto=format&fit=crop',
    'crispy-rolls': 'https://images.unsplash.com/photo-1667608929017-e5aa9f642be8?q=80&w=1932&auto=format&fit=crop',
    'lime-soda': 'https://images.unsplash.com/photo-1643405508958-8bae79c1f7b1?q=80&w=1170&auto=format&fit=crop',
    'cold-coffee': 'https://images.unsplash.com/photo-1625242662341-5e92c5101338?q=80&w=1170&auto=format&fit=crop',
    'lava-cake': 'https://plus.unsplash.com/premium_photo-1661266841331-e2169199de65?q=80&w=1167&auto=format&fit=crop',
    'red-velvet': 'https://via.placeholder.com/300x200?text=Red+Velvet+Slice'
  };

  document.querySelectorAll('.menu-item').forEach(item=>{
    item.addEventListener('click', ()=>{
      const slug = item.dataset.slug;
      if(singlePriceItems.includes(slug)){
        modalName.textContent = item.querySelector('.name').textContent;
        modalDesc.textContent = item.querySelector('.desc').textContent;
        modalPrice.textContent = item.querySelector('.price').textContent;
        modalImage.src = productImages[slug] || '';
        modal.style.display = 'flex';
      } else {
        goToProduct(slug);
      }
    });
  });

  function closeModal(){ modal.style.display='none'; }
  function openOrderForm(){
    orderProductName.value = modalName.textContent;
    orderProductPrice.value = modalPrice.textContent;
    orderProductImage.value = modalImage.src;
    modal.style.display='none';
    orderFormModal.style.display='flex';
  }
  function closeOrderForm(){ orderFormModal.style.display='none'; }

  // Get Current Location
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position)=>{
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;
            // Use some API or approximate to fill address fields (here just saving coordinates)
            document.getElementById('userVillage').value = "Current Location";
            document.getElementById('userCity').value = "City";
            document.getElementById('userZipcode').value = "Zipcode";
            document.getElementById('userState').value = "State";
            // optional: save lat/lon in hidden inputs if needed
            console.log("Latitude:", lat, "Longitude:", lon);
        }, ()=>{ alert('Could not get location'); });
    } else {
        alert('Geolocation not supported by your browser');
    }
}

  // fullscreen image
  const fullscreenOverlay = document.getElementById('fullscreenImageOverlay');
  const fullscreenImg = document.getElementById('fullscreenImage');
  modalImage.addEventListener('click', ()=>{
    fullscreenImg.src = modalImage.src;
    fullscreenOverlay.style.display = 'flex';
  });
  function closeFullscreenImage(){ fullscreenOverlay.style.display='none'; }

  modal.addEventListener('click',(e)=>{ if(e.target===modal) closeModal(); });
  orderFormModal.addEventListener('click',(e)=>{ if(e.target===orderFormModal) closeOrderForm(); });
  fullscreenOverlay.addEventListener('click',(e)=>{ if(e.target===fullscreenOverlay) closeFullscreenImage(); });
</script>



<script>
/* ------------------------------
   Particle animation (floating small svg food icons)
   ------------------------------ */
(function createParticles(){
  const svg = document.querySelector('.particles');

  const svgns = "http://www.w3.org/2000/svg";
  const icons = [
    '<path d="M6 2c-.55 0-1 .45-1 1v1.5C5 5.43 6.12 7 6 9 5.88 11 6 12 6 12" />', // tiny abstract
  ];
  // We'll create a few svg groups with FontAwesome-like glyphs using text nodes for simplicity
  const glyphs = ['🍕','🍔','🍟','🍩','☕','🍷'];
  const total = 18;
  for(let i=0;i<total;i++){
    const g = document.createElementNS(svgns,'text');
    g.setAttribute('class','particle');
    g.setAttribute('x', Math.random()*100 + '%');
    g.setAttribute('y', Math.random()*100 + '%');
    g.setAttribute('font-size', 22 + Math.random()*18);
    g.setAttribute('fill', 'rgba(255,215,77,0.08)');
    g.setAttribute('style', `transform-origin:center;`);
    g.textContent = glyphs[Math.floor(Math.random()*glyphs.length)];
    svg.appendChild(g);
    animateParticle(g);
  }

  function animateParticle(el){
    // CSS-free keyframe-like animation via JS: float up/down and subtle rotate
    const dx = (Math.random()-0.5) * 20;
    const dy = (Math.random()-0.5) * 20;
    let t = 0;
    function tick(){
      t += 0.006 + Math.random()*0.01;
      const x = 50 + Math.cos(t + Math.random())*30 + dx;
      const y = 50 + Math.sin(t + Math.random())*20 + dy;
      el.setAttribute('x', x + '%');
      el.setAttribute('y', y + '%');
      el.setAttribute('opacity', 0.08 + Math.abs(Math.sin(t))*0.06);
      el.setAttribute('transform', `rotate(${Math.sin(t)*10})`);
      requestAnimationFrame(tick);
    }
    tick();
  }
})();

/* ------------------------------
   Smooth scroll to category when clicking left buttons
   ------------------------------ */
document.querySelectorAll('.cat-btn').forEach(btn=>{
  btn.addEventListener('click', ()=> {
    const id = btn.dataset.target;
    const el = document.getElementById(id);
    if(el){
      el.scrollIntoView({behavior:'smooth',block:'start'});
      // tiny highlight
      el.classList.add('highlight');
      setTimeout(()=>el.classList.remove('highlight'),1200);
    }
  });
});

/* ------------------------------
   Item click → go to product page (data-slug)
   ------------------------------ */
function goToProduct(slug){
  // replace with your product detail route. For demo we show anchor to "#"
  // e.g. window.location.href = `/product/${slug}.php`;
//   window.location.href = slug + '.html'; // default demo behavior
}

/* attach click & keyboard handlers for menu items */
document.querySelectorAll('.menu-item').forEach(item=>{
  item.addEventListener('click', ()=> goToProduct(item.dataset.slug));
  item.addEventListener('keypress', (e)=> { if(e.key === 'Enter') goToProduct(item.dataset.slug); });
});

/* ------------------------------
   Search & Filter
   ------------------------------ */
const searchInput = document.getElementById('searchInput');
const clearBtn = document.getElementById('clearSearch');

searchInput.addEventListener('input', onSearch);
clearBtn.addEventListener('click', ()=>{ searchInput.value=''; onSearch(); });

function onSearch(){
  const q = (searchInput.value || '').trim().toLowerCase();
  const items = document.querySelectorAll('.menu-item');
  items.forEach(it=>{
    const name = it.querySelector('.name').textContent.toLowerCase();
    const desc = it.querySelector('.desc').textContent.toLowerCase();
    const match = q === '' || name.includes(q) || desc.includes(q);
    it.style.display = match ? 'flex' : 'none';
  });
}

/* ------------------------------
   Reveal on scroll for sections
   ------------------------------ */
const reveals = document.querySelectorAll('.reveal');
const obs = new IntersectionObserver(entries=>{
  entries.forEach(en=>{
    if(en.isIntersecting) en.target.classList.add('show');
  });
},{threshold:0.08});
reveals.forEach(r=>obs.observe(r));

/* ------------------------------
   small a11y: focus outline for keyboard users
   ------------------------------ */
document.addEventListener('keydown', (e)=>{
  if(e.key === 'Tab') document.body.classList.add('show-focus');
});
</script>
</body>
</html>
