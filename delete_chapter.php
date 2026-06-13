<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

include 'connect.php';

$chapter_id = intval($_GET['id'] ?? 0);
$course_id  = intval($_GET['course_id'] ?? 0);

// Fetch chapter to get course_id and file path
try {
    $stmt = $db->prepare("SELECT id, course_id, zip_file FROM chapters WHERE id = ?");
    $stmt->bind_param("i", $chapter_id);
    $stmt->execute();
    $chapter = $stmt->get_result()->fetch_assoc();
} catch (mysqli_sql_exception $e) {
    $stmt = $db->prepare("SELECT id, course_id FROM chapters WHERE id = ?");
    $stmt->bind_param("i", $chapter_id);
    $stmt->execute();
    $chapter = $stmt->get_result()->fetch_assoc();
}

if ($chapter) {
    $course_id = $chapter['course_id'];

    // Delete the file from server if it exists
    if (!empty($chapter['zip_file'] ?? '') && file_exists($chapter['zip_file'])) {
        unlink($chapter['zip_file']);
    }

    $stmt = $db->prepare("DELETE FROM chapters WHERE id = ?");
    $stmt->bind_param("i", $chapter_id);
    $stmt->execute();
}

header("Location: chapters.php?course_id=" . $course_id);
exit;
?>