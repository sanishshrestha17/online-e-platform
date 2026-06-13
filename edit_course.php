<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

include 'connect.php';

$course_id = intval($_GET['id'] ?? 0);
if (!$course_id) {
    die("Invalid course ID.");
}

$success_message = "";
$error_message = "";

// Fetch the course data
$stmt = $db->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$course = $stmt->get_result()->fetch_assoc();

if (!$course) {
    die("Course not found.");
}

// Fetch categories for dropdown
$categories_result = $db->query("SELECT id, name FROM categories ORDER BY name");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $status      = $_POST['status'] ?? 'active';

    if (empty($title) || empty($description) || !$category_id) {
        $error_message = "Title, description, and category are required.";
    } else {

        // --- Thumbnail ---
        $thumbnail_path = $course['thumbnail']; // keep existing by default
        if (!empty($_FILES['thumbnail']['name']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $thumb_dir = 'uploads/thumbnails/';
            if (!file_exists($thumb_dir)) mkdir($thumb_dir, 0777, true);
            $ext = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
            $safe_name = uniqid('course_') . '.' . $ext;
            $new_path = $thumb_dir . $safe_name;
            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $new_path)) {
                $thumbnail_path = $new_path;
            } else {
                $error_message = "Failed to upload thumbnail.";
            }
        }

        // --- Course file (ZIP or PDF) ---
        $course_file_path = $course['course_file'] ?? ''; // keep existing by default
        if (!empty($_FILES['course_file']['name']) && $_FILES['course_file']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['course_file']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['zip', 'pdf'])) {
                $error_message = "Only ZIP and PDF files are allowed.";
            } else {
                $file_dir = 'uploads/courses/';
                if (!file_exists($file_dir)) mkdir($file_dir, 0777, true);
                $safe_name = uniqid('course_') . '_' . basename($_FILES['course_file']['name']);
                $new_path = $file_dir . $safe_name;
                if (move_uploaded_file($_FILES['course_file']['tmp_name'], $new_path)) {
                    $course_file_path = $new_path;
                } else {
                    $error_message = "Failed to upload course file.";
                }
            }
        }

        if (empty($error_message)) {
            $stmt = $db->prepare(
                "UPDATE courses SET title=?, description=?, category_id=?, thumbnail=?, course_file=?, status=? WHERE id=?"
            );
            $stmt->bind_param("ssisssi", $title, $description, $category_id, $thumbnail_path, $course_file_path, $status, $course_id);

            if ($stmt->execute()) {
                $success_message = "Course updated successfully!";
                // Refresh course data to reflect changes
                $stmt2 = $db->prepare("SELECT * FROM courses WHERE id = ?");
                $stmt2->bind_param("i", $course_id);
                $stmt2->execute();
                $course = $stmt2->get_result()->fetch_assoc();
            } else {
                $error_message = "Error updating course: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
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
        .current-file {
            font-size: 13px;
            color: #555;
            margin-bottom: 16px;
        }
        .current-file img {
            display: block;
            margin-top: 6px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .current-file a {
            color: #4CAF50;
            text-decoration: none;
        }
        .current-file a:hover { text-decoration: underline; }
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
        <h1>Edit Course</h1>

        <?php if (!empty($success_message)): ?>
            <div class="message success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div class="message error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <form action="edit_course.php?id=<?= $course_id ?>" method="POST" enctype="multipart/form-data">

            <label for="title">Course Title</label>
            <input type="text" id="title" name="title" required
                   value="<?= htmlspecialchars($course['title']) ?>">

            <label for="description">Course Description</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($course['description']) ?></textarea>

            <label for="category_id">Category</label>
            <select id="category_id" name="category_id" required>
                <option value="">-- Select Category --</option>
                <?php while ($row = $categories_result->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($row['id']) ?>"
                        <?= ($row['id'] == $course['category_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="active"   <?= ($course['status'] === 'active')   ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= ($course['status'] === 'inactive') ? 'selected' : '' ?>>Inactive</option>
            </select>

            <label for="thumbnail">Thumbnail Image (leave blank to keep current)</label>
            <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
            <?php if (!empty($course['thumbnail'])): ?>
                <div class="current-file">
                    Current thumbnail:
                    <img src="<?= htmlspecialchars($course['thumbnail']) ?>" width="120" alt="Current Thumbnail">
                </div>
            <?php endif; ?>

            <label for="course_file">Course File — ZIP or PDF (leave blank to keep current)</label>
            <input type="file" id="course_file" name="course_file" accept=".zip,.pdf,application/zip,application/pdf">
            <?php if (!empty($course['course_file'])): 
                $ext = strtolower(pathinfo($course['course_file'], PATHINFO_EXTENSION));
                $file_label = strtoupper($ext) . ' file';
            ?>
                <div class="current-file">
                    Current file: <a href="<?= htmlspecialchars($course['course_file']) ?>" target="_blank"><?= $file_label ?></a>
                </div>
            <?php endif; ?>

            <button type="submit">Update Course</button>
        </form>
    </main>
</body>
</html>