<?php
require 'Shoes.php';
require 'db_conn.php';
require 'Order.php';
require 'OrderItem.php';
require 'Users.php';

session_start();
include 'navbar.php';


$db = new DatabaseConnection();
$shoes = new Shoes($db);

// Define variables and initialize with empty values
$firstName = $lastName = $phoneNumber = $creditCardNumber = $cvv = $street = $city = $province = $zip = "";
$cartErr = $firstNameErr = $lastNameErr = $phoneNumberErr = $creditCardNumberErr = $cvvErr = $streetErr = $cityErr = $provinceErr = $zipErr = "";

// check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: register.php");
    exit;
} else {
    $users = new Users($db);
    $user = $users->get_user_by_id($_SESSION['user_id']);
    if ($user) {
        $user = $user->fetch_assoc();
        $firstName = $user['first_name'];
        $lastName = $user['last_name'];
        $phoneNumber = $user['phone'];
        $province = $user['province'];
    }
}
// Check if cart is empty
if (empty($_SESSION['cart'])) {
    $cartErr = "Your cart is empty. Please add items before checking out.";
    return;
}

// Calculate total price of items in the cart 
$total_price = 0;
foreach ($_SESSION['cart'] as $shoe_id) {
    $result = $shoes->get_shoe_by_id($shoe_id);
    $row = mysqli_fetch_assoc($result);
    $total_price += $row['shoe_price'];
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate firstName
    if (empty(trim($_POST["firstName"]))) {
        $firstNameErr = "Please enter your First Name.";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", trim($_POST["firstName"]))) {
        $firstNameErr = "Invalid First Name, only alphabets and spaces are allowed.";
    } else {
        $firstName = trim($_POST["firstName"]);
    }

    // Validate lastName
    if (empty(trim($_POST["lastName"]))) {
        $lastNameErr = "Please enter your Last Name.";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", trim($_POST["lastName"]))) {
        $lastNameErr = "Invalid Last Name, only alphabets and spaces are allowed.";
    } else {
        $lastName = trim($_POST["lastName"]);
    }

    // Validate phoneNumber
    if (empty(trim($_POST["phoneNumber"]))) {
        $phoneNumberErr = "Please enter your Phone Number.";
    } elseif (!preg_match("/^[0-9]{10}$/", trim($_POST["phoneNumber"]))) {
        $phoneNumberErr = "Invalid Phone Number, only 10 digits are allowed.";
    } else {
        $phoneNumber = trim($_POST["phoneNumber"]);
    }

    // Validate creditCardNumber
    if (empty(trim($_POST["creditCardNumber"]))) {
        $creditCardNumberErr = "Please enter your Credit Card Number.";
    } elseif (!preg_match("/^[0-9]{16}$/", trim($_POST["creditCardNumber"]))) {
        $creditCardNumberErr = "Invalid Credit Card Number, only 16 digits are allowed.";
    } else {
        $creditCardNumber = trim($_POST["creditCardNumber"]);
    }

    // Validate cvv
    if (empty(trim($_POST["cvv"]))) {
        $cvvErr = "Please enter your CVV.";
    } elseif (!preg_match("/^[0-9]{3}$/", trim($_POST["cvv"]))) {
        $cvvErr = "Invalid CVV, only 3 digits are allowed.";
    } else {
        $cvv = trim($_POST["cvv"]);
    }

    // Validate street
    if (empty(trim($_POST["street"]))) {
        $streetErr = "Please enter your Street.";
    } else {
        $street = trim($_POST["street"]);
    }

    // Validate city
    if (empty(trim($_POST["city"]))) {
        $cityErr = "Please enter your City.";
    } else {
        $city = trim($_POST["city"]);
    }

    // Validate province
    if (empty(trim($_POST["province"]))) {
        $provinceErr = "Please select your Province.";
    } else {
        $province = trim($_POST["province"]);
    }

    // Validate zip
    if (empty(trim($_POST["zip"]))) {
        $zipErr = "Please enter your Zip.";
    } elseif (!preg_match("/^[a-zA-Z0-9]{5,6}$/", trim($_POST["zip"]))) {
        $zipErr = "Invalid Zip, only 5 to 6 digits and chars are allowed.";
    } else {
        $zip = trim($_POST["zip"]);
    }


    // Check input errors before inserting in database
    if (empty($firstNameErr) && empty($lastNameErr) && empty($phoneNumberErr) && empty($creditCardNumberErr) && empty($cvvErr) && empty($streetErr) && empty($cityErr) && empty($stateErr) && empty($zipErr)) {
        // insert into order and orderitem tables
        $db = new DatabaseConnection();
        $order = new Order($db);
        $orderItem = new OrderItem($db);

        // Calculate total price of items in the cart

        // Add order
        $user_id = $_SESSION['user_id'];
        $order->add_order($user_id, $total_price);

        // Get the id of the newly created order
        $order_id = mysqli_insert_id($db->get_dbc());

        // Add order items
        foreach ($_SESSION['cart'] as $shoe_id) {
            $orderItem->add_order_item($order_id, $shoe_id, 1); // Assuming quantity is 1 for each item
        }

        // Clear the cart
        $_SESSION['cart'] = array();
        header("Location: confirmation.php");
        exit;
    }
}
?>

