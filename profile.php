<?php
session_start(); // Start session
if (!isset($_SESSION['user_id'])) {
    header('Location: login_user.html'); // Redirect if not logged in
    exit();
}

include 'connect.php';

// Fetch user details
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM userss WHERE id = '$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2>Profile</h2>
        <form action="update_profile.php" method="POST">
            <input type="text" name="first_name" value="<?php echo $user['first_name']; ?>" required>
            <input type="text" name="last_name" value="<?php echo $user['last_name']; ?>" required>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
            <button type="submit">Update Profile</button>
        </form>
        <p><a href="dashboard.php">Back to Dashboard</a></p>
    </div>
</body>
</html>
