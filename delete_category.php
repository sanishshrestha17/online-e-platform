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

// Delete category from the database
$query = "DELETE FROM categories WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $category_id);
$stmt->execute();

header("Location: categoriesmanage.php");
exit;
?>
