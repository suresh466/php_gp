<?php
require 'Shoes.php';
require 'db_conn.php';
require 'Order.php';
require 'OrderItem.php';
require 'Users.php';

session_start();

$db = new DatabaseConnection();
$shoes = new Shoes($db);

// Define variables and initialize with empty values
$firstName = $lastName = $phoneNumber = $creditCardNumber = $cvv = $street = $city = $province = $zip = "";
$cartErr = $firstNameErr = $lastNameErr = $phoneNumberErr = $creditCardNumberErr = $cvvErr = $streetErr = $cityErr = $provinceErr = $zipErr = "";

// check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: register.php");
    exit;
}
else {
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
        $user_id = $_SESSION['user_id']; // Assuming the user id is stored in the session
        $order->add_order($user_id, $total_price);

        // Get the id of the newly created order
        $order_id = mysqli_insert_id($db->get_dbc());

        // Add order items
        foreach ($_SESSION['cart'] as $shoe_id) {
            $orderItem->add_order_item($order_id, $shoe_id, 1); // Assuming quantity is 1 for each item
        }

        // Clear the cart
        $_SESSION['cart'] = array();
    }
}
?>

<!-- HTML form -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <p><?php echo $cartErr; ?></p>
    <div>
        <label>First Name</label>
        <input type="text" name="firstName" value="<?php echo $firstName; ?>">
        <span><?php echo $firstNameErr; ?></span>
    </div>
    <div>
        <label>Last Name</label>
        <input type="text" name="lastName" value="<?php echo $lastName; ?>">
        <span><?php echo $lastNameErr; ?></span>
    </div>
    <div>
        <label>Phone Number</label>
        <input type="text" name="phoneNumber" value="<?php echo $phoneNumber; ?>">
        <span><?php echo $phoneNumberErr; ?></span>
    </div>
    <div>
        <label>Credit Card Number</label>
        <input type="text" name="creditCardNumber" value="<?php echo $creditCardNumber; ?>">
        <span><?php echo $creditCardNumberErr; ?></span>
    </div>
    <div>
        <label>CVV</label>
        <input type="text" name="cvv" value="<?php echo $cvv; ?>">
        <span><?php echo $cvvErr; ?></span>
    </div>
    <div>
        <label>Street</label>
        <input type="text" name="street" value="<?php echo $street; ?>">
        <span><?php echo $streetErr; ?></span>
    </div>
    <div>
        <label>City</label>
        <input type="text" name="city" value="<?php echo $city; ?>">
        <span><?php echo $cityErr; ?></span>
    </div>
    <div>
    <div>
        <label>Province</label>
        <select name="province">
        <option value="AB" <?php echo $province == 'AB' ? 'selected' : ''; ?>>Alberta</option>
        <option value="BC" <?php echo $province == 'BC' ? 'selected' : ''; ?>>British Columbia</option>
        <option value="MB" <?php echo $province == 'MB' ? 'selected' : ''; ?>>Manitoba</option>
        <option value="NB" <?php echo $province == 'NB' ? 'selected' : ''; ?>>New Brunswick</option>
        <option value="NL" <?php echo $province == 'NL' ? 'selected' : ''; ?>>Newfoundland and Labrador</option>
        <option value="NS" <?php echo $province == 'NS' ? 'selected' : ''; ?>>Nova Scotia</option>
        <option value="ON" <?php echo $province == 'ON' ? 'selected' : ''; ?>>Ontario</option>
        <option value="PE" <?php echo $province == 'PE' ? 'selected' : ''; ?>>Prince Edward Island</option>
        <option value="QC" <?php echo $province == 'QC' ? 'selected' : ''; ?>>Quebec</option>
        <option value="SK" <?php echo $province == 'SK' ? 'selected' : ''; ?>>Saskatchewan</option>
    </select>
        <span><?php echo $provinceErr; ?></span>
    </div>
    <div>
        <label>Zip</label>
        <input type="text" name="zip" value="<?php echo $zip; ?>">
        <span><?php echo $zipErr; ?></span>
    </div>
    <div>
        <label>Total Price</label>
        <p><?php echo $total_price; ?></p>
    </div>
    <div>
        <input type="submit" value="Checkout">
    </div>
</form>