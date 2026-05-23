<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Admas University</title>
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
            min-height: 20vh;
            display: flex;
            flex-direction: column;
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

        .menu-toggle.active i.fa-bars {
            display: none;
        }

        .menu-toggle.active i.fa-times {
            display: block;
        }

        .menu-toggle i.fa-times {
            display: none;
        }

        /* About Us Section */
        .about-us {
            padding: 80px 6%;
            background: white;
            text-align: center;
        }

        .about-us h1 {
            color: #003b6f;
            font-size: 42px;
            margin-bottom: 50px;
            font-weight: 700;
            position: relative;
            padding-bottom: 15px;
            display: inline-block;
        }

        .about-us h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, #003b6f, #ffc107);
            border-radius: 2px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 50px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .about-col {
            flex: 1;
            text-align: left;
        }

        .about-col h1 {
            color: #003b6f;
            font-size: 36px;
            margin-bottom: 25px;
            font-weight: 700;
            line-height: 1.3;
            position: relative;
            padding-bottom: 15px;
        }

        .about-col h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #003b6f, #ffc107);
            border-radius: 2px;
        }

        .about-col p {
            color: #555;
            font-size: 17px;
            line-height: 1.8;
            margin-bottom: 30px;
            text-align: justify;
        }

        .about-col img {
            width: 100%;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            transition: all 0.4s ease;
            border: 5px solid white;
            transform: perspective(1000px) rotateY(-10deg);
        }

        .about-col img:hover {
            transform: perspective(1000px) rotateY(0deg) scale(1.02);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.25);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 30px;
            background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(0, 59, 111, 0.2);
        }

        .btn:hover {
            background: linear-gradient(135deg, #005fa3 0%, #003b6f 100%);
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 59, 111, 0.3);
            letter-spacing: 1px;
        }

        .btn i {
            transition: transform 0.3s ease;
        }

        .btn:hover i {
            transform: translateX(5px);
        }

        /* Footer Section */
        .foot {
            background: linear-gradient(135deg, #5ca9ecff 0%, #005fa3 100%);
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
                text-align: center;
            }
            
            .about-col {
                text-align: center;
            }
            
            .about-col h1::after {
                left: 50%;
                transform: translateX(-50%);
            }
            
            .about-col p {
                text-align: center;
            }
            
            .about-col img {
                transform: none;
            }
            
            .about-col img:hover {
                transform: scale(1.02);
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

            .about-us h1 {
                font-size: 32px;
                margin-bottom: 40px;
            }

            .about-us {
                padding: 50px 4%;
            }

            .about-col h1 {
                font-size: 28px;
            }

            .about-col p {
                font-size: 16px;
                text-align: center;
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

            .about-us h1 {
                font-size: 28px;
            }

            .about-col h1 {
                font-size: 24px;
            }

            .btn {
                padding: 12px 25px;
                font-size: 15px;
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
        /* Dark Mode Toggle */
.theme-toggle {
    position: fixed;
    top: 160px;
    right: 30px;
    z-index: 999;
    transition: all 0.3s ease;
}

.theme-btn {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4da6ff 0%, #80c1ff 100%);
    color: #003b6f;
    border: 3px solid white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    box-shadow: 0 8px 20px rgba(77, 166, 255, 0.4);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.theme-btn:hover {
    transform: scale(1.15) rotate(15deg);
    box-shadow: 0 12px 30px rgba(77, 166, 255, 0.6);
    background: linear-gradient(135deg, #80c1ff 0%, #4da6ff 100%);
}

/* Dark Mode Styles */
body.dark-mode {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    color: #e0e0e0;
}

/* Dark Mode Header */
body.dark-mode .header {
    background: linear-gradient(135deg, #0f3460 0%, #1a1a2e 100%);
}

body.dark-mode .header::after {
    background: linear-gradient(90deg, #ffc107, #ff9800);
}

body.dark-mode .nav-links ul li a {
    color: #e0e0e0;
}

body.dark-mode .nav-links ul li a:hover {
    color: #ffc107;
    background: rgba(255, 255, 255, 0.1);
}

body.dark-mode .nav-links ul li a::after {
    background: #ffc107;
}

/* Dark Mode About Section */
body.dark-mode .about-us {
    background: #16213e;
}

body.dark-mode .about-us h1,
body.dark-mode .about-col h1 {
    color: #ffc107;
}

body.dark-mode .about-us h1::after,
body.dark-mode .about-col h1::after {
    background: linear-gradient(90deg, #ffc107, #ff9800);
}

body.dark-mode .about-col p {
    color: #b0b0b0;
}

body.dark-mode .about-col img {
    border-color: #2c3e50;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

body.dark-mode .about-col img:hover {
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4);
}

/* Dark Mode Button */
body.dark-mode .btn {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
    color: #1a1a2e;
    box-shadow: 0 10px 30px rgba(255, 193, 7, 0.3);
}

body.dark-mode .btn:hover {
    background: linear-gradient(135deg, #ff9800 0%, #ffc107 100%);
    box-shadow: 0 20px 40px rgba(255, 193, 7, 0.4);
}

/* Dark Mode Footer */
body.dark-mode .foot {
    background: linear-gradient(135deg, #0f3460 0%, #1a1a2e 100%);
}

body.dark-mode .foot::before {
    background: linear-gradient(90deg, #ffc107, #ff9800);
}

body.dark-mode .foot h4 {
    color: #ffc107;
}

body.dark-mode .foot h3 {
    color: #e0e0e0;
}

body.dark-mode .foot p {
    color: rgba(224, 224, 224, 0.8);
}

body.dark-mode .icons i {
    background: rgba(255, 255, 255, 0.15);
    color: #ffc107;
}

body.dark-mode .icons i:hover {
    background: #ffc107;
    color: #1a1a2e;
}

body.dark-mode .foot > p:last-child {
    border-top-color: rgba(255, 255, 255, 0.2);
    color: rgba(224, 224, 224, 0.6);
}

/* Dark Mode Theme Button */
body.dark-mode .theme-btn {
    background: linear-gradient(135deg, #003b6f 0%, #00509e 100%);
    color: #ffc107;
    border: 3px solid #ffc107;
    box-shadow: 0 8px 20px rgba(255, 193, 7, 0.3);
}

body.dark-mode .theme-btn:hover {
    background: linear-gradient(135deg, #00509e 0%, #003b6f 100%);
    box-shadow: 0 12px 30px rgba(255, 193, 7, 0.4);
}

/* Dark Mode Mobile Menu */
@media (max-width: 768px) {
    body.dark-mode .nav-links {
        background: linear-gradient(135deg, #0f3460 0%, #1a1a2e 100%);
    }
    
    .theme-toggle {
        top: 160px;
        right: 20px;
    }
    
    .theme-btn {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
}

@media (max-width: 480px) {
    .theme-toggle {
        top: 90px;
        right: 15px;
    }
    
    .theme-btn {
        width: 45px;
        height: 45px;
        font-size: 18px;
    }
}
    </style>
</head>
<body>
    <!-- Overlay for mobile menu -->
    <div class="overlay" id="overlay"></div>

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
                        <a href="login.php"target="_blank">
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
<!-- Add this right after <body> -->
<!-- <div class="theme-toggle">
    <button class="theme-btn" id="themeToggle" title="Toggle Dark Mode">
        <i class="fas fa-moon"></i>
    </button>
</div> -->
    <section class="about-us">
        <h1>About US</h1>
        <div class="row">
            <div class="about-col">
                <h1>About Admas University Mekanisa Campus</h1>
                <p>Admas University Mekanisa Campus is one of the leading centers of
                    learning in Ethiopia, known for its commitment to academic excellence, 
                    innovation, and student development. The campus provides quality 
                    education across various disciplines, supported by experienced instructors 
                    and modern facilities. It continues to play a vital role in empowering students  
                    with the skills, knowledge, and values needed to contribute meaningfully 
                    to society and the nation's growth.</p>
                <a class="btn" href="https://web.admasuniversity.edu.et/" target="_blank">
                    Learn More <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="about-col">
                <img src="images/admas1.jpg" alt="Admas University Campus">
            </div>
        </div>
    </section>

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
        // Dark Mode Toggle
const themeToggle = document.getElementById('themeToggle');

function toggleDarkMode() {
    const body = document.body;
    const isDarkMode = body.classList.contains('dark-mode');
    
    if (isDarkMode) {
        body.classList.remove('dark-mode');
        themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
        localStorage.setItem('darkMode', 'false');
    } else {
        body.classList.add('dark-mode');
        themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        localStorage.setItem('darkMode', 'true');
    }
}

// Check for saved dark mode preference
document.addEventListener('DOMContentLoaded', function() {
    const savedDarkMode = localStorage.getItem('darkMode');
    
    if (savedDarkMode === 'true') {
        document.body.classList.add('dark-mode');
        themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
    }
    
    // Add event listener for theme toggle
    themeToggle.addEventListener('click', toggleDarkMode);
    
    // Highlight current page
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