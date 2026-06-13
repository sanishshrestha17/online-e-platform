<?php
// Database connection
$host = "localhost";    // Server
$user = "root";         // Username (default for XAMPP)
$password = "";         // Password (default for XAMPP)
$database = "user_system"; // Your database name

// Establish connection
$db = new mysqli($host, $user, $password, $database);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>
