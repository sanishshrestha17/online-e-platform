<?php
session_start();
include 'connect.php';

// Redirect if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Get admin info
$admin_id = $_SESSION['admin_id'];

$query = "SELECT username FROM admins WHERE id=?";
$stmt = $db->prepare($query);

if (!$stmt) {
    die("Query Failed: " . $db->error);
}

$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

$admin = $result->fetch_assoc();
$username = $admin['username'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        /* Header */
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #2c3e50;
            color: white;
            padding: 15px 30px;
        }

        .admin-header img {
            height: 40px;
        }

        .logout a {
            color: white;
            text-decoration: none;
            background: #e74c3c;
            padding: 8px 15px;
            border-radius: 5px;
        }

        .logout a:hover {
            background: #c0392b;
        }

        /* Main */
        .admin-main {
            padding: 30px;
            text-align: center;
        }

        .welcome-section h2 {
            margin-bottom: 30px;
            color: #333;
        }

        /* Buttons */
        .button-container {
            margin: 20px 0;
        }

        .btn {
            display: inline-block;
            margin: 10px;
            padding: 15px 25px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            transition: 0.3s;
        }

        .btn:hover {
            background: #2980b9;
        }
    </style>
</head>

<body>

<!-- Header -->
<header class="admin-header">
    <div class="logo">
        <img src="logo.png" alt="Logo">
    </div>

    <div class="logout">
        <a href="adminlogout.php">Logout</a>
    </div>
</header>

<!-- Main Content -->
<main class="admin-main">

    <div class="welcome-section">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?> 👋</h2>
    </div>

    <!-- Manage Section -->
    <div class="button-container">
        <a href="coursemanage.php" class="btn">📚 Manage Courses</a>
        <a href="categoriesmanage.php" class="btn">📂 Manage Categories</a>
    </div>

    <!-- Activity Section -->
    <div class="button-container">
        <a href="views_downloads.php" class="btn">📊 Course Activity</a>
    </div>

    <!-- Enrollment Section -->
    <div class="button-container">
        <a href="admin_enrollments.php" class="btn">👥 Manage Enrollments</a>
    </div>

</main>

</body>
</html>