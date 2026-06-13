<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not authenticated
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <section class="header">
        <nav>
            <a href=""><img src="firstpage/logia.png" alt="logo"></a>
            <div class="nav-links">
                <ul>
                    <li>
                        <span style="font-weight: bold; font-size: 24px; color: red;">
                            Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </span>
                    </li>
                    <li><a href="">Home</a></li>
                    <li><a href="aboutus2.php">About Us</a></li>
                    <li><a href="courses_list.php">Courses</a></li>
                    <li><a class="red" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </nav>

    <div class="text-box">
        <h1>World's Largest Online University</h1>
        <P>G Mindset Education would be an online platform focused on fostering a growth mindset. It would offer personalized learning paths, encouraging students to embrace challenges, learn from feedback, and develop resilience. The goal would be to promote continuous improvement and adaptability, essential for success in both academic and personal growth. </P>
        <a href="aboutus2.php" class="hero">Read More</a>
        
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
        <P>GenAI and leadership are at the core of today's skills-based economy. Get the 2024 Global Learning & Skills Trends</P>
    </div>
    <hr>
    <br><br>
    <div class="video">
        <div class="v1">
            <img src="video/v1.jpg" alt="">
            <h3>The complete 2025 react js Course</h3>
            <p>Learn from the best </p>
            <h2>Code Stoic</h2>
            <button><a href="https://youtu.be/MHn66JJH5zs?si=zz_MbUeifVPsZG-2 " style="color: white;" target="_blank"> View</a></button>
        </div>
        <div class="v2">
            <img src="video/v2.jpg" alt="photo">
            <h3> THE complete 2025  Advance Python Course</h3>
            <p>Learn from the best</p>
            <h2>Bro Code</h2>
            <button><a href="https://youtu.be/ix9cRaBkVe0?si=d9DSxkSrE3T2gP4p" style="color: white;" target="_blank"> View</a></button>
        </div>
        <div class="v3">
            <img src="video/v3.jpg" alt="">
            <h3>The complete 2025 Web-Development Course</h3>
            <p>Learn from the best</p>
            <h2>Bro Code</h2>
            <button><a href="https://youtu.be/HGTJBPNC-Gw?si=IIz-q04O-UkMUw6i" style="color: white;" target="_blank"> View</a></button>
        </div>
        <div class="v4">
            <img src="video/v4.webp" alt="">
            <h3>The complete 2025 C++ Course</h3>
            <p>Learn from the best</p>
            <h2>Bro Code</h2>
            <button><a href="https://youtu.be/-TkoO8Z07hI?si=CAgcjmWXLUGoxMp0" style="color: white;" target="_blank"> View</a></button>
        </div>
    </div>
    <hr>
    <div class="heading">
        <p>Top Comments</p>
    </div>
    <div id="namw">
        <div class="left ">
            <p class="head">"</p>
            <p class="review">G/M Education was truly a game-changer and a great guide for me as we brought Dimensional to life.</p>
            <p class="ranaA"> Nimda Dogras  Rana <br>Kathmandu</p>
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
    <div class="footer " >
        <!-- <div class="About">
            <p class="a">About </p>
            <p class="b">About US</p>
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
        <p> Copyright &copy; 2024 G-Mindset Education. All Rights Reserved.</p>
    </div>
</body>
</html>
