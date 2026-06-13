<?php
session_start();
include 'connect.php';

// Check if course_id is provided
if (!isset($_GET['course_id'])) {
    die("Invalid request.");
}

$course_id = intval($_GET['course_id']);

// Fetch course details (only selecting zip_file)
$query = "SELECT zip_file FROM courses WHERE id = ? AND status = 'active'";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Course not found.");
}

$course = $result->fetch_assoc();

// Get the file path
$file_path = $course['zip_file'];

if (!file_exists($file_path)) {
    die("File not found.");
}

// Increment downloads count
$updateDownloads = "UPDATE courses SET downloads = downloads + 1 WHERE id = ?";
$stmt = $db->prepare($updateDownloads);
$stmt->bind_param("i", $course_id);
$stmt->execute();

// Force download
header("Content-Type: application/zip");
header("Content-Disposition: attachment; filename=\"" . basename($file_path) . "\"");
header("Content-Length: " . filesize($file_path));
readfile($file_path);
exit;
?>
