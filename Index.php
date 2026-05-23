<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admas University - Complaint Management System</title>
    <link rel="icon" href="images/Logos/AU Logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Override styles for hero section */
        .text-box {
            position: relative;
            padding: 100px 6%;
            text-align: center;
            color: white;
            min-height: 70vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        
        .text-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(0, 59, 111, 0.85), rgba(0, 95, 163, 0.9));
            z-index: 1;
        }
        
        .text-box .img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
        }
        
        .text-box .img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            filter: brightness(0.6) contrast(1.1);
        }
        
        .text-box h1,
        .text-box p,
        .text-box .submit-button {
            position: relative;
            z-index: 2;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
        }
        
        .text-box h1 {
            color: white;
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 25px;
            line-height: 1.3;
            max-width: 900px;
        }
        
        .text-box p {
            font-size: 1.25rem;
            max-width: 800px;
            margin: 0 auto 30px;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.95);
        }
        
        .text-box .submit-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 18px 35px;
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: #003b6f;
            text-decoration: none;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 700;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(255, 193, 7, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
            z-index: 2;
        }
        
        .text-box .submit-button:hover {
            background: linear-gradient(135deg, #ff9800 0%, #ffc107 100%);
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(255, 193, 7, 0.4);
            letter-spacing: 1.5px;
            color: #003b6f;
        }
        
        .text-box .submit-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255, 255, 255, 0.3), 
                transparent);
            transition: left 0.7s;
            z-index: -1;
        }
        
        .text-box .submit-button:hover::before {
            left: 100%;
        }
        
        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .text-box h1 {
                font-size: 2.8rem;
            }
            
            .text-box p {
                font-size: 1.1rem;
            }
        }
        
        @media (max-width: 768px) {
            .text-box {
                padding: 80px 4%;
                min-height: 60vh;
            }
            
            .text-box h1 {
                font-size: 2.2rem;
            }
            
            .text-box p {
                font-size: 1rem;
            }
            
            .text-box .submit-button {
                padding: 16px 30px;
                font-size: 16px;
            }
        }
        
        @media (max-width: 480px) {
            .text-box h1 {
                font-size: 1.8rem;
            }
            
            .text-box p {
                font-size: 0.95rem;
            }
            
            .text-box .submit-button {
                padding: 14px 25px;
                font-size: 14px;
            }
        }
        
        /* Dark mode adjustments */
        body.dark-mode .text-box::before {
            background: linear-gradient(rgba(15, 52, 96, 0.85), rgba(26, 26, 46, 0.9));
        }
        
        body.dark-mode .text-box .img img {
            filter: brightness(0.4) contrast(1.1);
        }
    </style>
</head>
        
