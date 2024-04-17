<?php

require 'db_conn.php';
require 'Shoes.php';
require 'categories.php';

session_start(); // start the session if it hasn't been started yet
include 'navbar.php';

if(isset($_POST['shoe_id'])){
    // check if the cart session exists, if not create one
    if(!isset($_SESSION['cart'])){
        $_SESSION['cart'] = array();
    }

    // add the product id to the cart session
    array_push($_SESSION['cart'], $_POST['shoe_id']);
}

$db = new DatabaseConnection();
$shoes = new Shoes($db);
$category = isset($_GET['category']) ? $_GET['category'] : null; // get the category from the URL
$result = $shoes->get_shoes($category);

$categories = new Categories($db);
$categories_result = $categories->get_categories();

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
    <div class="category-filter">
    <h2>Filter by category</h2>
    <select onchange="location = this.value;">
        <option value="products.php">None</option>
        <?php
        // Get the category ID from the URL
        $selected_category_id = isset($_GET['category']) ? $_GET['category'] : null;
        while ($category = $categories_result->fetch_assoc()):
            // If the category ID from the URL matches the current category ID, add the 'selected' attribute
            $selected = $category['category_id'] == $selected_category_id ? 'selected' : '';
            ?>
            <option value="products.php?category=<?php echo $category['category_id']; ?>" <?php echo $selected; ?>>
                <?php echo $category['name']; ?>
            </option>
        <?php endwhile; ?>
    </select>
</div>
    
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<h2>" . $row['shoe_name'] . "</h2>";
        echo "<p>Price: " . $row['shoe_price'] . "</p>";
        echo "<p>Size: " . $row['shoe_size'] . "</p>";
        echo "<p>Color: " . $row['shoe_color'] . "</p>";
        echo "<p>Brand: " . $row['shoe_brand'] . "</p>";
        echo "<form method='post' action=''>";
        echo "<input type='hidden' name='shoe_id' value='" . $row['shoe_id'] . "'>";
        echo "<input type='submit' value='Add to cart'>";
        echo "</form>";
    }
    ?>
    <button><a href="cart.php">Go to Cart</a></button>
    </body>
    
    </html>