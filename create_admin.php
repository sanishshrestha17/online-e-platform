<?php
include 'connect.php'; // Include your existing database connection file

// Define admin details
$username = "admin"; // Replace with the desired username
$password = "admin123"; // Replace with the desired password
$role = "superadmin"; // Replace with the desired role (e.g., 'superadmin' or 'moderator')

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare the SQL query to insert the admin
$query = "INSERT INTO admins (username, password, role) VALUES (?, ?, ?)";
$stmt = $db->prepare($query);

if ($stmt) {
    $stmt->bind_param("sss", $username, $hashed_password, $role);

    // Execute the query
    if ($stmt->execute()) {
        echo "Admin created successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Error: " . $db->error;
}

// Close the database connection
$db->close();
?>
