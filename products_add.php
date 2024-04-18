<?php
require 'db_conn.php';
require 'Shoes.php';
require 'Categories.php';

include 'admin_navbar.php';

$message = '';

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
    $picture = $form_data['picture'];

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = validate_form($_POST);
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p class='error'>$error</p>";
        }
    } else {
        $shoe_name = $_POST['name'];
        $shoe_price = $_POST['price'];
        $shoe_size = $_POST['size'];
        $shoe_color = $_POST['color'];
        $shoe_brand = $_POST['brand'];
        $category_id = $_POST['category_id'];
        $picture = $_POST['picture'];

        $result = $shoes->add_shoe($shoe_name, $shoe_price, $shoe_size, $shoe_color, $shoe_brand, $category_id, $picture);

        if ($result) {
            header('Location: admin.php');
            exit;
        } else {
            $message = "Failed to add shoe.";
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Add New Shoe</title>
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Add New Shoe</h2>
        <?php if (!empty($message)): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" class="form-control" id="price" name="price"
                    value="<?php echo isset($_POST['price']) ? $_POST['price'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="size">Size:</label>
                <input type="text" class="form-control" id="size" name="size"
                    value="<?php echo isset($_POST['size']) ? $_POST['size'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="color">Color:</label>
                <input type="text" class="form-control" id="color" name="color"
                    value="<?php echo isset($_POST['color']) ? $_POST['color'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="brand">Brand:</label>
                <input type="text" class="form-control" id="brand" name="brand"
                    value="<?php echo isset($_POST['brand']) ? $_POST['brand'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="category_id">Category:</label>
                <select class="form-control" id="category_id" name="category_id">
                    <?php while ($row = mysqli_fetch_assoc($categories_list)): ?>
                        <option value="<?php echo $row['category_id']; ?>" <?php echo $row['category_id'] == (isset($_POST['category_id']) ? $_POST['category_id'] : '') ? 'selected' : ''; ?>><?php echo $row['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="picture">Picture:</label>
                <input type="text" class="form-control" id="picture" name="picture"
                    value="<?php echo isset($_POST['picture']) ? $_POST['picture'] : ''; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Add</button>
        </form>
    </div>
</body>

</html>
