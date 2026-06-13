<?php
session_start();
include 'connect.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

// Handle the approval process
if (isset($_GET['id'])) {
    $enrollment_id = $_GET['id'];

    // Approve the enrollment
    $approve_query = "UPDATE enrollments SET status = 1 WHERE id = ?";
    $stmt = $db->prepare($approve_query);
    $stmt->bind_param("i", $enrollment_id);
    $stmt->execute();

    // Redirect back to the enrollments page
    header("Location: manage_enrollments.php");
    exit;
}
?>
