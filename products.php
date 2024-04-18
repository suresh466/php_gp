<?php

require 'db_conn.php';
require 'Shoes.php';
require 'categories.php';

session_start(); // start the session if it hasn't been started yet
include 'navbar.php';

// Check if a category is selected
$selected_category_id = null;
if (isset($_POST['category'])) {
    // Set a cookie with the selected category
    setcookie('selected_category', $_POST['category'], time() + (86400 * 30), "/"); // 86400 = 1 day
    $selected_category_id = $_POST['category'];
} elseif (isset($_COOKIE['selected_category'])) {
    $selected_category_id = $_COOKIE['selected_category'];
}

$db = new DatabaseConnection();
$shoes = new Shoes($db);
$result = $shoes->get_shoes($selected_category_id);

if(isset($_POST['shoe_id'])){
    // check if the cart session exists, if not create one
    if(!isset($_SESSION['cart'])){
        $_SESSION['cart'] = array();
    }
    // add the product id to the cart session
    array_push($_SESSION['cart'], $_POST['shoe_id']);
}

$categories = new Categories($db);
$categories_result = $categories->get_categories();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>

    
    <style>
        .product-card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <!-- Products Heading -->
        <h1 class="mb-4">Products</h1>
        
        <!-- Filter Row -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="category-filter">
                    <h2>Filter by category</h2>
                    <form method="post" action="">
                        <select name="category" class="form-control" onchange="this.form.submit()">
                            <option value="">None</option>
                            <?php while ($category = $categories_result->fetch_assoc()): ?>
                                <?php $selected = $category['category_id'] == $selected_category_id ? 'selected' : ''; ?>
                                <option value="<?php echo $category['category_id']; ?>" <?php echo $selected; ?>>
                                    <?php echo $category['name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Product Cards -->
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4">
                    <div class="card product-card">
                        <!-- Product Image -->
                        <img src="products/<?php echo $row['picture']; ?>.jpg" class="card-img-top" alt="Product Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['shoe_name']; ?></h5>
                            <p class="card-text">Price: <?php echo $row['shoe_price']; ?></p>
                            <p class="card-text">Size: <?php echo $row['shoe_size']; ?></p>
                            <p class="card-text">Color: <?php echo $row['shoe_color']; ?></p>
                            <p class="card-text">Brand: <?php echo $row['shoe_brand']; ?></p>
                            <form method="post" action="">
                                <input type="hidden" name="shoe_id" value="<?php echo $row['shoe_id']; ?>">
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        
        <!-- Button to Cart Page -->
        <div class="text-center mt-4">
            <a href="cart.php" class="btn btn-secondary btn-lg">Go to Cart</a>
        </div>
    </div>
    
    
</body>
</html>
