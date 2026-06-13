<?php
// Include the database connection
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($db->real_escape_string($_POST['first_name']));
    $last_name = trim($db->real_escape_string($_POST['last_name']));
    $email = trim($db->real_escape_string($_POST['email']));
    $password = trim($_POST['password']); // No need to escape

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if email already exists
    $check_email = "SELECT * FROM userss WHERE email = '$email'";
    $result = $db->query($check_email);

    if ($result->num_rows > 0) {
        echo "Email already exists. Please use a different email.";
    } else {
        // Insert new user
        $sql = "INSERT INTO userss (first_name, last_name, email, password, created_at, status) 
                VALUES ('$first_name', '$last_name', '$email', '$hashed_password', NOW(), 'non-verified')";

        if ($db->query($sql)) {
            // Show success message first
            echo "Successfully Registered! Redirecting to login...";
            header("refresh:2;url=login.html"); // Wait 2 seconds before redirecting
            exit();
        } else {
            echo "Error: " . $db->error;
        }
    }
} else {
    echo "Invalid request method.";
}
?>
