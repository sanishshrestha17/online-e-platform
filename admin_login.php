<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_panel.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'connect.php'; // Include your existing database connection file

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to fetch admin details
    $query = "SELECT * FROM admins WHERE username = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_role'] = $admin['role'];
            header("Location: admin_panel.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Invalid username.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="adminstyle.css">
</head>
<body>
    <div class="login-container">
        <form method="POST">
            <h2>Admin Login</h2>
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        </form>
        <p>Don't have an account yet? <a href="admin_signup.php">Sign up here</a></p>
    </div>
</body>
</html>
