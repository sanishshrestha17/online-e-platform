<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

include 'connect.php';

// Fetch the admin's username from the database
$admin_id = $_SESSION['admin_id'];
$query = "SELECT username FROM admins WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$username = $admin['username'] ?? 'Admin'; // Fallback if username not found

// Fetch all enrollments
$query = "SELECT enrollments.id, enrollments.user_id, enrollments.course_id, enrollments.enrolled_at, 
                 userss.first_name, userss.last_name, courses.title AS course_title, enrollments.status AS enrollment_status
          FROM enrollments
          JOIN userss ON enrollments.user_id = userss.id
          JOIN courses ON enrollments.course_id = courses.id";
$result = $db->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Enrollments</title>
    <link rel="stylesheet" href="admin_style.css">
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
        <div class="welcome-section">
            <h2>Welcome, <?= htmlspecialchars($username); ?></h2>
        </div>

        <!-- Enrollments Table -->
        <div class="enrollments-table">
            <h3>All Enrollments</h3>
            <table>
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Course Title</th>
                        <th>Enrollment Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']) ?></td>
                                <td><?= htmlspecialchars($row['course_title']) ?></td>
                                <td><?= htmlspecialchars($row['enrolled_at']) ?></td>
                                <td><?= htmlspecialchars($row['enrollment_status'] == 1 ? 'Approved' : 'Pending') ?></td>
                                <td>
                                    <?php if ($row['enrollment_status'] == 0): ?>
                                        <a href="approve_enrollment.php?id=<?= $row['id'] ?>" class="btn-small">Approve</a>
                                    <?php endif; ?>
                                    <a href="view_enrollment.php?id=<?= $row['id'] ?>" class="btn-small">View</a>
                                    <a href="delete_enrollment.php?id=<?= $row['id'] ?>" class="btn-small delete-btn">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No enrollments found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
