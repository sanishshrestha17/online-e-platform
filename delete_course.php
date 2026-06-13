<?php 
include('connect.php');  
$course_id = $_GET['id'];  

// Delete the course
$query = "DELETE FROM courses WHERE id = $course_id";

if ($db->query($query)) {
    echo "Course deleted successfully!";
    header("Location: coursemanage.php");  // Redirect to course management page
} else {
    echo "Error: " . $db->error;
}
?>
