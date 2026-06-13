<?php
// Define dynamic content
$title = "Courses - G Mindset Education";
$currentYear = date("Y");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="course1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
</head>
<body>

    <!-- Banner Section -->
    <div class="inner-banner" style="background-image: url('firstpage/bg.jpg');">
        <div class="inner-overlay"></div>
        <nav>
            <a href="#"><img src="logo.png" alt="logo"></a>
            <div class="nav-links">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="aboutus.php">About Us</a></li>
                    <li><a href="#">Courses</a></li>
                    <li><a href="login.html" class="btn red">Sign In</a></li>
                </ul>
            </div>
        </nav>
    </div>

    <!-- Sign In Section -->
    <div class="signin-container">
        <h2>You must sign in first to view courses</h2>
        <br><br><br><br><br><br><br>
        <a href="login.html" class="btn red">Sign In</a>
    </div>

    <!-- Footer -->
    <div class="footer">
        <!-- <div class="About">
            <p class="a">About</p>
            <p class="b"><a href="about.php">About Us</a></p>
            <p class="b"><a href="#">Courses</a></p>
            <p class="b">Blog</p>
        </div>
        <div class="contact">
            <p class="c">Contact</p>
            <p class="f">E-Mail</p>
            <p class="f">Whatsapp</p>
            <p class="f">Phone Number</p>
        </div>
        <div class="legal">
            <p class="i">Legal & Accessibility</p>
            <p class="e">Privacy & Policy</p>
            <p class="e">Term & Condition</p>
        </div> -->
        <div class="lo">
            <img src="firstpage/footerimg.png" alt="">
        </div>
    </div>

    <div class="last">
        <p>Copyright &copy; <?php echo $currentYear; ?> G-Mindset Education. All Rights Reserved.</p>
    </div>

</body>
</html>
