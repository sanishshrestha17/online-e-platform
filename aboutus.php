<?php
// Define dynamic content
$title = "About Us - G Mindset Education";
$currentYear = date("Y");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="about.css">
</head>
<body>
    <div class="inner-banner" style="background-image: url('firstpage/bg.jpg');">
        <div class="inner-overlay"></div>
        <nav>
            <a href=""><img src="logo.png" alt="logo"></a>
            <div class="nav-links">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="">About Us</a></li>
                    <li><a href="courses1.php">Courses</a></li>
                    <li><a class="red" href="login.html">Sign In</a></li>
                </ul>
            </div>
        </nav>
    </div>
    <div class="us">
        <h1>Welcome to G Mindset Education!</h1> 
        <p class="hello">
            At G Mindset Education, we are more than just an online learning platform—we are your partner in growth, self-discovery, and transformation. 
            Our mission is rooted in a simple yet powerful belief: with the right mindset and support, every individual has the potential to achieve extraordinary things.
            <br><br>
            <span class="k">Why the Growth Mindset?</span><br><br>
            The concept of a growth mindset, pioneered by psychologist Dr. Carol Dweck, has transformed how we think about success and potential...
            <!-- Content continues -->
        </p>
        
        <h2>Our Mission</h2>
        <p class="mission">
            At <span>G Mindset Education,</span> our mission is to empower individuals to embrace the transformative power of a growth mindset...
        </p>
        
        <h2>Our Vision</h2>
        <p class="vision">
            At <span>G Mindset Education,</span> our vision is to create a world where individuals, regardless of their background or circumstances...
        </p>
        
        <h2>Our Journey</h2>
        <p class="journey">
            The story of <span>G Mindset Education</span> began with a simple yet powerful idea: to create a learning platform that goes beyond textbooks...
        </p>
    </div>
    
    <div class="footer">
        <!-- <div class="About">
            <p class="a">About</p>
            <p class="b"><a href="about.php">About Us</a></p>
            <p class="b"><a href="courses1.php">Courses</a></p>
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
