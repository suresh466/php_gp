<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoe Store - Delete</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <?php
    require 'db_conn.php';
    require 'Shoes.php';
    $db = new DatabaseConnection();
    $shoes = new Shoes($db);
    $shoe_id = null;

    // if no id provided message and exit
    if (empty($_GET['id'])) {
        echo "<p class='error'> Error! Shoe Id not found!</p>";
        exit;
    } else {
        // get the shoe id from the get request
        $shoe_id = $_GET['id'];
        $result = $shoes->delete_shoe_by_id($shoe_id);

        // if query execution is successful
        echo 'here';
        var_dump($result);
        if ($result) {
            // if delete is successful redirect to admin.php
            header("Location: admin.php");
        } else {
            echo "<p class='error'> Error! Shoe not deleted!</p>";
        }
    }
    ?>

</body>

</html>