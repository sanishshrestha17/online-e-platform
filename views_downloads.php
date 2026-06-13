<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

include 'connect.php';

// Fetch course views and downloads
$query = "SELECT id, title, views, downloads FROM courses";
$result = $db->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Views & Downloads</title>
    <link rel="stylesheet" href="panelstyle.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 20px;
            background: #1e1e1e;
        }
        .logo img {
            width: 150px; /* Adjusted to a larger size */
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .logo img:hover {
            transform: scale(1.1);
        }
        .logout-btn {
            background-color: #e74c3c;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .logout-btn:hover {
            background-color: #c0392b;
        }
        .container {
            width: 80%; /* Set to 80% of the screen width */
            margin: auto;
            background: #1e1e1e;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
        }
        h2 {
            color: #ffffff;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #232323;
            text-align: left;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #444;
            color: #ffffff;
        }
        th {
            background: #007bff;
        }
        tr:nth-child(even) {
            background: #2c2c2c;
        }
        tr:hover {
            background: #3a3a3a;
        }
    </style>
</head>
<body>
    <div class="header">
        <!-- Logo on the Left -->
        <div class="logo">
            <a href="admin_panel.php">
                <img src="logo.png" alt="Admin Panel">
            </a>
        </div>
        <!-- Logout Button on the Right -->
        <a href="adminlogout.php" class="logout-btn">Logout</a>
    </div>

    <div class="container">
        <h2>Course Views & Downloads</h2>
        <table>
            <tr>
                <th>Course Name</th>
                <th>Views</th>
                <th>Downloads</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['views']) ?></td>
                    <td><?= htmlspecialchars($row['downloads']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
