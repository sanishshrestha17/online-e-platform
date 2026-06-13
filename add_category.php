<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

include 'connect.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $name = $_POST['category_name'];
    $description = $_POST['category_description'];

    // Validate input
    if (empty($name) || empty($description)) {
        $error_message = "Both fields are required.";
    } else {
        // Insert new category into the database
        $query = "INSERT INTO categories (name, description, created_at) VALUES (?, ?, NOW())";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ss", $name, $description);
        
        if ($stmt->execute()) {
            $success_message = "Category added successfully!";
        } else {
            $error_message = "Error adding category: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Category</title>
    <link rel="stylesheet" href="add_category.css">
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
        <h1>Add New Category</h1>

        <!-- Success or Error Message -->
        <?php if (isset($success_message)): ?>
            <div class="success-message"><?= $success_message ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?= $error_message ?></div>
        <?php endif; ?>

        <!-- Add Category Form -->
        <form action="add_category.php" method="POST">
            <label for="category_name">Category Name</label>
            <input type="text" id="category_name" name="category_name" required>

            <label for="category_description">Category Description</label>
            <textarea id="category_description" name="category_description" required></textarea>

            <button type="submit" name="add_category">Add Category</button>
        </form>

        <div class="button-container">
            <a href="categoriesmanage.php" class="btn">Back to Manage Categories</a>
        </div>
    </main>
</body>
</html>
