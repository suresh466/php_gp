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
    <div class="container mt-5">
        <h1 class="text-center mb-4">Cart</h1>
        <?php foreach($cart_items as $shoe_id): ?>
            <?php $result = $shoes->get_shoe_by_id($shoe_id); ?>
            <?php if($row = mysqli_fetch_assoc($result)): ?>
                <div class="card mb-3">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <img src="products/<?php echo $row['picture']; ?>.jpg" class="card-img" alt="Product Image">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['shoe_name']; ?></h5>
                                <p class="card-text">Price: <?php echo $row['shoe_price']; ?></p>
                                <p class="card-text">Size: <?php echo $row['shoe_size']; ?></p>
                                <p class="card-text">Color: <?php echo $row['shoe_color']; ?></p>
                                <p class="card-text">Brand: <?php echo $row['shoe_brand']; ?></p>
                                <form method="post" action="">
                                    <input type="hidden" name="remove_id" value="<?php echo $shoe_id; ?>">
                                    <button type="submit" class="btn btn-danger">Remove from Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        <div class="text-center">
            <a href="checkout.php" class="btn btn-primary btn-lg">Proceed to Checkout</a>
        </div>
    </div>
    
  
</body>
</html>

