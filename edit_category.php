<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

include 'connect.php';

// Check if category ID is provided
if (!isset($_GET['id'])) {
    die("Category not found.");
}

$category_id = intval($_GET['id']);

// Fetch category details from the database
$query = "SELECT * FROM categories WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Category not found.");
}

$category = $result->fetch_assoc();

// Handle form submission for updating the category
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $update_query = "UPDATE categories SET name = ?, description = ? WHERE id = ?";
    $update_stmt = $db->prepare($update_query);
    $update_stmt->bind_param("ssi", $name, $description, $category_id);
    $update_stmt->execute();

    header("Location: categoriesmanage.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link rel="stylesheet" href="editcategory.css">
</head>
<body>
    <header class="admin-header">
        <div class="logo">
            <a href="admin_panel.php"><img src="logo.png" alt="Logo"></a>
        </div>
        <div class="logout">
            <a href="adminlogout.php">Logout</a>
        </div>
    </header>

    <main class="admin-main">
        <h1>Edit Category</h1>
        <form action="edit_category.php?id=<?= $category['id'] ?>" method="POST">
            <label for="name">Category Name</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($category['name']) ?>" required>

            <label for="description">Category Description</label>
            <textarea name="description" id="description" required><?= htmlspecialchars($category['description']) ?></textarea>

            <button type="submit">Update Category</button>
        </form>
    </main>
</body>
</html>
