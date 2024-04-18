<?php
// Check if a session is already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the logout parameter is set in the URL
if (isset($_GET['logout'])) {
    // Unset user_id from the session
    unset($_SESSION['user_id']);

    // Redirect to the homepage
    header('Location: index.php');
    exit;
}
?>

<nav>
    <a href="admin.php">Home</a>
    <a href="products_add.php">Add</a>
</nav>