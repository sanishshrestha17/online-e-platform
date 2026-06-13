<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

include 'connect.php';

// Fetch admin username
$admin_id = $_SESSION['admin_id'];
$stmt = $db->prepare("SELECT username FROM admins WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();
$username = $admin['username'] ?? 'Admin';

// Handle approve action
if (isset($_GET['approve'])) {
    $enrollment_id = intval($_GET['approve']);
    $stmt = $db->prepare("UPDATE enrollments SET status = 1 WHERE id = ?");
    $stmt->bind_param("i", $enrollment_id);
    $stmt->execute();
    header("Location: admin_enrollments.php?success=approved");
    exit;
}

// Handle delete action
if (isset($_GET['delete'])) {
    $enrollment_id = intval($_GET['delete']);
    $stmt = $db->prepare("DELETE FROM enrollments WHERE id = ?");
    $stmt->bind_param("i", $enrollment_id);
    $stmt->execute();
    header("Location: admin_enrollments.php?success=deleted");
    exit;
}

// Fetch all enrollments
$query = "SELECT enrollments.id, enrollments.user_id, enrollments.course_id, enrollments.enrolled_at,
                 userss.first_name, userss.last_name, userss.email,
                 courses.title AS course_title, enrollments.status AS enrollment_status
          FROM enrollments
          JOIN userss ON enrollments.user_id = userss.id
          JOIN courses ON enrollments.course_id = courses.id
          ORDER BY enrollments.enrolled_at DESC";
$result = $db->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Enrollments</title>
    <link rel="stylesheet" href="admin_style.css">
    <style>
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: bold;
        }
        .badge.approved { background-color: #d4edda; color: #155724; }
        .badge.pending  { background-color: #fff3cd; color: #856404; }

        .btn-small.approve-btn { background-color: #4caf50; }
        .btn-small.approve-btn:hover { background-color: #388e3c; }
        .btn-small.view-btn { background-color: #3b5998; }
        .btn-small.view-btn:hover { background-color: #2d4373; }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .modal-overlay.active { display: flex; }
        .modal {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            max-width: 420px;
            width: 90%;
            text-align: center;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        }
        .modal h3 { margin-bottom: 10px; color: #333; }
        .modal p  { color: #666; margin-bottom: 24px; }
        .modal-buttons { display: flex; gap: 12px; justify-content: center; }
        .modal-buttons a, .modal-buttons button {
            padding: 10px 24px;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
            text-decoration: none;
            border: none;
        }
        .btn-cancel { background: #eee; color: #333; }
        .btn-cancel:hover { background: #ddd; }
        .btn-confirm-delete { background: #ff6b6b; color: #fff; }
        .btn-confirm-delete:hover { background: #ff3b3b; }

        /* View modal */
        .view-modal .modal { max-width: 500px; text-align: left; }
        .view-modal .detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; font-size: 15px; }
        .view-modal .detail-row:last-child { border-bottom: none; }
        .view-modal .detail-label { font-weight: bold; color: #555; }

        .flash { padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; text-align: center; font-size: 15px; }
        .flash.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>

    <header class="admin-header">
        <div class="logo">
            <a href="admin_panel.php"><img src="logo.png" alt="Logo"></a>
        </div>
        <div class="logout">
            <a href="adminlogout.php">Logout</a>
        </div>
    </header>

    <main class="admin-main">
        <div class="welcome-section">
            <h2>Welcome, <?= htmlspecialchars($username) ?></h2>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="flash success">
                <?= $_GET['success'] === 'approved' ? '✅ Enrollment approved successfully.' : '🗑️ Enrollment deleted successfully.' ?>
            </div>
        <?php endif; ?>

        <div class="enrollments-table">
            <h3>All Enrollments</h3>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User Name</th>
                        <th>Course Title</th>
                        <th>Enrolled On</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result && $result->num_rows > 0):
                    $i = 1;
                    while ($row = $result->fetch_assoc()):
                        $is_approved = $row['enrollment_status'] == 1;
                ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                        <td><?= htmlspecialchars($row['course_title']) ?></td>
                        <td><?= htmlspecialchars($row['enrolled_at']) ?></td>
                        <td>
                            <span class="badge <?= $is_approved ? 'approved' : 'pending' ?>">
                                <?= $is_approved ? 'Approved' : 'Pending' ?>
                            </span>
                        </td>
                        <td>
                            <?php if (!$is_approved): ?>
                                <a href="admin_enrollments.php?approve=<?= $row['id'] ?>"
                                   class="btn-small approve-btn"
                                   onclick="return confirm('Approve this enrollment?')">Approve</a>
                            <?php endif; ?>

                            <button class="btn-small view-btn"
                                onclick="openView(
                                    '<?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?>',
                                    '<?= htmlspecialchars($row['email']) ?>',
                                    '<?= htmlspecialchars($row['course_title']) ?>',
                                    '<?= htmlspecialchars($row['enrolled_at']) ?>',
                                    '<?= $is_approved ? 'Approved' : 'Pending' ?>'
                                )">View</button>

                            <button class="btn-small delete-btn"
                                onclick="openDelete(<?= $row['id'] ?>, '<?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?>')">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="6">No enrollments found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal">
            <h3>Delete Enrollment</h3>
            <p id="deleteMsg">Are you sure you want to delete this enrollment?</p>
            <div class="modal-buttons">
                <button class="btn-cancel" onclick="closeDelete()">Cancel</button>
                <a id="deleteConfirmBtn" href="#" class="btn-confirm-delete">Yes, Delete</a>
            </div>
        </div>
    </div>

    <!-- View Details Modal -->
    <div class="modal-overlay view-modal" id="viewModal">
        <div class="modal">
            <h3>Enrollment Details</h3>
            <div class="detail-row"><span class="detail-label">Name</span><span id="vName"></span></div>
            <div class="detail-row"><span class="detail-label">Email</span><span id="vEmail"></span></div>
            <div class="detail-row"><span class="detail-label">Course</span><span id="vCourse"></span></div>
            <div class="detail-row"><span class="detail-label">Enrolled On</span><span id="vDate"></span></div>
            <div class="detail-row"><span class="detail-label">Status</span><span id="vStatus"></span></div>
            <div class="modal-buttons" style="margin-top:20px;">
                <button class="btn-cancel" onclick="closeView()">Close</button>
            </div>
        </div>
    </div>

    <script>
        function openDelete(id, name) {
            document.getElementById('deleteMsg').textContent = 'Are you sure you want to delete the enrollment for "' + name + '"?';
            document.getElementById('deleteConfirmBtn').href = 'admin_enrollments.php?delete=' + id;
            document.getElementById('deleteModal').classList.add('active');
        }
        function closeDelete() {
            document.getElementById('deleteModal').classList.remove('active');
        }
        function openView(name, email, course, date, status) {
            document.getElementById('vName').textContent   = name;
            document.getElementById('vEmail').textContent  = email;
            document.getElementById('vCourse').textContent = course;
            document.getElementById('vDate').textContent   = date;
            document.getElementById('vStatus').textContent = status;
            document.getElementById('viewModal').classList.add('active');
        }
        function closeView() {
            document.getElementById('viewModal').classList.remove('active');
        }
        // Close modals on overlay click
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', function(e) {
                if (e.target === this) this.classList.remove('active');
            });
        });
    </script>

</body>
</html>