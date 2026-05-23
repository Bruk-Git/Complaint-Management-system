<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/Logos/AU Logo.png">
    <title>Select Login Type</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="images/logos/AU Logo.png">
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            min-height: 100vh;
            color: #333;
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
        }

        .menu-toggle:hover {
            background: rgba(0, 0, 0, 0.3);
            transform: scale(1.1);
        }

        .menu-toggle i {
            transition: transform 0.3s ease;
        }

        /* Login Selection Box */
        .box {
            max-width: 800px;
            margin: 80px auto;
            padding: 50px 40px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 59, 111, 0.15);
            text-align: center;
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.8s ease-out;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #003b6f, #005fa3, #ffc107);
        }

        .box h2 {
            color: #003b6f;
            font-size: 36px;
            margin-bottom: 40px;
            font-weight: 700;
            position: relative;
            padding-bottom: 15px;
        }

        .box h2::after {
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

        /* Login Buttons Grid */
        .login-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            padding: 25px 20px;
            background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
            color: white;
            text-decoration: none;
            border-radius: 15px;
            font-size: 18px;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            width: 100%;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 59, 111, 0.2);
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

        .btn:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 59, 111, 0.3);
            letter-spacing: 1px;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:active {
            transform: translateY(-4px);
        }

        .btn i {
            font-size: 24px;
            transition: all 0.3s ease;
        }

        .btn:hover i {
            transform: scale(1.2) rotate(10deg);
        }

        /* Specific button colors */
        .btn:nth-child(1) {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.2);
        }

        .btn:nth-child(2) {
            background: linear-gradient(135deg, #0078d4 0%, #005fa3 100%);
            box-shadow: 0 10px 30px rgba(0, 120, 212, 0.2);
        }

        .btn:nth-child(3) {
            background: linear-gradient(135deg, #17a2b8 0%, #0dcaf0 100%);
            box-shadow: 0 10px 30px rgba(23, 162, 184, 0.2);
        }

        .btn:nth-child(4) {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            box-shadow: 0 10px 30px rgba(108, 117, 125, 0.2);
        }

        .btn:nth-child(5) {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            box-shadow: 0 10px 30px rgba(220, 53, 69, 0.2);
        }

        .btn:hover:nth-child(1) {
            box-shadow: 0 20px 40px rgba(40, 167, 69, 0.3);
        }

        .btn:hover:nth-child(2) {
            box-shadow: 0 20px 40px rgba(0, 120, 212, 0.3);
        }

        .btn:hover:nth-child(3) {
            box-shadow: 0 20px 40px rgba(23, 162, 184, 0.3);
        }

        .btn:hover:nth-child(4) {
            box-shadow: 0 20px 40px rgba(108, 117, 125, 0.3);
        }

        .btn:hover:nth-child(5) {
            box-shadow: 0 20px 40px rgba(220, 53, 69, 0.3);
        }

        /* Button icons */
        .btn i {
            background: rgba(255, 255, 255, 0.1);
            padding: 12px;
            border-radius: 10px;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        /* Welcome Message */
        .welcome-message {
            color: #666;
            font-size: 18px;
            margin-bottom: 40px;
            line-height: 1.6;
            padding: 0 20px;
        }

        .welcome-message strong {
            color: #003b6f;
            font-weight: 700;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 30px 0;
            margin-top: 50px;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #e0e0e0;
            background: white;
        }

        .footer a {
            color: #005fa3;
            text-decoration: none;
            font-weight: 600;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
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
                animation: none;
                opacity: 1;
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
                position: relative;
                z-index: 1000;
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

            .box {
                margin: 40px 20px;
                padding: 40px 25px;
            }

            .box h2 {
                font-size: 28px;
            }

            .login-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .btn {
                padding: 22px 20px;
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .log-img {
                width: 60px;
                height: 60px;
            }

            .box {
                padding: 30px 20px;
                margin: 30px 15px;
            }

            .box h2 {
                font-size: 24px;
            }

            .btn {
                padding: 20px 15px;
                font-size: 15px;
            }

            .btn i {
                width: 40px;
                height: 40px;
                padding: 10px;
                font-size: 18px;
            }

            .welcome-message {
                font-size: 16px;
            }
        }

        /* Animation for menu items */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .nav-links:not(.active) ul li {
            animation: slideIn 0.5s ease-out forwards;
            opacity: 0;
        }

        .nav-links:not(.active) ul li:nth-child(1) { animation-delay: 0.1s; }
        .nav-links:not(.active) ul li:nth-child(2) { animation-delay: 0.2s; }
        .nav-links:not(.active) ul li:nth-child(3) { animation-delay: 0.3s; }
        .nav-links:not(.active) ul li:nth-child(4) { animation-delay: 0.4s; }
        .nav-links:not(.active) ul li:nth-child(5) { animation-delay: 0.5s; }

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
    <!-- Header Section -->
    <section class="header">
        <nav>
            <a href="index.php"><img class="log-img" src="images/logos/AU Logo.png" alt="AU Logo"></a>
            <div class="nav-links" id="navlinks">
                <ul>
                    <li id="home-nav">
                        <a href="Index.php" >
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
                            <span>Login</span>
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

    <!-- Overlay for mobile menu -->
    <div class="overlay" id="overlay"></div>

    <!-- Login Selection Box -->
    <div class="box">
        <h2>Select Login Type</h2>
        <p class="welcome-message">
            Welcome to the <strong>Complaint Management System</strong>. Please select your role to continue.
        </p>
        
        <div class="login-grid">
            <a href="Login.php" target="_blank" class="btn">
                <i class="fas fa-user-graduate"></i>
                <span>Student Login</span>
            </a>
            <a href="teacher_login.php" target="_blank" class="btn">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Teacher Login</span>
            </a>
            <a href="department_login.php" target="_blank" class="btn">
                <i class="fas fa-building"></i>
                <span>Department Login</span>
            </a>
            <a href="dean_login.php" target="_blank" class="btn">
                <i class="fas fa-user-tie"></i>
                <span>Dean Login</span>
            </a>
            <a href="admin_login.php" target="_blank" class="btn">
                <i class="fas fa-shield-alt"></i>
                <span>Admin Login</span>
            </a>
        </div>

        <div style="margin-top: 40px; color: #666; font-size: 14px;">
            <p><i class="fas fa-info-circle" style="color: #005fa3;"></i> 
            Need help? Contact system administrator at <strong>admin@university.edu</strong></p>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 Complaint Management System. All rights reserved. | 
        <a href="privacy.php" target="_blank">Privacy Policy</a> | 
        <a href="terms.php" target="_blank">Terms of Service</a></p>
        <p style="margin-top: 10px; font-size: 12px; color: #888;">
            <i class="fas fa-lock"></i> Secure Connection • <i class="fas fa-server"></i> System v2.1.4
        </p>
    </div>

    <script>
        // Mobile Menu Toggle
        const navLinks = document.getElementById('navlinks');
        const overlay = document.getElementById('overlay');
        const menuToggle = document.getElementById('menuToggle');
        const menuIconBars = menuToggle.querySelector('.fa-bars');
        const menuIconTimes = menuToggle.querySelector('.fa-times');

        function toggleMenu() {
            const isMenuOpen = navLinks.classList.contains('active');
            
            if (isMenuOpen) {
                // Close menu
                navLinks.classList.remove('active');
                overlay.classList.remove('active');
                menuToggle.classList.remove('active');
                document.body.style.overflow = 'auto';
            } else {
                // Open menu
                navLinks.classList.add('active');
                overlay.classList.add('active');
                menuToggle.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        // Toggle menu when clicking the hamburger button
        menuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleMenu();
        });

        // Close menu when clicking on overlay
        overlay.addEventListener('click', toggleMenu);

        // Close menu when clicking on a navigation link (will open in new tab)
        const navItems = document.querySelectorAll('.nav-links a');
        navItems.forEach(item => {
            item.addEventListener('click', function(e) {
                // Menu will close automatically since page opens in new tab
                // But we'll still close it for mobile users
                if (window.innerWidth <= 768) {
                    toggleMenu();
                }
                
                // Add slight delay for visual feedback
                setTimeout(() => {
                    // Link opens in new tab via target="_blank"
                }, 300);
            });
        });

        // Close menu when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const isMenuOpen = navLinks.classList.contains('active');
            const isMenuClicked = navLinks.contains(event.target);
            const isMenuButtonClicked = event.target.closest('.menu-toggle');
            
            if (isMenuOpen && !isMenuClicked && !isMenuButtonClicked) {
                toggleMenu();
            }
        });

        // Close menu with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && navLinks.classList.contains('active')) {
                toggleMenu();
            }
        });

        // Highlight current page in navigation
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname.split('/').pop();
            const navItems = document.querySelectorAll('.nav-links a');
            
            navItems.forEach(item => {
                const href = item.getAttribute('href');
                if (href === currentPage || 
                    (currentPage === '' && href === 'Index.php') ||
                    (currentPage === 'choose_login.php' && item.querySelector('span').textContent.includes('Login'))) {
                    item.style.color = '#ffc107';
                    item.style.fontWeight = '700';
                    item.style.background = 'rgba(255, 255, 255, 0.1)';
                }
            });

            // Add animation delay to buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach((btn, index) => {
                btn.style.animationDelay = `${index * 0.1}s`;
                btn.style.animation = 'fadeIn 0.5s ease-out forwards';
                btn.style.opacity = '0';
                btn.style.animationFillMode = 'forwards';
            });
        });

        // Prevent body scroll when menu is open
        document.addEventListener('DOMContentLoaded', function() {
            const originalStyle = document.body.style.cssText;
            
            // Reset body style when menu closes
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        if (!navLinks.classList.contains('active')) {
                            document.body.style.cssText = originalStyle;
                        }
                    }
                });
            });
            
            observer.observe(navLinks, { attributes: true });
        });
    </script>
</body>
</html>