<div class="container">
    <h2 class="text-center mb-4">Checkout</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group row">
            <label for="firstName" class="col-sm-3 col-form-label">First Name</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="firstName" name="firstName"
                    value="<?php echo $firstName; ?>">
                <span class="text-danger"><?php echo $firstNameErr; ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="lastName" class="col-sm-3 col-form-label">Last Name</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo $lastName; ?>">
                <span class="text-danger"><?php echo $lastNameErr; ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="phoneNumber" class="col-sm-3 col-form-label">Phone Number</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber"
                    value="<?php echo $phoneNumber; ?>">
                <span class="text-danger"><?php echo $phoneNumberErr; ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="creditCardNumber" class="col-sm-3 col-form-label">Credit Card Number</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="creditCardNumber" name="creditCardNumber"
                    value="<?php echo $creditCardNumber; ?>">
                <span class="text-danger"><?php echo $creditCardNumberErr; ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="cvv" class="col-sm-3 col-form-label">CVV</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="cvv" name="cvv" value="<?php echo $cvv; ?>">
                <span class="text-danger"><?php echo $cvvErr; ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="street" class="col-sm-3 col-form-label">Street</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="street" name="street" value="<?php echo $street; ?>">
                <span class="text-danger"><?php echo $streetErr; ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="city" class="col-sm-3 col-form-label">City</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="city" name="city" value="<?php echo $city; ?>">
                <span class="text-danger"><?php echo $cityErr; ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="province" class="col-sm-3 col-form-label">Province</label>
            <div class="col-sm-9">
                <select class="form-control" id="province" value="<?php echo $province; ?>" name="province">
                <option value="">Select Province</option>
            <option value="AB" <?php if ($province == "AB") echo "selected"; ?>>Alberta</option>
            <option value="BC" <?php if ($province == "BC") echo "selected"; ?>>British Columbia</option>
            <option value="MB" <?php if ($province == "MB") echo "selected"; ?>>Manitoba</option>
            <option value="NB" <?php if ($province == "NB") echo "selected"; ?>>New Brunswick</option>
            <option value="NL" <?php if ($province == "NL") echo "selected"; ?>>Newfoundland and Labrador</option>
            <option value="NS" <?php if ($province == "NS") echo "selected"; ?>>Nova Scotia</option>
            <option value="ON" <?php if ($province == "ON") echo "selected"; ?>>Ontario</option>
            <option value="PE" <?php if ($province == "PE") echo "selected"; ?>>Prince Edward Island</option>
            <option value="QC" <?php if ($province == "QC") echo "selected"; ?>>Quebec</option>
            <option value="SK" <?php if ($province == "SK") echo "selected"; ?>>Saskatchewan</option>
                </select>
                <span class="text-danger"><?php echo $provinceErr; ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="zip" class="col-sm-3 col-form-label">Zip</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="zip" name="zip" value="<?php echo $zip; ?>">
                <span class="text-danger"><?php echo $zipErr; ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label"><b>Total Price</b></label>
            <div class="col-sm-9 pt-2">
                <b><span><?php echo $total_price; ?></span></b>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-9 offset-sm-3">
                <button type="submit" class="btn btn-primary">Checkout</button>
            </div>
        </div>
    </form>
</div>