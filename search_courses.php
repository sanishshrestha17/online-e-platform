<?php
include 'connect.php';

// Get the search term from the URL (if it exists)
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch courses matching the search term
$query = "SELECT 
            courses.id, 
            courses.title, 
            courses.description, 
            courses.thumbnail, 
            categories.name AS category_name 
          FROM courses 
          JOIN categories ON courses.category_id = categories.id 
          WHERE courses.status = 'active' 
          AND (courses.title LIKE '%$searchQuery%' 
               OR courses.description LIKE '%$searchQuery%' 
               OR categories.name LIKE '%$searchQuery%')";

$result = $db->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="courses_list.css">

    <!-- Include Font Awesome for search icon -->
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
            <form action="search_courses.php" method="GET">
                <input type="text" name="search" placeholder="Search courses..." value="<?= htmlspecialchars($searchQuery) ?>">
                <button type="submit">
                    <i class="fas fa-search"></i> <!-- Search Icon -->
                </button>
            </form>
        </div>
        <div class="nav-buttons">
            <a href="index2.php" class="btn">Home</a>
            <a href="logout.php" class="btn logout-btn">Logout</a>
        </div>
    </header>

    <!-- Main Content -->
    <div class="course-list">
        <h1>Search Results for: "<?= htmlspecialchars($searchQuery) ?>"</h1>

        <div class="courses-container">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="course-card">
                        <img src="<?= htmlspecialchars($row['thumbnail']) ?>" alt="Course Thumbnail">
                        <h2><?= htmlspecialchars($row['title']) ?></h2>
                        <p><?= htmlspecialchars(substr($row['description'], 0, 100)) ?>...</p>
                        <p><strong>Category:</strong> <?= htmlspecialchars($row['category_name']) ?></p>

                        <a href="download.php?course_id=<?= $row['id'] ?>" class="btn">Download</a>
                        <a href="course_details.php?id=<?= $row['id'] ?>" class="btn">View Details</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No courses found for your search.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
