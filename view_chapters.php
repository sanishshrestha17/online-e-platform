<?php
session_start();
include 'connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if course_id is provided
if (!isset($_GET['course_id'])) {
    die("Invalid request.");
}

$course_id = intval($_GET['course_id']);

// Check that user has an approved enrollment for this course
$enrollStmt = $db->prepare("SELECT status FROM enrollments WHERE user_id = ? AND course_id = ?");
$enrollStmt->bind_param("ii", $_SESSION['user_id'], $course_id);
$enrollStmt->execute();
$enrollment = $enrollStmt->get_result()->fetch_assoc();

if (!$enrollment) {
    header("Location: courses_list.php");
    exit;
}
if ($enrollment['status'] != 1) {
    die("
    <div style='font-family:Arial;text-align:center;margin-top:80px;'>
        <h2>⏳ Access Pending</h2>
        <p>Your enrollment is awaiting admin approval. Please check back later.</p>
        <a href='courses_list.php' style='color:#3b5998;'>← Back to Courses</a>
    </div>");
}

// Fetch course details
$courseQuery = "SELECT title FROM courses WHERE id = ?";
$stmt = $db->prepare($courseQuery);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$courseResult = $stmt->get_result();
$course = $courseResult->fetch_assoc();

if (!$course) {
    die("Course not found.");
}

// Fetch chapters — try with zip_file, fall back if column doesn't exist yet
try {
    $stmt = $db->prepare("SELECT id, title, description, zip_file FROM chapters WHERE course_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $chapters = $stmt->get_result();
} catch (mysqli_sql_exception $e) {
    // zip_file column not in DB yet — fetch without it
    $stmt = $db->prepare("SELECT id, title, description FROM chapters WHERE course_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $chapters = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Chapters</title>
    <link rel="stylesheet" href="view_chapters.css">
</head>
<body>
    <header>
        <h1>Chapters for <?= htmlspecialchars($course['title']) ?></h1>
        <a href="courses_list.php" class="btn">Back to Courses</a>
    </header>
    
    <main>
        <?php if ($chapters->num_rows > 0): ?>
            <ul class="chapter-list">
                <?php while ($chapter = $chapters->fetch_assoc()): ?>
                    <li>
                        <h2><?= htmlspecialchars($chapter['title']) ?></h2>
                        <p><?= htmlspecialchars($chapter['description']) ?></p>
                        <?php if (!empty($chapter['zip_file'] ?? '')): 
                            $ext = strtolower(pathinfo($chapter['zip_file'], PATHINFO_EXTENSION));
                            $label = ($ext === 'pdf') ? 'Download PDF' : 'Download ZIP';
                        ?>
                            <a href="download_chapter.php?file=<?= urlencode($chapter['zip_file']) ?>" class="btn"><?= $label ?></a>
                        <?php else: ?>
                            <p>No download available for this chapter.</p>
                        <?php endif; ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No chapters found for this course.</p>
        <?php endif; ?>
    </main>
</body>
</html>