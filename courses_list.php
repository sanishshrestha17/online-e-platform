<?php
session_start();
include 'connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get the search term (if any)
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch courses and categories (filtered by search if applicable)
$query = "SELECT 
            courses.id, 
            courses.title, 
            courses.description, 
            courses.thumbnail, 
            categories.name AS category_name 
          FROM courses 
          JOIN categories ON courses.category_id = categories.id 
          WHERE courses.status = 'active' 
          AND (courses.title LIKE ? 
               OR courses.description LIKE ? 
               OR categories.name LIKE ?)";

$stmt = $db->prepare($query);
$searchTerm = '%' . $searchQuery . '%';
$stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

// Handle enrollment logic
if (isset($_GET['enroll'])) {
    $course_id = $_GET['enroll'];
    $user_id = $_SESSION['user_id'];

    // Check if the user is already enrolled
    $check_enrollment_query = "SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?";
    $check_enrollment_stmt = $db->prepare($check_enrollment_query);
    $check_enrollment_stmt->bind_param("ii", $user_id, $course_id);
    $check_enrollment_stmt->execute();
    $enrollment_result = $check_enrollment_stmt->get_result();

    if ($enrollment_result->num_rows == 0) {
        // Insert enrollment record
        $enroll_query = "INSERT INTO enrollments (user_id, course_id, enrolled_at) VALUES (?, ?, NOW())";
        $enroll_stmt = $db->prepare($enroll_query);
        $enroll_stmt->bind_param("ii", $user_id, $course_id);
        $enroll_stmt->execute();
        
        // Redirect to the same page to avoid resubmitting the form
        header("Location: courses_list.php");
        exit;
    } else {
        // Redirect to the course chapters if already enrolled
        header("Location: view_chapters.php?course_id=$course_id");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
    <link rel="stylesheet" href="courses_list.css">

    <!-- Font Awesome for Search Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

    <!-- Header Section -->
    <header class="user-header">
        <div class="logo">
            <a href="index2.php"><img src="logo.png" alt="Logo"></a>
        </div>

        <!-- Search Bar -->
        <div class="search-bar">
            <form action="courses_list.php" method="GET">
                <input type="text" name="search" placeholder="Search courses..." value="<?= htmlspecialchars($searchQuery) ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <div class="nav-buttons">
            <a href="index2.php" class="btn">Home</a>
            <a href="logout.php" class="btn logout-btn">Logout</a>
        </div>
    </header>

    <!-- Main Content -->
    <div class="course-list">
        <h1><?= $searchQuery ? 'Search Results for: "' . htmlspecialchars($searchQuery) . '"' : 'Available Courses' ?></h1>

        <div class="courses-container">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="course-card">
                        <img src="<?= htmlspecialchars($row['thumbnail']) ?>" alt="Course Thumbnail">
                        <h2><?= htmlspecialchars($row['title']) ?></h2>
                        <p><?= htmlspecialchars(substr($row['description'], 0, 100)) ?>...</p>
                        <p><strong>Category:</strong> <?= htmlspecialchars($row['category_name']) ?></p>

                        <?php
                        // Check enrollment and approval status
                        $course_id = $row['id'];
                        $user_id = $_SESSION['user_id'];
                        $check_stmt = $db->prepare("SELECT status FROM enrollments WHERE user_id = ? AND course_id = ?");
                        $check_stmt->bind_param("ii", $user_id, $course_id);
                        $check_stmt->execute();
                        $enrollment = $check_stmt->get_result()->fetch_assoc();
                        ?>

                        <?php if ($enrollment): ?>
                            <?php if ($enrollment['status'] == 1): ?>
                                <!-- Approved — can view chapters -->
                                <a href="view_chapters.php?course_id=<?= $row['id'] ?>" class="btn">View Chapters</a>
                            <?php else: ?>
                                <!-- Enrolled but not yet approved -->
                                <span class="btn" style="background:#aaa;cursor:not-allowed;display:inline-block;" title="Waiting for admin approval">⏳ Pending Approval</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- Not enrolled -->
                            <a href="courses_list.php?enroll=<?= $row['id'] ?>" class="btn enroll-btn">Enroll</a>
                        <?php endif; ?>

                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No courses found.</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>