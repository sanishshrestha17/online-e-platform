<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

include 'connect.php';

$success_message = "";
$error_message = "";

// Allow course_id to come from URL (when linked from chapters.php) or from POST
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id   = intval($_POST['course_id'] ?? 0);
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (empty($course_id) || empty($title) || empty($description)) {
        $error_message = "Course, title, and description are required.";
    } else {
        $zip_file_path = '';

        // Handle optional ZIP or PDF upload
        if (!empty($_FILES['zip_file']['name']) && $_FILES['zip_file']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['zip_file']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['zip', 'pdf'])) {
                $error_message = "Only ZIP and PDF files are allowed.";
            } else {
                $zip_dir = 'uploads/chapters/';
                if (!file_exists($zip_dir)) {
                    mkdir($zip_dir, 0777, true);
                }
                $safe_name = uniqid('chapter_') . '_' . basename($_FILES['zip_file']['name']);
                $zip_file_path = $zip_dir . $safe_name;
                if (!move_uploaded_file($_FILES['zip_file']['tmp_name'], $zip_file_path)) {
                    $error_message = "Failed to upload file.";
                }
            }
        }

        if (empty($error_message)) {
            try {
                $stmt = $db->prepare("INSERT INTO chapters (course_id, title, description, zip_file, created_at) VALUES (?, ?, ?, ?, NOW())");
                $stmt->bind_param("isss", $course_id, $title, $description, $zip_file_path);
            } catch (mysqli_sql_exception $e) {
                $stmt = $db->prepare("INSERT INTO chapters (course_id, title, description, created_at) VALUES (?, ?, ?, NOW())");
                $stmt->bind_param("iss", $course_id, $title, $description);
            }

            if ($stmt->execute()) {
                header("Location: chapters.php?course_id=" . $course_id);
                exit;
            } else {
                $error_message = "Error adding chapter: " . $stmt->error;
            }
        }
    }
}

// Fetch all courses for the dropdown
$courses = $db->query("SELECT id, title FROM courses ORDER BY title");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Chapter</title>
    <link rel="stylesheet" href="add_chapter.css">
    <style>
        form select {
            padding: 10px;
            font-size: 16px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            outline: none;
            width: 100%;
        }
        .message {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 15px;
            text-align: center;
        }
        .message.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="logo">
            <a href="admin_panel.php"><img src="logo.png" alt="Logo"></a>
        </div>
        <div class="header-buttons">
            <a href="coursemanage.php" class="btn-back">Back to Courses</a>
            <?php if (!empty($course_id)): ?>
                <a href="chapters.php?course_id=<?= $course_id ?>" class="btn-back">Back to Chapters</a>
            <?php endif; ?>
            <a href="adminlogout.php" class="btn-logout">Logout</a>
        </div>
    </header>

    <main class="admin-main">
        <h1>Add Chapter</h1>

        <?php if (!empty($error_message)): ?>
            <div class="message error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <form action="add_chapter.php" method="POST" enctype="multipart/form-data">

            <label for="course_id">Select Course</label>
            <select id="course_id" name="course_id" required>
                <option value="">-- Select a Course --</option>
                <?php if ($courses && $courses->num_rows > 0):
                    while ($row = $courses->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($row['id']) ?>"
                        <?= ($course_id == $row['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['title']) ?>
                    </option>
                <?php endwhile; else: ?>
                    <option value="" disabled>No courses available — add a course first</option>
                <?php endif; ?>
            </select>

            <label for="title">Chapter Title</label>
            <input type="text" id="title" name="title" required
                   value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">

            <label for="description">Chapter Description</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>

            <label for="zip_file">Upload Chapter File — ZIP or PDF (optional)</label>
            <input type="file" id="zip_file" name="zip_file" accept=".zip,.pdf,application/zip,application/pdf">

            <button type="submit">Add Chapter</button>
        </form>
    </main>
</body>
</html>