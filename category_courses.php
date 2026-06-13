<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

include 'connect.php';

// Check if category_id is provided
if (!isset($_GET['category_id'])) {
    die("Invalid request.");
}

$category_id = intval($_GET['category_id']);

// Fetch courses in the selected category
$query = "SELECT * FROM courses WHERE category_id = ? AND status = 'active'";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses in Category</title>
    <link rel="stylesheet" href="categoriesmanage.css">
</head>
<body>
    <!-- Header Section -->
    <header class="admin-header">
        <div class="logo">
            <a href="admin_panel.php"><img src="logo.png" alt="Logo"></a>
        </div>
        <div class="logout">
            <a href="adminlogout.php">Logout</a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="admin-main">
        <h1>Courses in Category</h1>
        
        <!-- Back to Categories Button -->
        <div class="button-container">
            <a href="categoriesmanage.php" class="btn">Back to Categories</a>
        </div>

        <!-- Courses Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Description</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['title']) ?></td>
                                <td><?= htmlspecialchars($row['description']) ?></td>
                                <td><?= htmlspecialchars($row['created_at']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No courses found in this category.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
