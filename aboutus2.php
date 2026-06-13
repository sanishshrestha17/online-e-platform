<?php
// Start the session
session_start();

// Example of a logged-in user check
$isLoggedIn = isset($_SESSION['user_id']); // Replace 'user_id' with the session variable you use for user identification
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - G Mindset Education</title>
    <link rel="stylesheet" href="about.css">
</head>
<body>
    <div class="inner-banner" style="background-image: url('firstpage/bg.jpg');">
        <div class="inner-overlay"></div>
        <nav>
            <a href=""><img src="logo.png" alt="logo"></a>
            <div class="nav-links">
                <ul>
                    <li><a href="index2.php">Home</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="courses_list.php">Courses</a></li>
                    <?php if ($isLoggedIn): ?>
                        <li><a class="red" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="signup.php">Sign Up</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </div>
    <div class="us">
        <h1>Welcome to G Mindset Education!</h1>
        <p class="hello">
            At G Mindset Education, we are more than just an online learning platform—we are your partner in growth, self-discovery, and transformation. Our mission is rooted in a simple yet powerful belief: with the right mindset and support, every individual has the potential to achieve extraordinary things.
            <br><br>
            We welcome you to a space where learning is not limited by boundaries or predefined notions of ability. Here, the focus is on developing a growth mindset—the belief that abilities, intelligence, and talents can be cultivated through hard work, perseverance, and dedication. Whether you’re starting a new chapter in your academic journey, advancing your career, or simply seeking personal growth, G Mindset Education is designed to meet you where you are and help you move forward with confidence.
            <br><br>
            <span class="k">Why the Growth Mindset?</span><br><br>
            The concept of a growth mindset, pioneered by psychologist Dr. Carol Dweck, has transformed how we think about success and potential. It teaches us that challenges are opportunities, failures are stepping stones, and progress matters more than perfection.
            <br>
            At G Mindset Education, we’ve taken this philosophy to heart. Our platform is built to inspire and nurture the belief that no matter your starting point, you can grow, adapt, and succeed. We understand that the journey is just as important as the destination, and we’re here to support you at every step.
            <br><br>
            <h2 class="one">What Awaits You at G Mindset Education?</h2><br><br>
            <span class="k">A World of Possibilities</span><br>
            We offer a rich variety of courses, tools, and resources tailored to help you achieve your goals. From academic mastery and professional skills to personal development and habit formation, our platform is your gateway to unlocking your potential.
            <br><br>
            <span class="k">A Supportive Community</span><br>
            Growth flourishes in a nurturing environment, and that’s exactly what we provide. You’ll find yourself surrounded by like-minded learners, passionate educators, and a culture of encouragement that propels you toward success.
            <br><br>
            <span class="k">A Focus on Resilience and Empowerment</span><br>
            Life is full of challenges, but with the right mindset, every challenge becomes an opportunity to grow. At G Mindset Education, we help you build the resilience to overcome setbacks and the confidence to turn obstacles into stepping stones.
            <br><br>
            <span class="k">Learning Made Simple</span><br>
            We believe that learning never stops. Our platform is accessible, flexible, and designed to grow with you, making it easy to continue your journey of self-improvement anytime, anywhere.
            <br><br>
            <span class="k">The Invitation</span><br>
            When you join G Mindset Education, you’re not just signing up for an online platform—you’re becoming part of a movement that values growth, progress, and potential above all else. Here, success isn’t measured by grades or titles but by the effort you put in and the transformation you achieve.
            <br>
            So, take that first step. Explore your passions, challenge your limits, and embrace the belief that you are capable of more than you’ve ever imagined.
            <br>
            Welcome to <span class="k">G Mindset Education</span> where growth begins, and possibilities are endless.
        </p>
        <h2>Our Mission</h2>
        <p class="mission">
            At <span>G Mindset Education,</span> our mission is to empower individuals to embrace the transformative power of a growth mindset, enabling them to realize their full potential. We believe that the key to success lies not in innate talent or intelligence, but in the ability to learn, adapt, and persevere through challenges.
            <br>
            Our goal is to make high-quality education accessible to all, providing learners with the tools, resources, and mindset they need to excel academically, professionally, and personally. By fostering a culture of continuous improvement, we aim to inspire individuals to see failure as a stepping stone, challenges as opportunities, and growth as a lifelong pursuit.
            <br>
            We are committed to creating an environment where learners are encouraged to take risks, experiment with new ideas, and embrace feedback as a tool for growth. With a focus on resilience, adaptability, and lifelong learning, our platform strives to equip every learner with the confidence and skills necessary to thrive in an ever-evolving world.
            <br>
            At <span>G Mindset Education,</span> we understand that growth is not a linear process, and success is defined by the progress we make, no matter how small. Our mission is to provide the guidance and support you need to turn every setback into a lesson, every challenge into a growth opportunity, and every goal into an achievable reality.
            <br>
            Together, we can build a world where everyone has the opportunity to grow, learn, and become the best version of themselves.    
            <br>
            We’re not just teaching subjects; we’re teaching how to learn, grow, and transform.
        </p>
        <h2>Our Vision</h2>
        <p class="vision">
            At <span>G Mindset Education,</span> our vision is to create a world where individuals, regardless of their background or circumstances, are empowered to unlock their full potential through the transformative power of a growth mindset. We envision a future where people embrace challenges as opportunities, view failure as a natural part of the learning process, and are motivated by a desire to continually improve and evolve.
            <br>
            We strive to be a global leader in online education, known for fostering resilience, adaptability, and a passion for lifelong learning. By providing accessible, high-quality education, we aim to equip learners with the tools, knowledge, and mindset necessary to thrive in an ever-changing world.
            <br>
            Our vision goes beyond academic achievement—we aspire to shape individuals who are not only successful in their careers and studies but also confident, self-aware, and capable of overcoming any obstacle life may present. We believe that by nurturing a growth mindset in every learner, we can contribute to a more inclusive, compassionate, and innovative world.
            <br>
            At <span>G Mindset Education,</span> we see a future where the pursuit of growth is the foundation of every success story, and where everyone, regardless of their starting point, can reach their fullest potential and make a meaningful impact.
        </p>
        <h2>Our Journey</h2>
        <p class="journey">
            The story of <span>G Mindset Education</span> began with a simple yet powerful idea: to create a learning platform that goes beyond textbooks, grades, and traditional education. It was founded on the belief that the true key to success lies not in innate talent or intelligence, but in the power of mindset—the belief that with effort, persistence, and the right guidance, anyone can grow, learn, and achieve their goals.
            <br>
            Our journey started with a team of passionate educators, technologists, and lifelong learners who were deeply inspired by the concept of a growth mindset, as popularized by psychologist Dr. Carol Dweck. We recognized that education should be about more than just acquiring knowledge—it should be about empowering individuals to overcome challenges, embrace failure as part of the process, and develop the resilience to keep moving forward, no matter what.
            <br>
            With this vision in mind, G Mindset Education was born. What began as a small project quickly gained traction as we designed an online platform that offered not just traditional courses, but also the mindset, tools, and community needed to foster lasting personal and professional growth.
            <br>
            As our platform grew, so did our impact. We started reaching individuals across the globe—students striving for academic excellence, professionals advancing their careers, and individuals seeking personal transformation. With every new learner, every success story, and every challenge overcome, we were reminded of the transformative power of a positive mindset.
            <br>
            Today, <span>G Mindset Education</span> is not just a website or a service; it’s a thriving community of passionate learners, supportive mentors, and educators dedicated to empowering each other. We’ve expanded our offerings, developed new tools, and built a space where growth is celebrated, where setbacks are seen as opportunities to learn, and where everyone can chart their own unique path to success.
            <br>
            Our journey is far from over. We are constantly evolving, adapting, and learning from our experiences, just as we encourage our learners to do. We’re committed to continuously improving our platform, expanding our reach, and creating new resources that will inspire and empower even more individuals around the world.
            <br>
            At <span>G Mindset Education,</span> we believe that growth is a lifelong journey. And as we continue to grow ourselves, we’re excited to walk alongside you on your journey of transformation, learning, and achievement. Together, we can break through limits, embrace challenges, and reach new heights.
        </p>
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
        <p>Copyright &copy; <?php echo date('Y'); ?> G-Mindset Education. All Rights Reserved.</p>
    </div>
</body>
</html>
