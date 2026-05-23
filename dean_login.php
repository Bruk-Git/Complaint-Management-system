<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="icon" href="images/Logos/AU Logo.png">
    <title>Dean Office Login | CMS</title>
    <link rel="icon" href="images/logos/AU Logo.png">

    <style>

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', 'Segoe UI', sans-serif;
    }

    body {
        background: linear-gradient(135deg, #5aaaecff 0%, #5961a1ff 100%);
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
        overflow-x: hidden;
    }

    /* Animated background elements */
    .bg-shape-1, .bg-shape-2 {
        position: fixed;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
        z-index: 0;
    }

    .bg-shape-1 {
        width: 300px;
        height: 300px;
        top: -150px;
        left: -150px;
        animation: float 20s infinite linear;
    }

    .bg-shape-2 {
        width: 200px;
        height: 200px;
        bottom: -100px;
        right: -100px;
        animation: float 15s infinite linear reverse;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        25% { transform: translate(50px, 50px) rotate(90deg); }
        50% { transform: translate(100px, 0) rotate(180deg); }
        75% { transform: translate(50px, -50px) rotate(270deg); }
    }

    /* Login Box */
    .login-box {
        background: rgba(170, 185, 200, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        padding: 50px;
        width: 100%;
        max-width: 480px;
        box-shadow: 
            0 25px 50px -12px rgba(0, 0, 0, 0.4),
            0 0 0 1px rgba(255, 255, 255, 0.1);
        position: relative;
        overflow: hidden;
        z-index: 1;
        animation: slideUp 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .login-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, 
            #ff9800 0%, 
            #ff5722 50%, 
            #e91e63 100%);
        border-radius: 24px 24px 0 0;
    }

    .login-box::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: conic-gradient(
            transparent 0deg,
            rgba(255, 152, 0, 0.1) 90deg,
            transparent 180deg,
            rgba(233, 30, 99, 0.1) 270deg,
            transparent 360deg
        );
        animation: rotate 20s linear infinite;
        z-index: -1;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(40px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Header */
    h2 {
        text-align: center;
        color: #1a237e;
        margin-bottom: 40px;
        font-size: 32px;
        font-weight: 700;
        position: relative;
        padding-bottom: 20px;
        letter-spacing: -0.5px;
    }

    h2::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #ff9800, #e91e63);
        border-radius: 2px;
    }

    /* Input Box */
    .input-box {
        margin-bottom: 30px;
        position: relative;
    }

    .input-box label {
        display: block;
        margin-bottom: 10px;
        color: #1a237e;
        font-weight: 600;
        font-size: 15px;
        letter-spacing: 0.3px;
        transition: color 0.3s;
    }

    .input-box input {
        width: 100%;
        padding: 18px 20px;
        border: 2px solid #e0e0e0;
        border-radius: 14px;
        font-size: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: #f8f9fa;
        color: #333;
        outline: none;
        font-weight: 500;
    }

    .input-box input:focus {
        border-color: #1a237e;
        background: white;
        box-shadow: 
            0 0 0 4px rgba(26, 35, 126, 0.1),
            0 10px 20px rgba(26, 35, 126, 0.1);
        transform: translateY(-2px);
    }

    .input-box input::placeholder {
        color: #9e9e9e;
        opacity: 0.7;
    }

    /* Password Input Container */
    .input-box:has(#password) {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 15px;
        top: 45px;
        background: transparent;
        border: none;
        cursor: pointer;
        font-size: 20px;
        padding: 10px;
        border-radius: 10px;
        transition: all 0.3s;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
    }

    .toggle-password:hover {
        background: rgba(26, 35, 126, 0.1);
        transform: scale(1.1);
    }

    .toggle-password:active {
        transform: scale(0.95);
    }

    /* Login Button */
    .btn-login {
        width: 100%;
        padding: 20px;
        background: linear-gradient(135deg, #1a237e 0%, #283593 100%);
        color: white;
        border: none;
        border-radius: 14px;
        font-size: 17px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-top: 20px;
    }

    .btn-login::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            90deg,
            transparent,
            rgba(255, 255, 255, 0.2),
            transparent
        );
        transition: left 0.7s;
    }

    .btn-login:hover {
        background: linear-gradient(135deg, #283593 0%, #303f9f 100%);
        transform: translateY(-3px);
        box-shadow: 
            0 15px 30px rgba(26, 35, 126, 0.3),
            0 0 0 1px rgba(255, 255, 255, 0.1);
    }

    .btn-login:hover::before {
        left: 100%;
    }

    .btn-login:active {
        transform: translateY(-1px);
        box-shadow: 
            0 10px 20px rgba(26, 35, 126, 0.2),
            0 0 0 1px rgba(255, 255, 255, 0.1);
    }

    /* Back Link */
    .back-link {
        text-align: center;
        margin-top: 30px;
        padding-top: 25px;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

    .back-link p {
        color: #666;
        font-size: 15px;
        font-weight: 500;
    }

    .back-link a {
        color: #1a237e;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 10px;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .back-link a::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            90deg,
            transparent,
            rgba(26, 35, 126, 0.1),
            transparent
        );
        transition: left 0.5s;
    }

    .back-link a:hover {
        color: #283593;
        background: rgba(26, 35, 126, 0.05);
        transform: translateX(-5px);
    }

    .back-link a:hover::before {
        left: 100%;
    }

    .back-link a i {
        font-size: 18px;
    }

    /* Dean Office Badge */
    .dean-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        margin-bottom: 30px;
        padding: 20px;
        background: linear-gradient(135deg, 
            rgba(255, 152, 0, 0.1) 0%, 
            rgba(233, 30, 99, 0.1) 100%);
        border-radius: 16px;
        border: 1px solid rgba(255, 152, 0, 0.2);
    }

    .dean-badge i {
        font-size: 32px;
        color: #ff9800;
        background: white;
        padding: 15px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(255, 152, 0, 0.2);
    }

    .dean-badge span {
        font-size: 18px;
        font-weight: 700;
        color: #1a237e;
        letter-spacing: 0.5px;
    }

    /* Password Strength Indicator */
    .password-strength {
        height: 4px;
        border-radius: 2px;
        background: #e0e0e0;
        margin-top: 8px;
        overflow: hidden;
        position: relative;
    }

    .strength-bar {
        height: 100%;
        width: 0%;
        border-radius: 2px;
        transition: width 0.3s, background-color 0.3s;
        position: absolute;
        left: 0;
        top: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .login-box {
            padding: 40px 30px;
            margin: 20px;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 30px;
        }

        .input-box input {
            padding: 16px 18px;
        }

        .btn-login {
            padding: 18px;
        }

        .dean-badge {
            padding: 15px;
        }
    }

    @media (max-width: 480px) {
        body {
            padding: 15px;
        }

        .login-box {
            padding: 30px 25px;
        }

        h2 {
            font-size: 24px;
        }

        .input-box input {
            padding: 15px;
            font-size: 15px;
        }

        .toggle-password {
            right: 10px;
            top: 40px;
            width: 40px;
            height: 40px;
            font-size: 18px;
        }
    }

    /* Loading Animation */
    .btn-login.loading {
        pointer-events: none;
        opacity: 0.9;
    }

    .btn-login.loading span {
        opacity: 0;
    }

    .btn-login.loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 24px;
        height: 24px;
        border: 3px solid transparent;
        border-top-color: white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: translate(-50%, -50%) rotate(0deg); }
        100% { transform: translate(-50%, -50%) rotate(360deg); }
    }

    /* Error/Success Messages */
    .message {
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 25px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: fadeIn 0.3s;
    }

    .message.error {
        background: linear-gradient(135deg, 
            rgba(244, 67, 54, 0.1) 0%, 
            rgba(183, 28, 28, 0.1) 100%);
        color: #d32f2f;
        border-left: 4px solid #d32f2f;
    }

    .message.success {
        background: linear-gradient(135deg, 
            rgba(76, 175, 80, 0.1) 0%, 
            rgba(27, 94, 32, 0.1) 100%);
        color: #388e3c;
        border-left: 4px solid #388e3c;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
</head>

<body>

<div class="login-box">
    <!-- <img class="log-img" src="images/logos/AU Logo.png"> -->
    <h2>Dean Office Login</h2>

    <form action="dean_login_process.php" method="POST">

        <div class="input-box">
            <label>Email</label>
            <input type="email" name="email" placeholder="dean@gmail.com" required>
        </div>

        <div class="input-box">
            <label>Password</label>
    <input type="password" name="password" placeholder="Enter password" required id="password">
    <button type="button" class="toggle-password" onclick="togglePassword()">
        👁️
        </div>

        <button type="submit" class="btn-login">Login</button>

    </form>

   
        <script> function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleBtn = document.querySelector('.toggle-password');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleBtn.textContent = '🙈';
    } else {
        passwordInput.type = 'password';
        toggleBtn.textContent = '👁️';
    }
}
</script>
</body>
</html>
