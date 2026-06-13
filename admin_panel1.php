<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

include 'connect.php';

// Fetch the admin's username from the database
$admin_id = $_SESSION['admin_id'];
$query = "SELECT username FROM admins WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

$admin = $result->fetch_assoc();
$username = $admin['username'] ?? 'Admin'; // Fallback if username not found
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="panelstyle.css">
</head>
<body>
    <!-- Header Section -->
    <header class="admin-header">
        <div class="logo">
            <img src="logo.png" alt="Logo">
        </div>
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="admin-main">
        <h1>Welcome, <?= htmlspecialchars($username); ?> (Admin)</h1>
        <div class="button-container">
            <a href="coursemanage.php" class="btn">Manage Courses</a>
            <a href="categoriesmanage.php" class="btn">Manage Categories</a>
        </div>
    </main>
</body>
</html>
