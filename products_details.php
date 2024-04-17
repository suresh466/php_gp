<?php
require 'db_conn.php';
require 'Shoes.php';

include 'admin_navbar.php';

$db = new DatabaseConnection();
$shoes = new Shoes($db);
$results = $shoes->get_shoes();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoe Store</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <!-- display all shoes in a table -->
    <table>
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
                echo "<tr>";
                echo "<td>{$row['shoe_id']}</td>";
                echo "<td>{$row['shoe_name']}</td>";
                echo "<td>{$row['shoe_price']}</td>";
                echo "<td>{$row['shoe_size']}</td>";
                echo "<td>{$row['shoe_color']}</td>";
                echo "<td>{$row['shoe_brand']}</td>";
                echo "<td>{$row['category_id']}</td>";
                echo "<td><a class='action_button' id='edit' href='products_edit.php?id={$row['shoe_id']}'>Edit</a> <a class='action_button' id='delete' href='products_delete.php?id={$row['shoe_id']}'>Delete</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

</body>

</html>