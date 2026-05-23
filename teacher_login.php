<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/Logos/AU Logo.png">
    <title>Teachers Login - Admas University</title>
    <link rel="icon" href="images/logos/AU Logo.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0a2540 0%, #003b6f 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            animation: gradientShift 15s ease infinite alternate;
        }

        @keyframes gradientShift {
            0% { background: linear-gradient(135deg, #0a2540 0%, #003b6f 100%); }
            50% { background: linear-gradient(135deg, #003b6f 0%, #0056a8 100%); }
            100% { background: linear-gradient(135deg, #0056a8 0%, #0a2540 100%); }
        }

        .container {
            width: 100%;
            max-width: 450px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 40px 35px;
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.6s ease-out;
            border: 1px solid rgba(255, 255, 255, 0.2);
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

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #003b6f, #005fa3, #ffc107);
        }

        .log-img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 3px solid #003b6f;
            padding: 8px;
            background: white;
            margin-bottom: 20px;
            box-shadow: 0 8px 25px rgba(0, 59, 111, 0.3);
            transition: all 0.3s ease;
        }

        .log-img:hover {
            transform: scale(1.05) rotate(5deg);
            box-shadow: 0 12px 35px rgba(0, 59, 111, 0.4);
        }

        h1 {
            color: #003b6f;
            font-size: 28px;
            margin-bottom: 35px;
            font-weight: 700;
            position: relative;
            padding-bottom: 15px;
        }

        h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #003b6f, #ffc107);
            border-radius: 2px;
            animation: lineExpand 1s ease-out 0.3s both;
        }

        @keyframes lineExpand {
            0% { width: 0; }
            100% { width: 80px; }
        }

        #Login-form {
            display: flex;
            flex-direction: column;
            gap: 25px;
            margin-bottom: 20px;
        }

        .input-box {
            position: relative;
        }

        .input-box input {
            width: 100%;
            padding: 16px 50px 16px 20px;
            background: white;
            border: 2px solid #e0e6ed;
            border-radius: 12px;
            font-size: 16px;
            color: #333;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 59, 111, 0.05);
        }

        .input-box input:focus {
            outline: none;
            border-color: #003b6f;
            box-shadow: 0 8px 25px rgba(0, 59, 111, 0.15);
            transform: translateY(-2px);
        }

        .input-box input::placeholder {
            color: #8a9aad;
        }

        .input-box i {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #003b6f;
            font-size: 22px;
            transition: all 0.3s ease;
            pointer-events: none;
            opacity: 0.8;
        }

        .input-box input:focus + i {
            color: #0056a8;
            transform: translateY(-50%) scale(1.1);
            opacity: 1;
        }

        .password-container {
            position: relative;
        }

        .password-container input {
            padding-right: 85px;
        }

        .toggle-password {
            position: absolute;
            right: 50px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 8px;
            border-radius: 6px;
            color: #8a9aad;
            font-size: 20px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            z-index: 10;
        }

        .toggle-password:hover {
            background: rgba(0, 59, 111, 0.1);
            color: #003b6f;
            transform: translateY(-50%) scale(1.1);
        }

        .toggle-password:active {
            transform: translateY(-50%) scale(0.95);
        }

        .toggle-password::before {
            content: '👁️';
            transition: all 0.3s ease;
        }

        .toggle-password.showing::before {
            content: '🙈';
        }

        .Remember-Forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            margin-top: 10px;
        }

        .Remember-Forgot label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #5a6c7d;
            cursor: pointer;
        }

        .Remember-Forgot input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #003b6f;
            cursor: pointer;
        }

        .Remember-Forgot a {
            color: #003b6f;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .Remember-Forgot a:hover {
            color: #0056a8;
            text-decoration: underline;
        }

        .btn {
            margin-top: 15px;
        }

        .btn button {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #003b6f, #0056a8);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 59, 111, 0.3);
        }

        .btn button:hover {
            background: linear-gradient(135deg, #0056a8, #003b6f);
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0, 59, 111, 0.4);
        }

        .btn button:active {
            transform: translateY(-1px);
        }

        .btn button::before {
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

        .btn button:hover::before {
            left: 100%;
        }

        /* Lock icon animation */
        .bx-lock-alt, .bx-lock-open-alt {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #003b6f;
            font-size: 22px;
            transition: all 0.3s ease;
            opacity: 0.8;
        }

        .password-container:focus-within .bx-lock-alt,
        .password-container:focus-within .bx-lock-open-alt {
            color: #0056a8;
            transform: translateY(-50%) scale(1.1);
            opacity: 1;
        }

        /* Custom checkbox styling */
        .Remember-Forgot label input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            background: white;
            border: 2px solid #d1d9e6;
            border-radius: 4px;
            position: relative;
            cursor: pointer;
            width: 18px;
            height: 18px;
        }

        .Remember-Forgot label input[type="checkbox"]:checked {
            background: #003b6f;
            border-color: #003b6f;
        }

        .Remember-Forgot label input[type="checkbox"]:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
        }

        /* Footer links */
        .footer-links {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .footer-links p {
            color: #5a6c7d;
            font-size: 14px;
        }

        .footer-links a {
            color: #003b6f;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .footer-links a:hover {
            color: #0056a8;
            gap: 12px;
        }

        .footer-links a i {
            font-size: 14px;
            transition: transform 0.3s ease;
        }

        .footer-links a:hover i {
            transform: translateX(-5px);
        }

        /* Security note */
        .security-note {
            margin-top: 20px;
            font-size: 12px;
            color: #8a9aad;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .security-note i {
            color: #28a745;
        }

        /* Error message styling */
        .error-message {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
            animation: errorShake 0.5s ease;
            box-shadow: 0 5px 20px rgba(220, 53, 69, 0.2);
            display: none;
        }

        @keyframes errorShake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            body { padding: 15px; }
            .container { 
                padding: 30px 25px; 
                margin: 10px;
            }
            .log-img { 
                width: 70px; 
                height: 70px; 
            }
            h1 { 
                font-size: 24px; 
                margin-bottom: 25px; 
            }
            .input-box input { 
                padding: 14px 45px 14px 16px; 
            }
            .password-container input {
                padding-right: 75px;
            }
            .toggle-password { 
                right: 45px; 
            }
            .Remember-Forgot { 
                flex-direction: column; 
                gap: 10px; 
                align-items: flex-start; 
            }
            .btn button { 
                padding: 14px; 
                font-size: 16px; 
            }
        }

        @media (max-width: 360px) {
            .container { 
                padding: 25px 20px; 
            }
            h1 { 
                font-size: 22px; 
            }
            .input-box input { 
                padding: 12px 40px 12px 14px; 
            }
            .password-container input {
                padding-right: 70px;
            }
            .toggle-password {
                right: 40px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <img class="log-img" src="images/logos/AU Logo.png" alt="AU Logo">
        <h1>Teachers Login</h1>

        <form action="teacher_login_process.php" method="post" id="Login-form">
            <div class="input-box">
                <input id="email" type="email" name="email" placeholder="Teacher Email Address" required>
                <i class='bx bx-envelope'></i>
            </div>

            <div class="input-box password-container">
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <i class='bx bx-lock-alt'></i>
                <button type="button" class="toggle-password" aria-label="Show password"></button>
            </div>
            
            <div class="Remember-Forgot">
                <label><input type="checkbox" name="remember"> Remember Me</label>
                <a href="forgot_password.php">Forgot password?</a>
            </div>

            <div class="btn">
                <button type="submit">Login</button>
            </div>
        </form>

        <div class="security-note">
            <i class="fas fa-shield-alt"></i>
            <span>Secure Teacher Portal Access</span>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.querySelector('.toggle-password');
            const lockIcon = document.querySelector('.bx-lock-alt, .bx-lock-open-alt');
            
            // Initialize toggle button
            toggleButton.classList.add('showing');
            
            // Toggle password visibility
            toggleButton.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Toggle showing/hiding class
                this.classList.toggle('showing');
                
                // Update aria label
                this.setAttribute('aria-label', type === 'text' ? 'Hide password' : 'Show password');
                
                // Change lock icon
                if (lockIcon) {
                    if (type === 'text') {
                        lockIcon.classList.remove('bx-lock-alt');
                        lockIcon.classList.add('bx-lock-open-alt');
                    } else {
                        lockIcon.classList.remove('bx-lock-open-alt');
                        lockIcon.classList.add('bx-lock-alt');
                    }
                }
                
                // Add animation effect
                this.style.transform = 'translateY(-50%) scale(1.2)';
                setTimeout(() => {
                    this.style.transform = 'translateY(-50%) scale(1)';
                }, 200);
                
                // Keep focus on password field
                passwordInput.focus();
            });
            
            // Add focus effects
            passwordInput.addEventListener('focus', function() {
                if (lockIcon) {
                    lockIcon.style.color = '#0056a8';
                    lockIcon.style.transform = 'translateY(-50%) scale(1.1)';
                }
                toggleButton.style.color = '#003b6f';
            });
            
            passwordInput.addEventListener('blur', function() {
                if (lockIcon) {
                    lockIcon.style.color = '#003b6f';
                    lockIcon.style.transform = 'translateY(-50%) scale(1)';
                }
                toggleButton.style.color = '#8a9aad';
            });
            
           
            function showError(message) {
                // Remove existing error
                const existingError = document.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }
                
                // Create error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.textContent = message;
                errorDiv.style.display = 'block';
                
                // Insert error message
                const container = document.querySelector('.container');
                const form = document.getElementById('Login-form');
                container.insertBefore(errorDiv, form);
                
                // Auto-remove after 5 seconds
                setTimeout(() => {
                    if (errorDiv.parentNode) {
                        errorDiv.style.opacity = '0';
                        errorDiv.style.transform = 'translateY(-10px)';
                        setTimeout(() => errorDiv.remove(), 300);
                    }
                }, 5000);
            }
            
            // Auto-focus email field
            document.getElementById('email').focus();
        });
        // Add this script to your teacher_login.php or create a new logout_handler.php

// Prevent back button after logout
window.history.pushState(null, null, window.location.href);
window.addEventListener('popstate', function () {
    window.history.pushState(null, null, window.location.href);
});

// Clear form cache
if (window.history && window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
    </script>
</body>
</html>