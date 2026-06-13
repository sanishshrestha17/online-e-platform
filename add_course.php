<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

include 'connect.php';

$success_message = "";
$error_message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category_id = $_POST['category_id'] ?? '';
    $status      = $_POST['status'] ?? 'active';
    $thumbnail   = $_FILES['thumbnail'] ?? null;

    // Validation
    if (empty($title) || empty($description) || empty($category_id)) {
        $error_message = "Title, description, and category are required.";
    } else {
        $thumbnail_path = '';

        // Handle optional thumbnail upload
        if ($thumbnail && $thumbnail['error'] === UPLOAD_ERR_OK) {
            $thumbnail_dir = 'uploads/thumbnails/';
            if (!file_exists($thumbnail_dir)) {
                mkdir($thumbnail_dir, 0777, true);
            }
            $ext = pathinfo($thumbnail['name'], PATHINFO_EXTENSION);
            $safe_name = uniqid('course_') . '.' . $ext;
            $thumbnail_path = $thumbnail_dir . $safe_name;
            if (!move_uploaded_file($thumbnail['tmp_name'], $thumbnail_path)) {
                $error_message = "Failed to upload thumbnail.";
            }
        }

        if (empty($error_message)) {
            $course_file_path = '';

            // Handle optional ZIP or PDF upload
            if (!empty($_FILES['course_file']['name']) && $_FILES['course_file']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['course_file']['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, ['zip', 'pdf'])) {
                    $error_message = "Only ZIP and PDF files are allowed.";
                } else {
                    $file_dir = 'uploads/courses/';
                    if (!file_exists($file_dir)) {
                        mkdir($file_dir, 0777, true);
                    }
                    $safe_name = uniqid('course_') . '_' . basename($_FILES['course_file']['name']);
                    $course_file_path = $file_dir . $safe_name;
                    if (!move_uploaded_file($_FILES['course_file']['tmp_name'], $course_file_path)) {
                        $error_message = "Failed to upload course file.";
                    }
                }
            }
        }

        if (empty($error_message)) {
            $query = "INSERT INTO courses (title, description, category_id, thumbnail, course_file, status, created_at)
                      VALUES (?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $db->prepare($query);
            $stmt->bind_param("ssisss", $title, $description, $category_id, $thumbnail_path, $course_file_path, $status);

            if ($stmt->execute()) {
                $success_message = "Course added successfully!";
            } else {
                $error_message = "Error adding course: " . $stmt->error;
            }
        }
    }
}

// Fetch categories for dropdown
$categories = $db->query("SELECT id, name FROM categories ORDER BY name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Course</title>
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
        .message.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .message.error   { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="logo">
            <a href="admin_panel.php"><img src="logo.png" alt="Logo"></a>
        </div>
        <div class="header-buttons">
            <a href="coursemanage.php" class="btn-back">Back to Courses</a>
            <a href="adminlogout.php" class="btn-logout">Logout</a>
        </div>
    </header>

    <main class="admin-main">
        <h1>Add New Course</h1>

        <?php if (!empty($success_message)): ?>
            <div class="message success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div class="message error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <form action="add_course.php" method="POST" enctype="multipart/form-data">

            <label for="title">Course Title</label>
            <input type="text" id="title" name="title" required
                   value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">

            <label for="description">Course Description</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>

            <label for="category_id">Category</label>
            <select id="category_id" name="category_id" required>
                <option value="">-- Select Category --</option>
                <?php if ($categories && $categories->num_rows > 0):
                    while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($cat['id']) ?>"
                        <?= (($_POST['category_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endwhile; endif; ?>
            </select>

            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="active"   <?= (($_POST['status'] ?? 'active') === 'active')   ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= (($_POST['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>Inactive</option>
            </select>

            <label for="thumbnail">Course Thumbnail (optional)</label>
            <input type="file" id="thumbnail" name="thumbnail" accept="image/*">

            <label for="course_file">Course File — ZIP or PDF (optional)</label>
            <input type="file" id="course_file" name="course_file" accept=".zip,.pdf,application/zip,application/pdf">

            <button type="submit">Add Course</button>
        </form>
    </main>
</body>
</html>