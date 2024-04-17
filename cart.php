<?php

require 'db_conn.php';
require 'Shoes.php';

session_start(); // Start the session if it hasn't been started yet
include 'navbar.php';

$db = new DatabaseConnection();
$shoes = new Shoes($db);

// Check if the cart session exists, if not create one
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = array();
}

// Check if the remove id is set in the POST request
if(isset($_POST['remove_id'])){
    if(($key = array_search($_POST['remove_id'], $_SESSION['cart'])) !== false) {
        unset($_SESSION['cart'][$key]);
    }
}

$cart_items = $_SESSION['cart'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
</head>
<body>
    <h1>Cart</h1>
    <?php
    foreach($cart_items as $shoe_id){
        $result = $shoes->get_shoe_by_id($shoe_id);
        $row = mysqli_fetch_assoc($result);
        echo "<h2>".$row['shoe_name']."</h2>";
        echo "<p>Price: ".$row['shoe_price']."</p>";
        echo "<p>Size: ".$row['shoe_size']."</p>";
        echo "<p>Color: ".$row['shoe_color']."</p>";
        echo "<p>Brand: ".$row['shoe_brand']."</p>";
        echo "<form method='post' action=''>";
        echo "<input type='hidden' name='remove_id' value='".$shoe_id."'>";
        echo "<input type='submit' value='Remove from cart'>";
        echo "</form>";
    }
    ?>
    <button><a href="checkout.php">Checkout</a></button>
</body>
</html>