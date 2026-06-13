<?php
session_start();
include 'connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get the file path from the URL parameter
$file = isset($_GET['file']) ? urldecode($_GET['file']) : '';

// Ensure the file exists before allowing the download
if (!empty($file) && file_exists($file)) {
    // Set the headers to download the file
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header('Content-Length: ' . filesize($file));
    
    // Read and output the file
    readfile($file);
    exit;
} else {
    echo "File not found or path is invalid.";
}
?>
