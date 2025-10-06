<?php
session_start();

// Admin credentials (Change as per your need)
$admin_user = "admin";
$admin_pass = "admin123";

$success = false; // flag

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if($username === $admin_user && $password === $admin_pass){
        $_SESSION['admin_logged_in'] = true;
        $success = true; // login successful
    } else {
        $error = "❌ Invalid Username or Password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login</title>
<style>
*{
    margin:0; padding:0;
    box-sizing:border-box;
    font-family: 'Poppins', sans-serif;
}
body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg,#ff6b6b,#ffb347);
    overflow:hidden;
}
.container{
    display:flex;
    width:950px;
    height:580px;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(0,0,0,0.3);
    animation:fadeIn 1.2s ease;
}
.left{
    flex:1.2;
    background:url('https://images.unsplash.com/photo-1600891964599-f61ba0e24092?auto=format&fit=crop&w=1000&q=80') no-repeat center/cover;
    position:relative;
    display:flex;
    justify-content:center;
    align-items:center;
    color:white;
    padding:40px;
}
.left::after{
    content:"";
    position:absolute;
    top:0;left:0;
    width:100%;height:100%;
    background:rgba(0,0,0,0.55);
}
.left-content{
    position:relative;
    z-index:2;
    text-align:center;
}
.left-content h1{
    font-size:40px;
    margin-bottom:15px;
}
.left-content p{
    font-size:18px;
    line-height:1.6;
}
.right{
    flex:1;
    backdrop-filter:blur(20px);
    background:rgba(255,255,255,0.25);
    display:flex;
    justify-content:center;
    align-items:center;
    flex-direction:column;
    padding:50px;
    animation:slideIn 1s ease;
}
.right h2{
    margin-bottom:25px;
    color:#222;
    font-size:28px;
    font-weight:600;
}
form{
    width:100%;
    max-width:320px;
    position:relative;
}
.input-group{
    position:relative;
    margin-bottom:20px;
}
.input-group input{
    width:100%;
    padding:14px;
    border:none;
    border-radius:12px;
    background:rgba(255,255,255,0.8);
    font-size:15px;
    outline:none;
    transition:0.3s;
}
.input-group label{
    position:absolute;
    top:50%;
    left:15px;
    transform:translateY(-50%);
    color:#777;
    pointer-events:none;
    transition:0.3s;
}
.input-group input:focus + label,
.input-group input:valid + label{
    top:-5px;
    left:10px;
    font-size:12px;
    color:#ff6b6b;
    background:white;
    padding:0 5px;
    border-radius:5px;
}
button{
    width:100%;
    padding:14px;
    border:none;
    border-radius:12px;
    background:linear-gradient(135deg,#ff6b6b,#ffb347);
    color:white;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
    transition:0.4s;
    box-shadow:0 4px 15px rgba(0,0,0,0.2);
}
button:hover{
    background:linear-gradient(135deg,#ff4d4d,#ff9800);
    transform:translateY(-2px);
}
.error{
    color:#ff2222;
    text-align:center;
    margin-bottom:10px;
    font-weight:500;
}
.loader-container{
    position:fixed;
    top:0;left:0;
    width:100%;height:100%;
    background:#fff;
    display:none; /* hidden by default */
    flex-direction:column;
    justify-content:center;
    align-items:center;
    font-size:20px;
    color:#333;
    z-index:1000;
}
.loader{
    width:70px;
    height:70px;
    border:8px solid #ddd;
    border-top:8px solid #ff6b6b;
    border-radius:50%;
    animation:spin 1s linear infinite;
    margin-bottom:15px;
}
@keyframes spin{100%{transform:rotate(360deg);}}
@keyframes fadeIn{from{opacity:0;transform:scale(0.9);}to{opacity:1;transform:scale(1);}}
@keyframes slideIn{from{transform:translateX(100px);opacity:0;}to{transform:translateX(0);opacity:1;}}
</style>
</head>
<body>
<div class="container login-container">
    <div class="left">
        <div class="left-content">
            <h1>🍔 Food Admin</h1>
            <p>“Delicious food at your fingertips – Manage orders smartly.”</p>
        </div>
    </div>
    <div class="right">
        <h2>Admin Login</h2>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <div class="input-group">
                <input type="text" name="username" required>
                <label>👤 Username</label>
            </div>
            <div class="input-group">
                <input type="password" name="password" required>
                <label>🔑 Password</label>
            </div>
            <button type="submit" name="login">Login</button>
        </form>
    </div>
</div>

<!-- Loader -->
<div class="loader-container" id="loaderBox">
    <div class="loader"></div>
    <p>Loading Admin Panel...</p>
</div>

<?php if($success): ?>
<script>
    // Show loader
    document.getElementById('loaderBox').style.display = "flex";
    // Redirect after 5s
    setTimeout(function(){
        window.location.href = "admin_dashboard.php";
    }, 5000);
</script>
<?php endif; ?>
</body>
</html>
