<?php
include 'connect.php';

// Get course ID from URL
$course_id = $_GET['id'] ?? null;

if (!$course_id) {
    die("Course not found.");
}

// Sanitize and ensure course_id is valid
$course_id = intval($course_id);

// Increment course views
$updateViews = "UPDATE courses SET views = views + 1 WHERE id = ?";
$stmt = $db->prepare($updateViews);
$stmt->bind_param("i", $course_id);
$stmt->execute();

// Fetch course details (excluding price)
$query = "SELECT 
            courses.title, 
            courses.description, 
            courses.thumbnail, 
            courses.zip_file, 
            categories.name AS category_name 
          FROM courses 
          JOIN categories ON courses.category_id = categories.id 
          WHERE courses.id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Course not found.");
}

$course = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($course['title']) ?></title>
    <link rel="stylesheet" href="course_details.css">
</head>
<body>
    <!-- Header Section -->
    <header class="user-header">
        <div class="logo">
            <a href="index2.php"><img src="logo.png" alt="Logo"></a>
        </div>
        <div class="nav-buttons">
            <a href="courses_list.php" class="btn">Back to Courses</a>
            <a href="logout.php" class="btn logout-btn">Logout</a>
        </div>
    </header>

    <!-- Course Details -->
    <div class="course-details">
        <h1><?= htmlspecialchars($course['title']) ?></h1>
        <img src="<?= htmlspecialchars($course['thumbnail']) ?>" alt="Course Thumbnail">
        <p><strong>Category:</strong> <?= htmlspecialchars($course['category_name']) ?></p>
        <p><strong>Description:</strong> <?= htmlspecialchars($course['description']) ?></p>

        <!-- Download Button -->
        <a href="download.php?course_id=<?= $course_id ?>" class="btn">Download Course Files</a>
    </div>
</body>
</html>
