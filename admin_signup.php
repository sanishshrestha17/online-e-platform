<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'connect.php';

    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new admin into the database
    $query = "INSERT INTO admins (username, password, role) VALUES (?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("sss", $username, $hashed_password, $role);

    if ($stmt->execute()) {
        echo "<p>Admin created successfully! <a href='admin_login.php'>Login</a></p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Signup</title>
    <link rel="stylesheet" href="adminstyle.css">
</head>
<body>
    <div class="signup-container">
        <form method="POST">
            <h2>Admin Sign Up</h2>
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <select name="role" required>
                <option value="superadmin">Super Admin</option>
                <option value="moderator">Moderator</option>
            </select><br>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="admin_login.php">Login here</a></p>
    </div>
</body>
</html>
