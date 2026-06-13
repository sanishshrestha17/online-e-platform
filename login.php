<?php
session_start(); // Start session at the beginning

// Include the database connection
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize input
    $email = trim($db->real_escape_string($_POST['email']));
    $password = trim($_POST['password']); // No need to escape

    if (empty($email) || empty($password)) {
        echo "Please fill in both email and password.";
        exit();
    }

    // Query to find the user
    $sql = "SELECT * FROM userss WHERE email = '$email'";
    $result = $db->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['first_name']; // Adjust as needed

            header("Location: index2.php");
            exit();
        } else {
            echo "Incorrect email or password.";
        }
    } else {
        echo "No account found with this email.";
    }
} else {
    echo "Invalid request method.";
}
?>
