<?php
session_start(); // Start session
if (!isset($_SESSION['user_id'])) {
    header('Location: login_user.html'); // Redirect if not logged in
    exit();
}

include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $email = $conn->real_escape_string($_POST['email']);

    // Update user information
    $sql = "UPDATE userss SET first_name = '$first_name', last_name = '$last_name', email = '$email' WHERE id = '$user_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Profile updated successfully! <a href='profile.php'>View Profile</a>";
    } else {
        echo "Error updating profile: " . $conn->error;
    }

    $conn->close();
}
?>
