<?php

// session_start();

// if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0){
//     echo "<h2>Your Cart</h2><table border='1'>
//     <tr><th>Product</th><th>Qty</th><th>Price</th><th>Total</th></tr>";

//     $grand_total = 0;
//     foreach($_SESSION['cart'] as $item){
//         $total = $item['price'] * $item['quantity'];
//         $grand_total += $total;

//         echo "<tr>
//             <td>{$item['name']}</td>
//             <td>{$item['quantity']}</td>
//             <td>₹{$item['price']}</td>
//             <td>₹$total</td>
//         </tr>";
//     }
//     echo "<tr><td colspan='3'>Grand Total</td><td>₹$grand_total</td></tr></table>";
//     echo "<a href='order.php'>Place Order</a>";
// } else {
//     echo "<p>Cart is empty!</p>";
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cart - Foodie Premium</title>
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
.hamburger{display:none;font-size:22px;border:1px solid var(--glass);border-radius:10px;padding:8px}

/* ===== Mobile Drawer ===== */
.drawer{display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:999}
.drawer .panel{position:absolute;right:0;top:0;height:100%;width:85%;max-width:360px;background:#121219;border-left:1px solid var(--glass);padding:18px;overflow:auto}
.drawer .panel .search{margin:10px 0}

/* CART WRAPPER */
.cart-wrapper{
  max-width:1200px;
  margin:70px auto;
  display:flex;
  gap:30px;
  flex-wrap:wrap;
}

/* CART ITEMS */
.cart-items{
  flex:2;
  display:flex;
  flex-direction:column;
  gap:20px;
  animation:fadeIn 0.7s ease-in-out;
}
@keyframes fadeIn{from{opacity:0;transform:translateY(20px);}to{opacity:1;transform:translateY(0);}}

/* CART ITEM CARD */
.cart-item{
  display:flex;align-items:center;justify-content:space-between;
  background: var(--card);padding:20px;border-radius:20px;
  box-shadow:0 6px 25px rgba(0,0,0,0.5);
  transition:0.3s ease;
}
.cart-item:hover{transform:scale(1.02);box-shadow:0 10px 30px rgba(0,0,0,0.6);}
.cart-item img{width:120px;height:120px;object-fit:cover;border-radius:16px;}
.cart-info{flex:1;margin-left:20px;animation:fadeIn 0.5s ease;}
.cart-info h3{margin-bottom:5px;font-size:20px;}
.cart-info p{color:var(--text-gray);margin-bottom:8px;}

/* QUANTITY SELECTOR */
.quantity{
  display:flex;align-items:center;gap:10px;margin-top:5px;
}
.quantity button{
  background:var(--accent);
  border:none;
  padding:6px 12px;
  border-radius:10px;
  color:#fff;
  font-weight:bold;
  cursor:pointer;
  transition:0.3s ease;
}
.quantity button:hover{transform:scale(1.1);}
.quantity span{
  min-width:35px;text-align:center;font-weight:bold;font-size:16px;
  transition:0.2s ease;
}

/* ACTION BUTTONS */
.cart-actions{display:flex;flex-direction:column;gap:10px;}
.remove-btn,.order-btn{
  border:none;padding:10px 15px;border-radius:14px;color:#fff;font-weight:bold;cursor:pointer;transition:0.3s ease;
}
.remove-btn{background:linear-gradient(45deg,var(--accent),var(--accent-2));}
.remove-btn:hover{transform:scale(1.1);box-shadow:0 6px 20px rgba(255,75,43,0.6);}
.order-btn{background:linear-gradient(45deg,var(--ok),#1db954);}
.order-btn:hover{transform:scale(1.05);box-shadow:0 6px 20px rgba(30,215,96,0.5);}

/* CART SUMMARY SIDEBAR */
.cart-summary{
  flex:1;
  background:var(--card);
  padding:25px;
  border-radius:20px;
  box-shadow:0 6px 25px rgba(0,0,0,0.4);
  position:sticky;
  top:80px;
  height:max-content;
  animation:fadeIn 0.7s ease-in-out;
}
.cart-summary h2{margin-bottom:20px;}
.cart-summary p{display:flex;justify-content:space-between;margin:10px 0;}
.cart-summary hr{border:0;border-top:1px solid #333;}
.checkout-btn{
  width:100%;
  margin-top:20px;
  padding:14px;
  border:none;
  border-radius:16px;
  background:linear-gradient(45deg,var(--accent),var(--accent-2));
  color:#fff;font-size:16px;font-weight:bold;
  cursor:pointer;
  transition:0.3s ease;
}
.checkout-btn:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(255,75,43,0.6);}

/* EMPTY CART */
.empty-cart{text-align:center;padding:60px;}
.empty-cart img{width:200px;margin-bottom:25px;animation:float 2s infinite ease-in-out;}
@keyframes float{0%,100%{transform:translateY(0);}50%{transform:translateY(-10px);}}
.empty-cart a{
  display:inline-block;margin-top:20px;padding:12px 20px;background:linear-gradient(45deg,var(--accent),var(--accent2));
  border-radius:16px;color:#fff;text-decoration:none;transition:0.3s ease;
}
.empty-cart a:hover{transform:scale(1.05);}

/* ===== Footer ===== */
footer{margin-top:40px;border-top:1px solid var(--glass);background:#0e0e11}
.f-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:16px;padding:28px 0;color:#c9cbd1}
footer a{color:#c9cbd1}
footer a:hover{color:#fff}

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
/* RESPONSIVE */
@media(max-width:992px){.cart-wrapper{flex-direction:column;}}
@media(max-width:768px){
  .cart-item{flex-direction:column;align-items:flex-start;}
  .cart-item img{margin-bottom:15px;}
  .cart-actions{flex-direction:row;width:100%;justify-content:space-between;}
  .cart-summary{position:relative;top:0;}
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

<!-- CART WRAPPER -->
<div class="cart-wrapper">
  <div id="cart-items" class="cart-items"></div>

  <div class="cart-summary" id="cart-summary" style="display:none;">
    <h2>Order Summary</h2>
    <p><span>Subtotal</span> <span id="subtotal">₹0</span></p>
    <p><span>Delivery</span> <span>₹40</span></p>
    <p><span>Tax (5%)</span> <span id="tax">₹0</span></p>
    <hr>
    <p><strong>Total</strong> <strong id="total">₹0</strong></p>
    <button class="checkout-btn" onclick="checkoutAll()">Proceed to Checkout</button>
  </div>
</div>

<div style="text-align:center; margin-top:20px;">
  <a href="order_status.php" class="track-btn">Track My Order</a>
</div>

<style>
  .track-btn {
    display:inline-block;
    padding:12px 20px;
    background:linear-gradient(45deg,var(--accent),var(--accent-2));
    color:#fff;
    border-radius:10px;
    font-weight:600;
    text-decoration:none;
    transition:0.3s;
  }
  .track-btn:hover {
    transform:translateY(-2px);
    box-shadow:0 6px 18px rgba(0,0,0,0.2);
  }
</style>

<!-- ===== FOOTER ===== -->
<footer>
  <div class="container f-grid">
    <div>
      <div class="brand"><i class="fa-solid fa-pizza-slice"></i> Foodie</div>
      <p class="muted" style="margin-top:8px">Order from the best restaurants near you. Fast delivery, secure payments, great offers.</p>
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
      <a href="#" class="btn"><i class="fa-brands fa-facebook"></i></a>
      <a href="#" class="btn"><i class="fa-brands fa-instagram"></i></a>
      <a href="#" class="btn"><i class="fa-brands fa-x-twitter"></i></a>
    </div>
  </div>
</footer>

<button id="toTop" aria-label="Back to top"><i class="fa-solid fa-arrow-up"></i></button>


<script>
let cart = JSON.parse(localStorage.getItem("cart")) || [];
cart.forEach(item => { if(!item.quantity) item.quantity=1; });

function renderCart(){
  const container = document.getElementById("cart-items");
  const summary = document.getElementById("cart-summary");
  container.innerHTML="";

  if(cart.length===0){
    summary.style.display="none";
    container.innerHTML=`
      <div class="empty-cart">
        <img src="https://cdn-icons-png.flaticon.com/512/2037/2037454.png" alt="Empty Cart">
        <h2>Your cart is empty!</h2>
        <a href="index.php">🍔 Browse Food</a>
      </div>`;
    return;
  }

  let subtotal=0;
  cart.forEach((item,index)=>{
    subtotal+=parseFloat(item.price)*item.quantity;
    container.innerHTML+=`
      <div class="cart-item">
        <img src="${item.img}" alt="${item.name}">
        <div class="cart-info">
          <h3>${item.name}</h3>
          <p>₹${item.price}</p>
          <div class="quantity">
            <button onclick="changeQty(${index},-1)">-</button>
            <span>${item.quantity}</span>
            <button onclick="changeQty(${index},1)">+</button>
          </div>
        </div>
        <div class="cart-actions">
          <button class="order-btn" onclick="orderItem(${index})">Order Now</button>
          <button class="remove-btn" onclick="removeItem(${index})">Remove</button>
        </div>
      </div>`;
  });

  let tax=(subtotal*0.05).toFixed(2);
  let total=subtotal+40+parseFloat(tax);
  document.getElementById("subtotal").innerText="₹"+subtotal.toFixed(2);
  document.getElementById("tax").innerText="₹"+tax;
  document.getElementById("total").innerText="₹"+total.toFixed(2);
  summary.style.display="block";
  localStorage.setItem("cart",JSON.stringify(cart));
}

function changeQty(index,delta){
  cart[index].quantity+=delta;
  if(cart[index].quantity<1) cart[index].quantity=1;
  renderCart();
}

function removeItem(index){
  cart.splice(index,1);
  renderCart();
}

function orderItem(index){
  const item=cart[index];
  alert("Order Placed: "+item.name+"\nQuantity: "+item.quantity+"\nTotal: ₹"+(item.price*item.quantity).toFixed(2));
  cart.splice(index,1);
  renderCart();
}

function checkoutAll(){
  if(cart.length===0){alert("Cart is empty!"); return;}
  let subtotal=cart.reduce((acc,item)=>acc+(item.price*item.quantity),0);
  let total=subtotal+40+(subtotal*0.05);
  alert("Order Placed!\nTotal Amount: ₹"+total.toFixed(2));
  cart=[];
  renderCart();
}

renderCart();
</script>

<script>
function orderItem(index){
  const item = cart[index];

  // Selected product ko checkoutItem me save karo
  localStorage.setItem("checkoutItem", JSON.stringify(item));

  // Cart se remove
  cart.splice(index, 1);
  localStorage.setItem("cart", JSON.stringify(cart));
  renderCart(); // UI update

  // Checkout page pe redirect
  window.location.href = "checkout.php";
}


</script>


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
function updateCartCount() {
  document.getElementById("cart-count").innerText = cart.length;
}

document.querySelectorAll(".add-to-cart").forEach(btn => {
  btn.addEventListener("click", () => {
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
