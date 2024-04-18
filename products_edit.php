<?php
require('db_conn.php');
require('Shoes.php');
require('Categories.php');

$db = new DatabaseConnection();
$shoes = new Shoes($db);
$categories = new Categories($db);
$categories_list = $categories->get_categories(); 

function validate_form($form_data)
{
    $name = $form_data['name'];
    $price = $form_data['price'];
    $size = $form_data['size'];
    $color = $form_data['color'];
    $brand = $form_data['brand'];
    $category_id = $form_data['category_id'];

    $errors = [];

    if (empty($name)) {
        $errors[] = "Name is required";
    } else {
        if (!is_string($name)) {
            $errors[] = "Name can only be a string";
        }
    }

    if (empty($color)) {
        $errors[] = "Color is required";
    } else {
        if (!is_text_only($color)) {
            $errors[] = "Color can only contain letters and spaces";
        }
    }

    if (empty($size)) {
        $errors[] = "Size is required";
    } else {
        if (!filter_var($size, FILTER_VALIDATE_INT)) {
            $errors[] = "Size must be an integer";
        }
    }

    if (empty($brand)) {
        $errors[] = "Brand is required";
    } else {
        if (!is_string($brand)) {
            $errors[] = "Brand can only be a string";
        }
    }

    if (empty($category_id)) {
        $errors[] = "Category ID is required";
    } else {
        if (!filter_var($category_id, FILTER_VALIDATE_INT)) {
            $errors[] = "Category ID must be an integer";
        }
    }

    if (empty($price)) {
        $errors[] = "Price is required";
    } else if (!preg_match('/^\d+(\.\d{1,2})?$/', $price)) {
        $errors[] = "Price must be a valid positive number with a maximum of two decimal places";
    }

    return $errors;
}

function is_text_only($input)
{
    return !preg_match("/[^a-zA-Z- ]/", $input);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = validate_form($_POST);
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p class='error'>$error</p>";
        }
    } else {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $size = $_POST['size'];
        $color = $_POST['color'];
        $brand = $_POST['brand'];
        $category_id = $_POST['category_id'];

        $shoes->update_shoe_by_id($id, $name, $price, $size, $color, $brand, $category_id);
        header('Location: admin.php');
        exit;
    }
}

if (!isset($_GET['id']) && !isset($_POST['id'])) {
    echo "<p class='error'>Error! Shoe Id not found.</p>";
    exit;
} else {
    $id = $_SERVER['REQUEST_METHOD'] == 'POST' ? $_POST['id'] : $_GET['id'];
    $result = $shoes->get_shoe_by_id($id);
    $shoe = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] != 'GET' && $_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "The script only works with get and post requests.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Shoe</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $shoe['shoe_id']; ?>">
        <label>Name: <input type="text" name="name" value="<?php echo $shoe['shoe_name']; ?>"></label>
        <label>Price: <input type="text" name="price" value="<?php echo $shoe['shoe_price']; ?>"></label>
        <label>Size: <input type="text" name="size" value="<?php echo $shoe['shoe_size']; ?>"></label>
        <label>Color: <input type="text" name="color" value="<?php echo $shoe['shoe_color']; ?>"></label>
        <label>Brand: <input type="text" name="brand" value="<?php echo $shoe['shoe_brand']; ?>"></label>
        <label>Category: 
            <select name="category_id"> 
                <?php while ($row = mysqli_fetch_assoc($categories_list)) : ?>
                    <option value="<?php echo $row['category_id']; ?>" <?php echo $row['category_id'] == $shoe['category_id'] ? 'selected' : ''; ?>><?php echo $row['name']; ?></option>
                <?php endwhile; ?>
            </select>
        </label>
        <input type="submit" value="Update">
    </form>
</body>
</html>