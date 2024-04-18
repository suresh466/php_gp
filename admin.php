<?php
require 'db_conn.php';
require 'Shoes.php';
require 'Categories.php';

include 'admin_navbar.php';

$db = new DatabaseConnection();
$shoes = new Shoes($db);
$results = $shoes->get_shoes();
$categories = new Categories($db);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoe Store</title>
    
</head>
<body>


    <div class="container mt-5">
        <h2 class="text-center mb-4">Shoe Inventory</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Size</th>
                        <th>Color</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($results)) {
                        $category_result = $categories->get_category_by_id($row['category_id']);
                        $category = mysqli_fetch_assoc($category_result);
                        echo "<tr>";
                        echo "<td>{$row['shoe_id']}</td>";
                        echo "<td>{$row['shoe_name']}</td>";
                        echo "<td>{$row['shoe_price']}</td>";
                        echo "<td>{$row['shoe_size']}</td>";
                        echo "<td>{$row['shoe_color']}</td>";
                        echo "<td>{$row['shoe_brand']}</td>";
                        echo "<td>{$category['name']}</td>";
                        echo "<td><a class='btn btn-primary' href='products_edit.php?id={$row['shoe_id']}'>Edit</a> <a class='btn btn-danger' href='products_delete.php?id={$row['shoe_id']}'>Delete</a></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
