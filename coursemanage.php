<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

include 'connect.php';

// Fetch all courses from the database, excluding the price column
$query = "SELECT courses.id, courses.title, courses.description, categories.name AS category_name, courses.status
          FROM courses
          JOIN categories ON courses.category_id = categories.id";
$result = $db->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <link rel="stylesheet" href="coursemanage.css">
    <style>
        /* Style for the clickable course title */
        table a {
            color: green;  /* Set the text color to green */
            text-decoration: none;  /* Remove underline */
        }

        table a:hover {
            color: #007f00;  /* Slightly darker green on hover */
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header class="admin-header">
        <div class="logo">
            <a href="admin_panel.php"><img src="logo.png" alt="Logo"></a>
        </div>
        <div class="logout">
            <a href="adminlogout.php">Logout</a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="admin-main">
        <h1>Manage Courses</h1>
        <div class="button-container">
            <a href="add_course.php" class="btn">Add New Course</a>
        </div>

        <!-- Courses Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']) ?></td>
                                <td><a href="chapters.php?course_id=<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></a></td> <!-- Link to chapters.php -->
                                <td><?= htmlspecialchars($row['category_name']) ?></td>
                                <td><?= htmlspecialchars($row['description']) ?></td>
                                <td><?= htmlspecialchars($row['status']) ?></td>
                                <td>
                                    <a href="edit_course.php?id=<?= $row['id'] ?>" class="btn-small">Edit</a>
                                    <a href="add_chapter.php?course_id=<?= $row['id'] ?>" class="btn-small" style="background:#1abc9c;">Add Chapter</a>
                                    <a href="chapters.php?course_id=<?= $row['id'] ?>" class="btn-small" style="background:#3b5998;">View Chapters</a>
                                    <a href="delete_course.php?id=<?= $row['id'] ?>" class="btn-small delete-btn" onclick="return confirm('Delete this course and all its chapters?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No courses found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>