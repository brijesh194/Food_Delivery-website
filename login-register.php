<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login / Register - Foodies</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #121212;
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .container {
      background: #1f1f1f;
      padding: 30px;
      border-radius: 15px;
      width: 350px;
    }
    input, button {
      width: 100%; padding: 12px; margin: 8px 0;
      border: none; border-radius: 8px; outline: none;
    }
    button {
      background: #ff4b2b; color: white; cursor: pointer;
    }
    button:hover { background: #ff1e00; }
    .toggle { text-align: center; cursor: pointer; color: #00aaff; }
    .hidden { display: none; }
    .message { text-align:center; margin:10px 0; }
  </style>
</head>
<body>
  <div class="container">
    <div id="message" class="message"></div>

    <!-- Register Form -->
    <form id="registerForm">
      <h2>Register</h2>
      <input type="text" id="reg_name" placeholder="Full Name" required>
      <input type="email" id="reg_email" placeholder="Email" required>
      <input type="password" id="reg_password" placeholder="Password" required>
      <button type="submit">Register</button>
      <p class="toggle" onclick="toggleForm()">Already have an account? Login</p>
    </form>

    <!-- Login Form -->
    <form id="loginForm" class="hidden">
      <h2>Login</h2>
      <input type="email" id="log_email" placeholder="Email" required>
      <input type="password" id="log_password" placeholder="Password" required>
      <button type="submit">Login</button>
      <p class="toggle" onclick="toggleForm()">Don't have an account? Register</p>
    </form>
  </div>

  <script>
    const registerForm = document.getElementById('registerForm');
    const loginForm = document.getElementById('loginForm');
    const message = document.getElementById('message');

    function toggleForm() {
      registerForm.classList.toggle('hidden');
      loginForm.classList.toggle('hidden');
      message.innerHTML = "";
    }

    // Register
    registerForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      let formData = new FormData();
      formData.append("action", "register");
      formData.append("name", document.getElementById('reg_name').value);
      formData.append("email", document.getElementById('reg_email').value);
      formData.append("password", document.getElementById('reg_password').value);

      let res = await fetch("auth.php", { method: "POST", body: formData });
      let data = await res.json();
      message.innerHTML = data.message;
      if (data.status === "success") {
        toggleForm(); // redirect to login form
      }
    });

    // Login
    loginForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      let formData = new FormData();
      formData.append("action", "login");
      formData.append("email", document.getElementById('log_email').value);
      formData.append("password", document.getElementById('log_password').value);

      let res = await fetch("auth.php", { method: "POST", body: formData });
      let data = await res.json();
      if (data.status === "success") {
        // ✅ Navbar ko update karo bina refresh
        localStorage.setItem("user", data.user);
        window.location.href = "index.php";
      } else {
        message.innerHTML = data.message;
      }
    });
  </script>
</body>
</html>
