<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/Logos/AU Logo.png">
    <title>Department Login</title>
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

        .login-box {
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

        .login-box::before {
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

        h2 {
            color: #003b6f;
            font-size: 28px;
            margin-bottom: 35px;
            font-weight: 700;
            position: relative;
            padding-bottom: 15px;
        }

        h2::after {
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

        form {
            display: flex;
            flex-direction: column;
            gap: 25px;
            margin-bottom: 30px;
        }

        .input-box {
            text-align: left;
            position: relative;
        }

        .input-box label {
            display: block;
            margin-bottom: 8px;
            color: #003b6f;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .input-box label i {
            color: #005fa3;
            font-size: 16px;
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

        .password-container {
            position: relative;
        }

        .password-container input {
            padding-right: 60px;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 45px;
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

        .btn-submit {
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
            margin-top: 10px;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 59, 111, 0.3);
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #0056a8, #003b6f);
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0, 59, 111, 0.4);
        }

        .btn-submit:active {
            transform: translateY(-1px);
        }

        .btn-submit::before {
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

        .btn-submit:hover::before {
            left: 100%;
        }

        .footer-link {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .footer-link p {
            color: #5a6c7d;
            font-size: 14px;
        }

        .footer-link a {
            color: #003b6f;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .footer-link a:hover {
            color: #0056a8;
            gap: 12px;
        }

        .footer-link a i {
            font-size: 14px;
            transition: transform 0.3s ease;
        }

        .footer-link a:hover i {
            transform: translateX(-5px);
        }

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

        /* Success message */
        .success-message {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
            animation: successFade 0.5s ease;
            box-shadow: 0 5px 20px rgba(40, 167, 69, 0.2);
            display: none;
        }

        @keyframes successFade {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            body { padding: 15px; }
            .login-box { 
                padding: 30px 25px; 
                margin: 10px;
            }
            .log-img { 
                width: 70px; 
                height: 70px; 
            }
            h2 { 
                font-size: 24px; 
                margin-bottom: 25px; 
            }
            .input-box input { 
                padding: 14px 45px 14px 16px; 
            }
            .btn-submit { 
                padding: 14px; 
                font-size: 16px; 
            }
            .toggle-password {
                right: 12px;
                width: 36px;
                height: 36px;
            }
        }

        @media (max-width: 360px) {
            .login-box { 
                padding: 25px 20px; 
            }
            h2 { 
                font-size: 22px; 
            }
            .input-box input { 
                padding: 12px 40px 12px 14px; 
            }
        }
    </style>
</head>
<body>
    <div class="login-box">
        <img class="log-img" src="images/logos/AU Logo.png" alt="AU Logo">
        <h2>Department Login</h2>

        <!-- Error/Success Messages -->
        <div id="errorMessage" class="error-message"></div>
        <div id="successMessage" class="success-message"></div>

        <form action="department_login_process.php" method="post" id="loginForm">
            <div class="input-box">
                <label>
                    <i class="fas fa-envelope"></i>
                    Email
                </label>
                <input type="email" name="email" id="email" placeholder="department@university.edu" required>
            </div>

            <div class="input-box password-container">
                <label>
                    <i class="fas fa-lock"></i>
                    Password
                </label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
                <button type="button" class="toggle-password" id="togglePassword" aria-label="Show password"></button>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

       

        <div class="security-note">
            <i class="fas fa-shield-alt"></i>
            <span>Secure Department Portal Access</span>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('togglePassword');
            const loginForm = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitBtn');
            const errorMessage = document.getElementById('errorMessage');
            const successMessage = document.getElementById('successMessage');

            // Initialize toggle button
            toggleButton.classList.add('showing');

            // Toggle password visibility
            toggleButton.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                this.classList.toggle('showing');
                this.setAttribute('aria-label', type === 'text' ? 'Hide password' : 'Show password');
                
                // Add animation effect
                this.style.transform = 'translateY(-50%) scale(1.2)';
                setTimeout(() => {
                    this.style.transform = 'translateY(-50%) scale(1)';
                }, 200);
                
                // Keep focus on password field
                passwordInput.focus();
            });            
            
        });
    </script>
</body>
</html>