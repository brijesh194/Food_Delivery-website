<?php
$servername = "localhost";
$username = "root";   // apna username
$password = "";       // apna password
$dbname = "food_delivery";  // apna database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Connection check
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Request check
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $img = $_POST['img'] ?? '';

    if (!empty($id) && !empty($name) && !empty($price)) {
        $stmt = $conn->prepare("INSERT INTO cart (product_id, product_name, price, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isis", $id, $name, $price, $img);

        if ($stmt->execute()) {
            echo "✅ Product added to cart!";
        } else {
            echo "❌ Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "❌ Missing product data!";
    }
}
$conn->close();
?>
