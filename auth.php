<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db = "food_delivery";

$conn = new mysqli($host, $user, $pass, $db);

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $error = "Email already registered!";
    } else {
        $conn->query("INSERT INTO users (name, email, password) VALUES ('$name','$email','$password')");
        $_SESSION['register_success'] = "Registration successful! Please login now.";
        header("Location: auth.php?login=1");
        exit;
    }
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $res = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user'] = $row['name'];
            $_SESSION['login_success'] = true;
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid Password!";
        }
    } else {
        $error = "User not found!";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login / Register - Foodies</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
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
body {
  font-family: 'Poppins', sans-serif;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background: linear-gradient(-45deg, #ff4b2b, #1f1c2c, #3a1c71, #ff6a00);
  background-size: 400% 400%;
  animation: gradientBG 12s ease infinite;
  overflow: hidden;
}

@keyframes gradientBG {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

.container {
  background: rgba(255, 255, 255, 0.08);
  backdrop-filter: blur(20px);
  border-radius: 20px;
  padding: 40px 30px;
  width: 380px;
  box-shadow: 0 8px 32px var(--accent-2);
  animation: fadeIn 1s ease forwards;
  transform: translateY(30px);
  opacity: 0;
}

@keyframes fadeIn {
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

h2 {
  text-align: center;
  margin-bottom: 25px;
  font-weight: 700;
  color: #fff;
  text-shadow: 0 0 10px rgba(255, 215, 0, 0.6);
}

input {
  width: 100%;
  padding: 14px;
  margin: 10px 0;
  margin-left: -10px;
  border: none;
  border-radius: 12px;
  outline: none;
  background: rgba(255, 255, 255, 0.1);
  color: #fff;
  font-size: 15px;
  transition: 0.3s ease;
}

input:focus {
  background: rgba(255, 255, 255, 0.2);
  box-shadow: 0 0 10px #ffd700;
}

/* Button Glow Animation */
button {
  width: 100%;
  padding: 14px;
  margin-top: 15px;
  border-radius: 12px;
  border: none;
  background: linear-gradient(135deg, #ffd700, #ffae00);
  color: #121212;
  font-weight: 700;
  font-size: 16px;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4);
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0% { box-shadow: 0 0 15px #ffd700; }
  50% { box-shadow: 0 0 30px #ffd700; }
  100% { box-shadow: 0 0 15px #ffd700; }
}

button:hover {
  transform: scale(1.05);
}

.toggle {
  text-align: center;
  margin-top: 18px;
  cursor: pointer;
  color: #40c4ff;
  transition: color 0.3s ease;
}

.toggle:hover {
  color: #ffd700;
}

.error {
  color: #ff4b2b;
  text-align: center;
  font-weight: 500;
}

.success {
  color: #00ff88;
  text-align: center;
  font-weight: 500;
}

  .hidden {
    display: none;
  }

  @keyframes fadeIn {
    0% { opacity: 0; transform: translateY(-10px);}
    100% { opacity: 1; transform: translateY(0);}
  }
</style>
</head>
<body>
  <div class="container">
    <?php 
      if (isset($error)) echo "<p class='error'>$error</p>"; 
      if (isset($_SESSION['register_success'])) {
        echo "<p class='success'>".$_SESSION['register_success']."</p>";
        unset($_SESSION['register_success']);
      }
    ?>

    <form method="POST" id="registerForm" <?php if(isset($_GET['login'])) echo 'class="hidden"'; ?>>
      <h2>Register</h2>
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" name="register">Register</button>
      <p class="toggle" onclick="toggleForm()">Already have an account? Login</p>
    </form>

    <form method="POST" id="loginForm" <?php if(!isset($_GET['login'])) echo 'class="hidden"'; ?>>
      <h2>Login</h2>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" name="login">Login</button>
      <p class="toggle" onclick="toggleForm()">Don't have an account? Register</p>
    </form>
  </div>

<script>
function toggleForm() {
  document.getElementById('registerForm').classList.toggle('hidden');
  document.getElementById('loginForm').classList.toggle('hidden');
}
</script>
</body>
</html>
