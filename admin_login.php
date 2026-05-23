<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/Logos/AU Logo.png">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* Admin Login CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0a2540 0%, #003b6f 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
            animation: gradientShift 10s ease-in-out infinite alternate;
        }

        @keyframes gradientShift {
            0% { background: linear-gradient(135deg, #0a2540 0%, #003b6f 100%); }
            100% { background: linear-gradient(135deg, #003b6f 0%, #0056a8 100%); }
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(0, 120, 212, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(40, 167, 69, 0.08) 0%, transparent 50%);
            z-index: -1;
            animation: floatBackground 20s ease-in-out infinite;
        }

        @keyframes floatBackground {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -20px) scale(1.05); }
            66% { transform: translate(-20px, 30px) scale(0.95); }
        }

        .container {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 40px 35px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: containerAppear 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.1);
            z-index: 1;
        }

        @keyframes containerAppear {
            0% { opacity: 0; transform: translateY(50px) scale(0.8); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        .container::before {
            content: '🔐';
            position: absolute;
            top: -25px;
            right: -25px;
            font-size: 100px;
            opacity: 0.1;
            z-index: -1;
            animation: shieldFloat 4s ease-in-out infinite;
        }

        @keyframes shieldFloat {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(10px, -10px) rotate(5deg); }
        }

        .container h1 {
            color: #003b6f;
            font-size: 28px;
            text-align: center;
            margin-bottom: 35px;
            position: relative;
            padding-bottom: 15px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .container h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #003b6f, #0056a8);
            border-radius: 2px;
            animation: lineExpand 1s ease-out 0.5s both;
        }

        @keyframes lineExpand {
            0% { width: 0; }
            100% { width: 80px; }
        }

        #Login-form {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .input-box {
            position: relative;
            margin-bottom: 5px;
        }

        .input-box input {
            width: 100%;
            padding: 16px 20px;
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid #e0e6ed;
            border-radius: 12px;
            font-size: 16px;
            color: #2c3e50;
            transition: all 0.3s ease;
            font-family: inherit;
            box-shadow: 0 5px 15px rgba(0, 59, 111, 0.05);
        }

        .input-box input:focus {
            outline: none;
            border-color: #003b6f;
            background: white;
            box-shadow: 0 8px 25px rgba(0, 59, 111, 0.15);
            transform: translateY(-2px);
        }

        .input-box input::placeholder {
            color: #8a9aad;
            transition: all 0.3s ease;
        }

        .input-box input:focus::placeholder {
            opacity: 0.5;
            transform: translateX(10px);
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
        }

        .input-box input:focus + i {
            color: #0056a8;
            transform: translateY(-50%) scale(1.1);
        }

        .password-container {
            position: relative;
        }

        .password-container input {
            padding-right: 75px !important;
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
            animation: eyeToggle 0.3s ease;
        }

        @keyframes eyeToggle {
            0% { transform: scale(0) rotate(-90deg); opacity: 0; }
            100% { transform: scale(1) rotate(0deg); opacity: 1; }
        }

        .Remember-Forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0;
            animation: slideUp 0.5s ease-out 0.4s both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .Remember-Forgot label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #5a6c7d;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }

        .Remember-Forgot label input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #003b6f;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .Remember-Forgot label input[type="checkbox"]:hover {
            transform: scale(1.1);
        }

        .Remember-Forgot a {
            color: #003b6f;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            padding-bottom: 2px;
        }

        .Remember-Forgot a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: #003b6f;
            transition: width 0.3s ease;
        }

        .Remember-Forgot a:hover {
            color: #0056a8;
        }

        .Remember-Forgot a:hover::after {
            width: 100%;
        }

        .btn {
            margin-top: 10px;
            animation: slideUp 0.5s ease-out 0.5s both;
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
            margin-top: 15px;
            letter-spacing: 1px;
            text-transform: uppercase;
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

        .btn button::after {
            content: '⚡';
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .btn button:hover::after {
            opacity: 1;
            animation: boltFlash 0.5s ease;
        }

        @keyframes boltFlash {
            0% { opacity: 0; transform: translateY(-50%) scale(0); }
            50% { opacity: 1; transform: translateY(-50%) scale(1.5); }
            100% { opacity: 1; transform: translateY(-50%) scale(1); }
        }

        .security-note {
            text-align: center;
            margin-top: 25px;
            color: #5a6c7d;
            font-size: 14px;
            position: relative;
            padding-top: 15px;
        }

        .security-note::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 1px;
            background: linear-gradient(90deg, transparent, #e0e6ed, transparent);
        }

        @media (max-width: 480px) {
            body { padding: 15px; }
            .container { padding: 30px 25px; margin: 20px; }
            .container h1 { font-size: 24px; margin-bottom: 25px; }
            .input-box input { padding: 14px 16px; font-size: 15px; }
            .toggle-password { right: 45px; }
            .Remember-Forgot { flex-direction: column; gap: 15px; align-items: flex-start; }
            .btn button { padding: 14px; font-size: 16px; }
            .container::before { font-size: 80px; top: -15px; right: -15px; }
        }

        .error-message {
            background: linear-gradient(135deg, #ff4d4d, #ff3333);
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
            animation: errorShake 0.5s ease;
            box-shadow: 0 5px 20px rgba(255, 77, 77, 0.2);
        }

        @keyframes errorShake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Login</h1>
        <link rel="icon" href="images/logos/AU Logo.png">
        <form method="post" action="admin_login_process.php" id="Login-form">
            
            <!-- Email Input -->
            <div class="input-box">
                <input id="email" type="email" name="email" placeholder="Email Address" required>
                <i class='bx bx-envelope'></i>
            </div>
            
            <!-- Password Input with Show/Hide -->
            <div class="input-box password-container">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <i class='bx bx-lock-alt'></i>
                <button type="button" class="toggle-password" aria-label="Show password"></button>
            </div>
            
            <!-- Remember Me & Forgot Password -->
            <div class="Remember-Forgot">
                <label>
                    <input type="checkbox" name="remember">
                    Remember Me
                </label>
                <a href="forgot_password.php">Forgot Password?</a>
            </div>
            
            <!-- Login Button -->
            <div class="btn">
                <button type="submit">Login</button>
            </div>
            <link rel="icon" href="images/logos/AU Logo.png">
         

            <!-- Security Note -->
            <div class="security-note">
                <i class="fas fa-shield-alt"></i> Secure Admin Access Only
            </div>
            
        </form>
    </div>

    <!-- Show/Hide Password JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.querySelector('.toggle-password');
            
            // Initialize toggle button
            toggleButton.classList.add('showing');
            
            // Toggle password visibility
            toggleButton.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Toggle showing class
                this.classList.toggle('showing');
                
                // Update aria label
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