<?php
// Define variables and initialize with empty values
$firstName = $lastName = $phoneNumber = $creditCardNumber = $cvv = $street = $city = $state = $zip = "";
$firstNameErr = $lastNameErr = $phoneNumberErr = $creditCardNumberErr = $cvvErr = $streetErr = $cityErr = $stateErr = $zipErr = "";

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

    // Validate state
    if (empty(trim($_POST["state"]))) {
        $stateErr = "Please enter your State.";
    } else {
        $state = trim($_POST["state"]);
    }

    // Validate zip
    if (empty(trim($_POST["zip"]))) {
        $zipErr = "Please enter your Zip.";
    } elseif (!preg_match("/^[0-9]{5}$/", trim($_POST["zip"]))) {
        $zipErr = "Invalid Zip, only 5 digits are allowed.";
    } else {
        $zip = trim($_POST["zip"]);
    }

    // Check input errors before inserting in database
    if (empty($firstNameErr) && empty($lastNameErr) && empty($phoneNumberErr) && empty($creditCardNumberErr) && empty($cvvErr) && empty($streetErr) && empty($cityErr) && empty($stateErr) && empty($zipErr)) {
        // Insert data into database
        // ...
    }
}
?>

<!-- HTML form -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
        <label>State</label>
        <input type="text" name="state" value="<?php echo $state; ?>">
        <span><?php echo $stateErr; ?></span>
    </div>
    <div>
        <label>Zip</label>
        <input type="text" name="zip" value="<?php echo $zip; ?>">
        <span><?php echo $zipErr; ?></span>
    </div>
    <div>
        <input type="submit" value="Checkout">
    </div>
</form>