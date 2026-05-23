<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Admas University</title>
    <link rel="icon" href="images/logos/AU Logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            color: #333;
            line-height: 1.6;
        }

        /* Header Section */
        .header {
            background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
            padding: 0 6%;
            position: relative;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #ffc107, #ff9800);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            position: relative;
            z-index: 2;
        }

        .log-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid white;
            padding: 5px;
            background: white;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .log-img:hover {
            transform: scale(1.05) rotate(5deg);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .nav-links {
            flex: 1;
            text-align: right;
        }

        .nav-links ul {
            list-style: none;
            display: flex;
            justify-content: flex-end;
            gap: 20px;
        }

        .nav-links ul li {
            position: relative;
        }

        .nav-links ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            padding: 10px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: block;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .nav-links ul li a:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #ffc107;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .nav-links ul li a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 3px;
            background: #ffc107;
            transition: all 0.3s ease;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .nav-links ul li a:hover::after {
            width: 80%;
        }

        /* Menu Toggle Button */
        .menu-toggle {
            display: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            background: rgba(0, 0, 0, 0.2);
            padding: 10px;
            border-radius: 5px;
            transition: all 0.3s ease;
            border: none;
            width: 50px;
            height: 50px;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1000;
        }

        .menu-toggle:hover {
            background: rgba(0, 0, 0, 0.3);
            transform: scale(1.1);
        }

        .menu-toggle i {
            transition: transform 0.3s ease;
        }

        .menu-toggle.active i.fa-bars {
            display: none;
        }

        .menu-toggle.active i.fa-times {
            display: block;
        }

        .menu-toggle i.fa-times {
            display: none;
        }

        /* Page Title Section */
        .page-title {
            background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
            padding: 60px 6%;
            text-align: center;
            color: white;
            margin-top: 0;
        }

        .page-title h1 {
            font-size: 48px;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 1s ease-out;
            margin: 0;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Location Section */
        .location {
            padding: 80px 6%;
            background: white;
            text-align: center;
        }

        .location iframe {
            width: 100%;
            max-width: 900px;
            height: 450px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            border: 5px solid white;
            transition: all 0.4s ease;
        }

        .location iframe:hover {
            transform: scale(1.01);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.25);
        }

        /* Contact Us Section */
        .contact-us {
            padding: 80px 6%;
            background: #f8fafc;
        }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 50px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .contact-col {
            flex: 1;
        }

        .contact-col div {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 40px;
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-left: 4px solid #003b6f;
        }

        .contact-col div:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            border-left-color: #ffc107;
        }

        .contact-col i {
            font-size: 24px;
            color: #003b6f;
            background: rgba(0, 59, 111, 0.1);
            padding: 15px;
            border-radius: 10px;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .contact-col div:hover i {
            background: #003b6f;
            color: white;
            transform: scale(1.1);
        }

        .contact-col span h5 {
            color: #003b6f;
            font-size: 20px;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .contact-col span p {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
        }

        /* Contact Form */
        .contact-col form {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .contact-col input,
        .contact-col textarea {
            width: 100%;
            padding: 18px 20px;
            background: #f8fafc;
            border: 2px solid #e0e6ed;
            border-radius: 12px;
            font-size: 16px;
            color: #333;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .contact-col input:focus,
        .contact-col textarea:focus {
            outline: none;
            border-color: #003b6f;
            background: white;
            box-shadow: 0 0 0 3px rgba(0, 59, 111, 0.1);
        }

        .contact-col textarea {
            resize: vertical;
            min-height: 150px;
        }

        .contact-col input::placeholder,
        .contact-col textarea::placeholder {
            color: #8a9aad;
        }

        .btn {
            padding: 18px 30px;
            background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(0, 59, 111, 0.2);
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }

        .btn:hover {
            background: linear-gradient(135deg, #005fa3 0%, #003b6f 100%);
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 59, 111, 0.3);
            letter-spacing: 1.5px;
        }

        .btn:active {
            transform: translateY(-2px);
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255, 255, 255, 0.2), 
                transparent);
            transition: left 0.7s;
        }

        .btn:hover::before {
            left: 100%;
        }

        /* Footer Section */
        .foot {
            background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
            color: white;
            padding: 60px 6% 30px;
            text-align: center;
            position: relative;
        }

        .foot::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #ffc107, #ff9800);
        }

        .foot h4 {
            font-size: 28px;
            margin-bottom: 20px;
            font-weight: 700;
            color: #ffc107;
        }

        .foot h3 {
            font-size: 24px;
            margin: 40px 0 20px;
            font-weight: 600;
            color: white;
        }

        .foot p {
            max-width: 800px;
            margin: 0 auto 25px;
            font-size: 16px;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.9);
        }

        .icons {
            display: flex;
            justify-content: center;
            gap: 25px;
            margin: 30px 0;
            flex-wrap: wrap;
        }

        .icons i {
            background: rgba(255, 255, 255, 0.1);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 20px !important;
        }

        .icons i:hover {
            background: white;
            transform: translateY(-5px) scale(1.1);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .foot > p:last-child {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .row {
                flex-direction: column;
                gap: 40px;
            }
            
            .contact-col {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                position: fixed;
                top: 0;
                right: -300px;
                width: 300px;
                height: 100vh;
                background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
                padding: 100px 0 0 30px;
                transition: right 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
                z-index: 999;
                box-shadow: -5px 0 30px rgba(0, 0, 0, 0.2);
            }

            .nav-links.active {
                right: 0;
            }

            .nav-links ul {
                display: block;
            }

            .nav-links ul li {
                margin: 25px 0;
            }

            .nav-links ul li a {
                font-size: 18px;
                padding: 15px 25px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                gap: 15px;
            }

            .nav-links ul li a i {
                font-size: 20px;
                width: 30px;
            }

            .menu-toggle {
                display: flex;
            }

            .page-title {
                padding: 40px 4%;
            }

            .page-title h1 {
                font-size: 36px;
            }

            .location {
                padding: 50px 4%;
            }

            .contact-us {
                padding: 50px 4%;
            }

            .contact-col form {
                padding: 30px;
            }

            .foot {
                padding: 40px 4% 20px;
            }

            .foot h4 {
                font-size: 24px;
            }

            .foot h3 {
                font-size: 20px;
            }

            .icons {
                gap: 15px;
            }

            .icons i {
                width: 45px;
                height: 45px;
                font-size: 18px !important;
            }
        }

        @media (max-width: 480px) {
            .log-img {
                width: 60px;
                height: 60px;
            }

            .page-title h1 {
                font-size: 28px;
            }

            .location iframe {
                height: 300px;
            }

            .contact-col div {
                padding: 20px;
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .contact-col i {
                margin: 0 auto;
            }

            .btn {
                padding: 16px;
                font-size: 16px;
            }

            .foot h4 {
                font-size: 22px;
            }

            .foot h3 {
                font-size: 18px;
            }

            .icons {
                gap: 12px;
            }

            .icons i {
                width: 40px;
                height: 40px;
                font-size: 16px !important;
            }
        }

        /* Overlay for mobile menu */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 998;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .overlay.active {
            display: block;
            opacity: 1;
        }
    </style>
</head>
<body>
    <!-- Overlay for mobile menu -->
    <div class="overlay" id="overlay"></div>

    <!-- Header Section -->
    <section class="header">
        <nav>
            <a href="index.php"><img class="log-img" src="images/logos/AU Logo.png" alt="AU Logo"></a>

            <div class="nav-links" id="navlinks">
                <ul>
                    <li id="home-nav">
                        <a href="Index.php">
                            <i class="fas fa-home"></i>
                            <span>HOME</span>
                        </a>
                    </li>
                    <li id="complain-nav">
                        <a href="login.php" target="_blank">
                            <i class="fas fa-file-alt"></i>
                            <span>COMPLAIN</span>
                        </a>
                    </li>   
                    <li id="contact-nav">
                        <a href="ContactUs.php">
                            <i class="fas fa-phone-alt"></i>
                            <span>CONTACT</span>
                        </a>
                    </li>
                    <li id="about-nav">
                        <a href="About.php">
                            <i class="fas fa-info-circle"></i>
                            <span>ABOUT</span>
                        </a>
                    </li>
                    <li id="login-nav">
                        <a href="choose_login.php">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>LOGIN</span>
                        </a>
                    </li>
                </ul>
            </div>
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
                <i class="fas fa-times"></i>
            </button>
        </nav>
    </section>

    <!-- Page Title Section -->
    <section class="page-title">
        <h1>Contact Us</h1>
    </section>

    <!-- Location Section -->
    <section class="location">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1657.0162127568562!2d38.7239169058706!3d8.965610924779932!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x164b8199a2b42527%3A0x1d9b18d363b56e2a!2zQWRtYXMgVW5pdmVyc2l0eSBKZW1vIENhbXB1cyB8IOGKoOGLteGIm-GItSDhi67hipXhiajhiK3hiLXhibIg4YyA4YieIE5lYml5YXQgYnJhbmNo!5e0!3m2!1sen!2set!4v1762619534095!5m2!1sen!2set" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </section>

    <!-- Contact Form Section -->
    <section class="contact-us">
        <div class="row">
            <div class="contact-col">
                <div>
                    <i class="fas fa-map-marker-alt"></i>
                    <span>
                        <h5>Mekanisa Campus</h5>
                        <p>Addis Ababa, Ethiopia</p>
                    </span>
                </div>
                <div>
                    <i class="fas fa-phone"></i>
                    <span>
                        <h5>+251 11 123 4567</h5>
                        <p>Monday to Sunday, 8:00 AM - 6:00 PM</p>
                    </span>
                </div>
                <div>
                    <i class="fas fa-envelope"></i>
                    <span>
                        <h5>info@admasuniversity.edu.et</h5>
                        <p>Email us your queries anytime</p>
                    </span>
                </div>
            </div>
            <div class="contact-col">
                <form name="contactForm" action="ContactDB.php" method="post" id="contactForm">
                    <input type="text" name="fullname" placeholder="Enter your name" required>
                    <input type="email" name="email" placeholder="Enter your Email" required>
                    <input type="text" name="subject" placeholder="Enter your subject" required>
                    <textarea rows="8" name="message" placeholder="Your message..."></textarea>
                    <button type="submit" class="btn">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <Section class="foot">
        <h4>About Us</h4>
        <p>We are a team of Admas University students dedicated to developing a digital system that makes complaint handling faster, easier, and more transparent.</p>
        <h3>Follow Us</h3>
        <div class="icons">
            <i class="fa-brands fa-facebook fa-beat" style="color: #005eff;"></i>
            <i class="fa-brands fa-youtube fa-beat" style="color: #ff0000;"></i>
            <i class="fa-brands fa-tiktok fa-beat" style="color: #000000;"></i>
            <i class="fa-brands fa-instagram fa-beat" style="color: #ff3f0f;"></i>
            <i class="fa-brands fa-whatsapp fa-beat" style="color: #00ff00;"></i>
            <i class="fa-brands fa-linkedin fa-beat" style="color: #0874d9ff;"></i>
            <i class="fa-brands fa-x-twitter fa-beat" style="color: #0b0c0e;"></i>
        </div>
        <p>&copy; 2025 Admas University&trade;. All Rights Reserved</p>
    </Section>

    <script>
        const navlinks = document.getElementById("navlinks");
        const overlay = document.getElementById("overlay");
        const menuToggle = document.getElementById("menuToggle");
        const contactForm = document.getElementById("contactForm");

        // Toggle menu function
        function toggleMenu() {
            const isMenuOpen = navlinks.classList.contains('active');
            
            if (isMenuOpen) {
                // Close menu
                navlinks.classList.remove('active');
                overlay.classList.remove('active');
                menuToggle.classList.remove('active');
                document.body.style.overflow = 'auto';
            } else {
                // Open menu
                navlinks.classList.add('active');
                overlay.classList.add('active');
                menuToggle.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        // Add click event to menu toggle button
        menuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleMenu();
        });

        // Close menu when clicking on overlay
        overlay.addEventListener('click', toggleMenu);

        // Close menu when clicking on a navigation link
        const navItems = document.querySelectorAll('.nav-links a');
        navItems.forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    toggleMenu();
                }
            });
        });

        // Close menu when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const isMenuOpen = navlinks.classList.contains('active');
            const isMenuClicked = navlinks.contains(event.target);
            const isMenuButtonClicked = event.target.closest('#menuToggle');
            
            if (isMenuOpen && !isMenuClicked && !isMenuButtonClicked) {
                toggleMenu();
            }
        });

        // Close menu with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && navlinks.classList.contains('active')) {
                toggleMenu();
            }
        });

        // Form validation
        contactForm.addEventListener('submit', function(e) {
            const fullname = this.querySelector('input[name="fullname"]');
            const email = this.querySelector('input[name="email"]');
            const subject = this.querySelector('input[name="subject"]');
            const message = this.querySelector('textarea[name="message"]');
            
            // Basic validation
            if (!fullname.value.trim()) {
                e.preventDefault();
                alert('Please enter your name');
                fullname.focus();
                return false;
            }
            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) {
                e.preventDefault();
                alert('Please enter a valid email address');
                email.focus();
                return false;
            }
            
            if (!subject.value.trim()) {
                e.preventDefault();
                alert('Please enter a subject');
                subject.focus();
                return false;
            }
            
            if (!message.value.trim()) {
                e.preventDefault();
                alert('Please enter your message');
                message.focus();
                return false;
            }
            
            return true;
        });

        // Highlight current page in navigation
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname.split('/').pop();
            const navItems = document.querySelectorAll('.nav-links a');
            
            navItems.forEach(item => {
                const href = item.getAttribute('href');
                if (href === currentPage || (currentPage === '' && href === 'Index.php')) {
                    item.style.color = '#ffc107';
                    item.style.fontWeight = '700';
                    item.style.background = 'rgba(255, 255, 255, 0.1)';
                }
            });
        });
    </script>
</body>
</html>