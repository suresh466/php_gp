<?php
require 'db_conn.php';
require 'Shoes.php';
require 'Categories.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = new DatabaseConnection();
    $shoes = new Shoes($db);

    $shoe_name = $_POST['name'];
    $shoe_price = $_POST['price'];
    $shoe_size = $_POST['size'];
    $shoe_color = $_POST['color'];
    $shoe_brand = $_POST['brand'];
    $category_id = $_POST['category'];

    $result = $shoes->add_shoe($shoe_name, $shoe_price, $shoe_size, $shoe_color, $shoe_brand, $category_id);

    if ($result) {
        $message = "Shoe added successfully";
    } else {
        $message = "Failed to add shoe";
    }
}

$db = new DatabaseConnection();
$categories = new Categories($db);
$categories_list = $categories->get_categories();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Shoe</title>
</head>
<body>
    <?php if (!empty($message)) : ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form action="" method="post">
        <label for="name">Shoe Name:</label><br>
        <input type="text" id="name" name="name"><br>
        <label for="price">Price:</label><br>
        <input type="text" id="price" name="price"><br>
        <label for="size">Size:</label><br>
        <input type="text" id="size" name="size"><br>
        <label for="color">Color:</label><br>
        <input type="text" id="color" name="color"><br>
        <label for="brand">Brand:</label><br>
        <input type="text" id="brand" name="brand"><br>
        <label for="category">Category:</label><br>
        <select id="category" name="category">
            <?php while ($row = mysqli_fetch_assoc($categories_list)) : ?>
                <option value="<?php echo $row['category_id']; ?>"><?php echo $row['name']; ?></option>
            <?php endwhile; ?>
        </select><br>
        <input type="submit" value="Add Shoe">
    </form>
</body>
</html>