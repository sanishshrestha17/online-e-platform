<?php
session_start(); // Start session
if (!isset($_SESSION['user_id'])) {
    header('Location: login_user.html'); // Redirect if not logged in
    exit();
}

// Display user info
echo "Welcome, " . $_SESSION['first_name'] . " " . $_SESSION['last_name'] . "!";
?>
