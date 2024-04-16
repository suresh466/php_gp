<?php
session_start(); // start the session if it hasn't been started yet

require 'db_conn.php';
require 'Shoes.php';
$db = new DatabaseConnection();
$user = new Shoes($db);

if(isset($_POST['shoe_id'])){
    // check if the cart session exists, if not create one
    if(!isset($_SESSION['cart'])){
        $_SESSION['cart'] = array();
    }

    // add the product id to the cart session
    array_push($_SESSION['cart'], $_POST['shoe_id']);
}

$result = $user->get_shoes();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
</head>
<body>
    <h1>Products</h1>
    <?php
    while($row = mysqli_fetch_assoc($result)){
        echo "<h2>".$row['shoe_name']."</h2>";
        echo "<p>Price: ".$row['shoe_price']."</p>";
        echo "<p>Size: ".$row['shoe_size']."</p>";
        echo "<p>Color: ".$row['shoe_color']."</p>";
        echo "<p>Brand: ".$row['shoe_brand']."</p>";
        echo "<form method='post' action=''>";
        echo "<input type='hidden' name='shoe_id' value='".$row['shoe_id']."'>";
        echo "<input type='submit' value='Add to cart'>";
        echo "</form>";
    }
    ?>
<button><a href="cart.php">Go to Cart</a></button>
</body>
</html>