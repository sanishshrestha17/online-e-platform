<?php
// Define dynamic content if needed
$title = "Document";
$year = date("Y");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <section class="header">
        <nav>
            <a href=""><img src="logo.png" alt="logo"></a>
            <div class="nav-links">
                <ul>
                    <li><a href="">Home</a></li>
                    <li><a href="aboutus.php">About Us</a></li>
                    <li><a href="courses1.php">Courses</a></li>
                    <li><a class="red" href="login.html">Sign In</a></li>
                </ul>
            </div>
        </nav>

        <div class="text-box">
            <h1>World's Largest Online University</h1>
            <p>
                G Mindset Education would be an online platform focused on fostering a growth mindset. 
                It would offer personalized learning paths, encouraging students to embrace challenges, 
                learn from feedback, and develop resilience. The goal would be to promote continuous 
                improvement and adaptability, essential for success in both academic and personal growth.
            </p>
            <a href="aboutus.php" class="hero">Read More</a>
        </div>
    </section>
    <br><br><br>
    
    <div class="sponser">
        <p>Trusted by over 16,000 companies and millions of learners around the world</p>
        <div class="money">
            <img src="sponser/lambo.jpg" alt="photo">
            <img src="sponser/iphone.jpg" alt="">
            <img src="sponser/sisco.png" alt="">
            <img src="sponser/vemo.png" alt="">
            <img src="sponser/redbull.png" alt="">
            <img src="sponser/tu.png" alt="">
            <img src="sponser/nisan.jpg" alt="">
            <img src="sponser/samsung.png" alt="">
        </div>
    </div>
    <div class="banner">
        Top trends for the future of work
        <p>GenAI and leadership are at the core of today's skills-based economy. Get the 2024 Global Learning & Skills Trends</p>
    </div>
    <br><br>
    <hr>
    <div class="heading">
        <p>Top Comments</p>
    </div>
    <div id="namw">
        <div class="left">
            <p class="head">"</p>
            <p class="review">G/M Education was truly a game-changer and a great guide for me as we brought Dimensional to life.</p>
            <p class="ranaA"> Nimda Dogras Rana <br>Kathmandu</p>
        </div>
        <div class="center">
            <p class="head1">"</p>
            <p class="review1">This course was outstanding, with clear, engaging content and valuable insights that greatly enhanced my understanding!</p>
            <p class="ranaB"> Vallal Dev Rana <br>Kathmandu</p>
        </div>
        <div class="right">
            <p class="head2">"</p>
            <p class="review2">An excellent course that offers comprehensive, engaging content and practical knowledge that truly makes a difference!</p>
            <p class="ranaC"> Raj Mata Rana <br>Kathmandu</p>
        </div>
    </div>
    <div class="footer">
        <!-- <div class="About">
            <p class="a">About</p>
            <p class="b">About Us</p>
            <p class="b">Courses</p>
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
        <p>Copyright &copy; <?php echo $year; ?> G-Mindset Education. All Rights Reserved.</p>
    </div>
</body>
</html>
