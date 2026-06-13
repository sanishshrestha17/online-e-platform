<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

include 'connect.php';

$chapter_id = intval($_GET['id'] ?? 0);

// Fetch chapter — try with zip_file, fall back if column missing
try {
    $stmt = $db->prepare("SELECT id, course_id, title, description, zip_file FROM chapters WHERE id = ?");
    $stmt->bind_param("i", $chapter_id);
    $stmt->execute();
    $chapter = $stmt->get_result()->fetch_assoc();
    $has_zip_col = true;
} catch (mysqli_sql_exception $e) {
    $stmt = $db->prepare("SELECT id, course_id, title, description FROM chapters WHERE id = ?");
    $stmt->bind_param("i", $chapter_id);
    $stmt->execute();
    $chapter = $stmt->get_result()->fetch_assoc();
    $has_zip_col = false;
}

if (!$chapter) {
    die("Chapter not found.");
}

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $zip_file_path = $chapter['zip_file'] ?? '';

    if (!empty($_FILES['zip_file']['name']) && $_FILES['zip_file']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['zip_file']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['zip', 'pdf'])) {
            $error_message = "Only ZIP and PDF files are allowed.";
        } else {
            $dir = 'uploads/chapters/';
            if (!file_exists($dir)) mkdir($dir, 0777, true);
            $safe_name = uniqid('chapter_') . '_' . basename($_FILES['zip_file']['name']);
            $new_path = $dir . $safe_name;
            if (move_uploaded_file($_FILES['zip_file']['tmp_name'], $new_path)) {
                $zip_file_path = $new_path;
            } else {
                $error_message = "Failed to upload file.";
            }
        }
    }

    if (empty($error_message)) {
        if ($has_zip_col) {
            $stmt = $db->prepare("UPDATE chapters SET title=?, description=?, zip_file=? WHERE id=?");
            $stmt->bind_param("sssi", $title, $description, $zip_file_path, $chapter_id);
        } else {
            $stmt = $db->prepare("UPDATE chapters SET title=?, description=? WHERE id=?");
            $stmt->bind_param("ssi", $title, $description, $chapter_id);
        }
        if ($stmt->execute()) {
            header("Location: chapters.php?course_id=" . $chapter['course_id']);
            exit;
        } else {
            $error_message = "Error updating chapter: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Chapter</title>
    <link rel="stylesheet" href="add_chapter.css">
    <style>
        .message { padding:12px 16px; border-radius:6px; margin-bottom:16px; font-size:15px; text-align:center; }
        .message.error { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }
        .current-file { font-size:13px; color:#555; margin-bottom:16px; }
        .current-file a { color:#4CAF50; }
        form select { padding:10px; font-size:16px; margin-bottom:20px; border-radius:5px; border:1px solid #ccc; width:100%; }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="logo">
            <a href="admin_panel.php"><img src="logo.png" alt="Logo"></a>
        </div>
        <div class="header-buttons">
            <a href="chapters.php?course_id=<?= $chapter['course_id'] ?>" class="btn-back">Back to Chapters</a>
            <a href="adminlogout.php" class="btn-logout">Logout</a>
        </div>
    </header>

    <main class="admin-main">
        <h1>Edit Chapter</h1>

        <?php if (!empty($error_message)): ?>
            <div class="message error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <form action="edit_chapter.php?id=<?= $chapter_id ?>" method="POST" enctype="multipart/form-data">
            <label for="title">Chapter Title</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($chapter['title']) ?>" required>

            <label for="description">Chapter Description</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($chapter['description']) ?></textarea>

            <label for="zip_file">Upload File — ZIP or PDF (leave blank to keep current)</label>
            <input type="file" id="zip_file" name="zip_file" accept=".zip,.pdf">
            <?php if (!empty($chapter['zip_file'] ?? '')): 
                $ext = strtolower(pathinfo($chapter['zip_file'], PATHINFO_EXTENSION));
            ?>
                <div class="current-file">
                    Current file: <a href="download_chapter.php?file=<?= urlencode($chapter['zip_file']) ?>"><?= strtoupper($ext) ?> file</a>
                </div>
            <?php endif; ?>

            <button type="submit">Update Chapter</button>
        </form>
    </main>
</body>
</html>