<body>
    <!-- Dark Mode Toggle -->
    <!-- <div class="theme-toggle">
        <button class="theme-btn" id="themeToggle" title="Toggle Dark Mode">
            <i class="fas fa-moon"></i>
        </button>
    </div> -->

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

    <div class="text-box">
        <div class="img">
            <img src="images/Mekanisa.jpg" alt="Admas University Mekanisa Campus">
        </div>
        <h1>Welcome to Admas Digital Complaint Management System</h1>
        <p>
            The Admas University Complaint Management System provides a fast and transparent 
            way for students and staff to submit, track, and resolve complaints online. It 
            promotes efficient communication, accountability, and improved service delivery 
            across the university.
        </p>
        <a href="login.php" class="submit-button">Submit Your Complaint</a>
    </div>

    <section class="offer">
        <h1>How the System Helps</h1>
        <div class="row">
            <div class="offer-col">
                <h3>What We Offer</h3>
                <p>We offer a simple digital way to manage complaints.
                    The system saves time, reduces errors, and ensures every 
                    issue reaches the right office for quick resolution.</p>
            </div>
            <div class="offer-col">
                <h3>Problems We Solve</h3>
                <p>The old complaint process was slow and confusing.
                    Our new online system solves this by allowing users
                    to submit, follow up, and get responses instantly.</p>
            </div>
            <div class="offer-col">
                <h3>Our Solution</h3>
                <p>Our system makes it easier for students and staff
                    to submit and track complaints online. It replaces
                    slow, paper-based processes with a fast, transparent,
                    and user-friendly platform.</p>
            </div>
        </div>
    </section>

    <section class="feature">
        <h1>Features</h1>
        <p>These describe what your system can do and how it helps users</p>
        <div class="row">
            <div class="feature-col">
                <img src="images/complain.png" alt="Complaint Submission">
                <div class="layer"><h3>Complaint Submission</h3></div>
            </div>
            <div class="feature-col">
                <img src="images/Status.png" alt="Real-Time Complaint Tracking">
                <div class="layer"><h3>Real-Time Complaint Tracking</h3></div>
            </div>
            <div class="feature-col">
                <img src="images/Feedback.png" alt="Response and Feedback Management">
                <div class="layer"><h3>Response and Feedback Management</h3></div>
            </div>
        </div>
    </section>

    <section class="role">
        <h1>User Roles</h1>
        <p></p>
        <div class="row">
            <div class="role-col">
                <img src="images/students.png" alt="Students">
                <div><h3>Students</h3><p>Submit and track complaints</p></div>
            </div>
            <div class="role-col">
                <img src="images/teachers.jpg" alt="Teachers">
                <div><h3>Teachers</h3><p>Respond to student concerns</p></div>
            </div>
            <div class="role-col">
                <img src="images/staff.jpg" alt="Department and Management Staff">
                <div><h3>Department and Management Staff</h3><p>Review and manage complaints</p></div>
            </div>
        </div>
    </section>

    <section class="cta">
        <h1>Need Assistance?</h1>
        <p>Reach us at support@admascms.edu.et or visit the IT office for help.</p>
        <a href="contactUs.php" class="submit-button">Contact Us</a>
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

    <!-- Cookie Consent Banner -->
    <div class="cookie-consent" id="cookieConsent">
        <div class="cookie-content">
            <h3>🍪 We Use Cookies</h3>
            <p>We use cookies to enhance your browsing experience, analyze site traffic, and personalize content. By clicking "Accept All", you consent to our use of cookies. <a href="#" id="learnMore">Learn more</a></p>
        </div>
        <div class="cookie-buttons">
            <button class="cookie-btn accept-btn" id="acceptAll">Accept All</button>
            <button class="cookie-btn settings-btn" id="cookieSettings">Settings</button>
            <button class="cookie-btn reject-btn" id="rejectAll">Reject All</button>
        </div>
    </div>

    <!-- Cookie Settings Modal -->
    <div class="cookie-modal" id="cookieModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Cookie Preferences</h3>
            </div>
            <div class="modal-body">
                <div class="cookie-option">
                    <div class="option-info">
                        <h4>Essential Cookies</h4>
                        <p>Required for the website to function properly. Cannot be disabled.</p>
                    </div>
                    <label class="switch">
                        <input type="checkbox" checked disabled>
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="cookie-option">
                    <div class="option-info">
                        <h4>Analytics Cookies</h4>
                        <p>Help us understand how visitors interact with our website.</p>
                    </div>
                    <label class="switch">
                        <input type="checkbox" id="analyticsCookies">
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="cookie-option">
                    <div class="option-info">
                        <h4>Preference Cookies</h4>
                        <p>Remember your settings like theme, language, and layout preferences.</p>
                    </div>
                    <label class="switch">
                        <input type="checkbox" id="preferenceCookies" checked>
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="cookie-option">
                    <div class="option-info">
                        <h4>Dark Mode</h4>
                        <p>Remember your theme preference across sessions.</p>
                    </div>
                    <label class="switch">
                        <input type="checkbox" id="darkModeCookies">
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button class="close-btn" id="closeModal">Cancel</button>
                <button class="save-btn" id="saveSettings">Save Preferences</button>
            </div>
        </div>
    </div>

    <script>
        // Cookie Management Functions
        function setCookie(name, value, days) {
            let expires = "";
            if (days) {
                const date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/; SameSite=Lax";
        }

        function getCookie(name) {
            const nameEQ = name + "=";
            const ca = document.cookie.split(';');
            for(let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        function deleteCookie(name) {
            document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/';
        }

        // Cookie Consent Management
        function checkCookieConsent() {
            const cookieConsent = getCookie('au_cookie_consent');
            
            if (!cookieConsent) {
                // Show cookie banner after 2 seconds
                setTimeout(() => {
                    document.getElementById('cookieConsent').classList.add('show');
                }, 2000);
            } else {
                // Apply saved preferences
                applyCookiePreferences();
            }
        }

        function applyCookiePreferences() {
            const cookieConsent = getCookie('au_cookie_consent');
            
            if (cookieConsent) {
                const preferences = JSON.parse(cookieConsent);
                
                // Apply dark mode if enabled
                if (preferences.darkMode && preferences.preferences) {
                    document.body.classList.add('dark-mode');
                    document.getElementById('darkModeCookies').checked = true;
                    document.getElementById('themeToggle').innerHTML = '<i class="fas fa-sun"></i>';
                } else {
                    document.getElementById('darkModeCookies').checked = false;
                    document.getElementById('themeToggle').innerHTML = '<i class="fas fa-moon"></i>';
                }
                
                // Apply other preferences
                document.getElementById('preferenceCookies').checked = preferences.preferences || false;
                document.getElementById('analyticsCookies').checked = preferences.analytics || false;
            }
        }

        function saveCookiePreferences() {
            const preferences = {
                accepted: true,
                timestamp: new Date().toISOString(),
                preferences: document.getElementById('preferenceCookies').checked,
                analytics: document.getElementById('analyticsCookies').checked,
                darkMode: document.getElementById('darkModeCookies').checked
            };
            
            setCookie('au_cookie_consent', JSON.stringify(preferences), 365);
            document.getElementById('cookieConsent').classList.remove('show');
            document.getElementById('cookieModal').classList.remove('show');
            
            // Apply preferences immediately
            applyCookiePreferences();
            
            // Show confirmation
            showToast('Cookie preferences saved successfully!');
        }

        function showToast(message) {
            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: linear-gradient(135deg, #27ae60, #2ecc71);
                color: white;
                padding: 15px 25px;
                border-radius: 8px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.2);
                z-index: 10000;
                animation: slideIn 0.3s ease;
            `;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Dark Mode Toggle
        function toggleDarkMode() {
            const body = document.body;
            const themeToggle = document.getElementById('themeToggle');
            const isDarkMode = body.classList.contains('dark-mode');
            
            if (isDarkMode) {
                body.classList.remove('dark-mode');
                themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
                document.getElementById('darkModeCookies').checked = false;
            } else {
                body.classList.add('dark-mode');
                themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
                document.getElementById('darkModeCookies').checked = true;
            }
            
            // Save preference if cookies are accepted
            const cookieConsent = getCookie('au_cookie_consent');
            if (cookieConsent) {
                const preferences = JSON.parse(cookieConsent);
                preferences.darkMode = !isDarkMode;
                setCookie('au_cookie_consent', JSON.stringify(preferences), 365);
            }
        }

        // Menu Toggle Functions
        const navlinks = document.getElementById("navlinks");
        const overlay = document.getElementById("overlay");
        const menuToggle = document.getElementById("menuToggle");

        function toggleMenu() {
            const isMenuOpen = navlinks.classList.contains('active');
            
            if (isMenuOpen) {
                navlinks.classList.remove('active');
                overlay.classList.remove('active');
                menuToggle.classList.remove('active');
                document.body.style.overflow = 'auto';
            } else {
                navlinks.classList.add('active');
                overlay.classList.add('active');
                menuToggle.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Check cookie consent
            checkCookieConsent();
            
            // Apply saved preferences
            applyCookiePreferences();
            
            // Menu functionality
            menuToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleMenu();
            });

            overlay.addEventListener('click', toggleMenu);

            const navItems = document.querySelectorAll('.nav-links a');
            navItems.forEach(item => {
                item.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        toggleMenu();
                    }
                });
            });

            // Cookie consent events
            document.getElementById('acceptAll').addEventListener('click', function() {
                const preferences = {
                    accepted: true,
                    timestamp: new Date().toISOString(),
                    preferences: true,
                    analytics: true,
                    darkMode: document.body.classList.contains('dark-mode')
                };
                setCookie('au_cookie_consent', JSON.stringify(preferences), 365);
                document.getElementById('cookieConsent').classList.remove('show');
                showToast('All cookies accepted!');
            });

            document.getElementById('rejectAll').addEventListener('click', function() {
                const preferences = {
                    accepted: false,
                    timestamp: new Date().toISOString(),
                    preferences: false,
                    analytics: false,
                    darkMode: false
                };
                setCookie('au_cookie_consent', JSON.stringify(preferences), 365);
                document.getElementById('cookieConsent').classList.remove('show');
                
                // Remove dark mode if active
                document.body.classList.remove('dark-mode');
                document.getElementById('themeToggle').innerHTML = '<i class="fas fa-moon"></i>';
                
                showToast('Non-essential cookies rejected.');
            });

            document.getElementById('cookieSettings').addEventListener('click', function() {
                document.getElementById('cookieModal').classList.add('show');
            });

            document.getElementById('learnMore').addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('cookieModal').classList.add('show');
            });

            document.getElementById('closeModal').addEventListener('click', function() {
                document.getElementById('cookieModal').classList.remove('show');
            });

            document.getElementById('saveSettings').addEventListener('click', saveCookiePreferences);

            // Theme toggle
            document.getElementById('themeToggle').addEventListener('click', toggleDarkMode);

            // Highlight current page
            const currentPage = window.location.pathname.split('/').pop();
            const navLinks = document.querySelectorAll('.nav-links a');
            
            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href === currentPage || (currentPage === '' && href === 'Index.php')) {
                    link.style.color = '#ffc107';
                    link.style.fontWeight = '700';
                    link.style.background = 'rgba(255, 255, 255, 0.1)';
                }
            });

            // Add animations to cards
            const cards = document.querySelectorAll('.feature-col, .role-col, .offer-col');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.style.opacity = '0';
                card.style.animation = 'fadeIn 0.8s ease-out forwards';
            });
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('cookieModal').classList.remove('show');
                
                if (navlinks.classList.contains('active')) {
                    toggleMenu();
                }
            }
        });

        // Add fadeIn animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>