<?php
include 'db.php';

if(isset($_POST['query'])){
    $search = $_POST['query'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ? LIMIT 5");
    $likeSearch = "%".$search."%";
    $stmt->bind_param("ss", $likeSearch, $likeSearch);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            echo "<div>{$row['name']}</div>";
        }
    } else {
        echo "<div>No results found</div>";
    }
}
?>
