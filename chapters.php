<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

include 'connect.php';

$course_id = intval($_GET['course_id'] ?? 0);
if (!$course_id) {
    header("Location: coursemanage.php");
    exit;
}

// Fetch course title
$stmt = $db->prepare("SELECT title FROM courses WHERE id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$course = $stmt->get_result()->fetch_assoc();
$course_title = $course['title'] ?? 'Unknown Course';

// Fetch chapters — try with zip_file, fall back if column doesn't exist yet
try {
    $stmt = $db->prepare("SELECT id, title, description, zip_file FROM chapters WHERE course_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $has_zip_col = true;
} catch (mysqli_sql_exception $e) {
    $stmt = $db->prepare("SELECT id, title, description FROM chapters WHERE course_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $has_zip_col = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chapters — <?= htmlspecialchars($course_title) ?></title>
    <link rel="stylesheet" href="chapters.css">
</head>
<body>
    <header class="admin-header">
        <div class="logo">
            <a href="admin_panel.php"><img src="logo.png" alt="Logo"></a>
        </div>
        <div class="header-buttons">
            <a href="coursemanage.php" class="btn-back">Back to Courses</a>
            <a href="add_chapter.php?course_id=<?= $course_id ?>" class="btn-back">+ Add Chapter</a>
            <a href="adminlogout.php" class="btn-logout">Logout</a>
        </div>
    </header>

    <main class="admin-main">
        <h1>Chapters for: <?= htmlspecialchars($course_title) ?></h1>

        <?php if (!$has_zip_col): ?>
            <div style="background:#fff3cd;color:#856404;border:1px solid #ffc107;padding:10px 16px;border-radius:6px;margin-bottom:16px;">
                ⚠️ The <code>zip_file</code> column is missing from your <code>chapters</code> table.
                Run: <code>ALTER TABLE chapters ADD COLUMN zip_file VARCHAR(255) DEFAULT NULL;</code>
            </div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>File</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0):
                    $i = 1;
                    while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td>
                            <?php
                            $file = $row['zip_file'] ?? '';
                            if (!empty($file)):
                                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                $label = ($ext === 'pdf') ? '📄 Download PDF' : '📦 Download ZIP';
                            ?>
                                <a href="download_chapter.php?file=<?= urlencode($file) ?>"><?= $label ?></a>
                            <?php else: ?>
                                <span style="color:#aaa;">No file</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_chapter.php?id=<?= $row['id'] ?>" class="btn-small">Edit</a>
                            <a href="delete_chapter.php?id=<?= $row['id'] ?>&course_id=<?= $course_id ?>"
                               class="btn-small delete-btn"
                               onclick="return confirm('Delete this chapter?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr>
                        <td colspan="5" style="text-align:center;color:#888;padding:30px;">
                            No chapters yet. <a href="add_chapter.php?course_id=<?= $course_id ?>">Add the first one →</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>