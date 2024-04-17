<?php
include 'navbar.php';

session_start();

require 'db_conn.php';
require 'Users.php';

// Initialize database connection
$db = new DatabaseConnection();
$users = new Users($db);

// Define variables and initialize with empty values
$name = $lname = $email = $phone = $province = "";
$nameErr = $lnameErr = $emailErr = $phoneErr = $provinceErr = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate first name
    if (empty(trim($_POST["name"]))) {
        $nameErr = "Please enter your Last Name.";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", trim($_POST["name"]))) {
        $nameErr = "Invalid first Name, only alphabets and spaces are allowed.";
    } else {
        $name = trim($_POST["name"]);
    }
    // Validate lname
    if (empty(trim($_POST["lname"]))) {
        $lnameErr = "Please enter your Last Name.";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", trim($_POST["lname"]))) {
        $lnameErr = "Invalid Last Name, only alphabets and spaces are allowed.";
    } else {
        $lname = trim($_POST["lname"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $emailErr = "Please enter your Email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid Email format.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate phone
    if (empty(trim($_POST["phone"]))) {
        $phoneErr = "Please enter your Phone Number.";
    } elseif (!preg_match("/^[0-9]{10}$/", trim($_POST["phone"]))) {
        $phoneErr = "Invalid Phone Number, only 10 digits are allowed.";
    } else {
        $phone = trim($_POST["phone"]);
    }

    // Validate province
    if (empty(trim($_POST["province"]))) {
        $provinceErr = "Please select your Province.";
    } else {
        $province = trim($_POST["province"]);
    }

    // Check input errors before inserting in database
    if (empty($nameErr) && empty($lnameErr) && empty($emailErr) && empty($phoneErr) && empty($provinceErr)) {
        // Insert data into database
        $result = $users->register_user($name, $lname, $email, $phone, $province);
        if ($result) {
            $_SESSION['user_id'] = mysqli_insert_id($db->get_dbc());
            header("Location: checkout.php");
            exit;
        }
    }
}
?>

<!-- HTML form -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div>
        <label>First Name</label>
        <input type="text" name="name" value="<?php echo $name; ?>">
        <span><?php echo $nameErr; ?></span>
    </div>
    <div>
        <label>Last Name</label>
        <input type="text" name="lname" value="<?php echo $lname; ?>">
        <span><?php echo $lnameErr; ?></span>
    </div>
    <div>
        <label>Email</label>
        <input type="email" name="email" value="<?php echo $email; ?>">
        <span><?php echo $emailErr; ?></span>
    </div>
    <div>
        <label>Phone</label>
        <input type="text" name="phone" value="<?php echo $phone; ?>">
        <span><?php echo $phoneErr; ?></span>
    </div>
    <div>
        <label>Province</label>
        <select name="province">
            <option value="AB">Alberta</option>
            <option value="BC">British Columbia</option>
            <option value="MB">Manitoba</option>
            <option value="NB">New Brunswick</option>
            <option value="NL">Newfoundland and Labrador</option>
            <option value="NS">Nova Scotia</option>
            <option value="ON">Ontario</option>
            <option value="PE">Prince Edward Island</option>
            <option value="QC">Quebec</option>
            <option value="SK">Saskatchewan</option>
        </select>
        <span><?php echo $provinceErr; ?></span>
    </div>
    <div>
        <input type="submit" value="Register">
    </div>
</form>