<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dean Registration | CMS</title>
    <link rel="icon" href="images/logos/AU Logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f0f4f8 0%, #e3ebf5 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .register-box {
            width: 100%;
            max-width: 450px;
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 59, 111, 0.12);
            border: 1px solid rgba(0, 91, 163, 0.1);
        }
        
        .log-img {
            display: block;
            margin: 0 auto 20px;
            width: 70px;
            height: 70px;
            object-fit: contain;
        }
        
        h2 {
            text-align: center;
            color: #003b6f;
            margin-bottom: 25px;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        .input-box {
            margin-bottom: 22px;
            position: relative;
        }
        
        .input-box label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #003b6f;
            font-size: 14px;
            letter-spacing: 0.3px;
        }
        
        .input-box input {
            width: 100%;
            height: 48px;
            padding: 0 45px 0 15px;
            border: 2px solid #e1e5eb;
            border-radius: 8px;
            font-size: 15px;
            color: #333;
            background: #f8fafc;
            transition: all 0.3s ease;
            font-family: inherit;
        }
        
        .input-box input:focus {
            outline: none;
            border-color: #003b6f;
            background: white;
            box-shadow: 0 0 0 3px rgba(0, 59, 111, 0.1);
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 40px;
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            font-size: 18px;
            padding: 5px;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: all 0.2s ease;
        }
        
        .password-toggle:hover {
            color: #003b6f;
            background: rgba(0, 59, 111, 0.05);
        }
        
        .password-strength {
            margin-top: 12px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            font-size: 13px;
        }
        
        .strength-meter {
            height: 6px;
            background: #e9ecef;
            border-radius: 3px;
            margin: 8px 0;
            overflow: hidden;
        }
        
        .strength-fill {
            height: 100%;
            width: 0%;
            background: #dc3545;
            border-radius: 3px;
            transition: all 0.3s ease;
        }
        
        .strength-fill.weak { 
            background: #dc3545; 
            width: 25%; 
        }
        
        .strength-fill.fair { 
            background: #fd7e14; 
            width: 50%; 
        }
        
        .strength-fill.good { 
            background: #ffc107; 
            width: 75%; 
        }
        
        .strength-fill.strong { 
            background: #28a745; 
            width: 100%; 
        }
        
        .strength-text {
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        
        .requirements {
            margin-top: 12px;
            padding-left: 20px;
            list-style: none;
        }
        
        .requirement {
            font-size: 12px;
            color: #666;
            margin-bottom: 6px;
            position: relative;
            transition: color 0.3s ease;
        }
        
        .requirement:before {
            content: '✗';
            position: absolute;
            left: -18px;
            color: #dc3545;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        
        .requirement.met:before {
            content: '✓';
            color: #28a745;
        }
        
        .requirement.met {
            color: #495057;
        }
        
        .btn-register {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
            transition: all 0.3s ease;
            font-family: inherit;
            letter-spacing: 0.5px;
        }
        
        .btn-register:hover {
            background: linear-gradient(135deg, #005fa3 0%, #0072b8 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 59, 111, 0.2);
        }
        
        .btn-register:active {
            transform: translateY(0);
        }
        
        .password-match {
            font-size: 12px;
            margin-top: 6px;
            padding: 5px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: 500;
        }
        
        .password-match.match {
            color: #28a745;
            background: rgba(40, 167, 69, 0.1);
        }
        
        .password-match.mismatch {
            color: #dc3545;
            background: rgba(220, 53, 69, 0.1);
        }
        
        .footer-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        
        .footer-link a {
            color: #005fa3;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: color 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .footer-link a:hover {
            color: #003b6f;
            text-decoration: underline;
        }
        
        .footer-link a i {
            font-size: 13px;
        }
        
        /* Responsive design */
        @media (max-width: 500px) {
            .register-box {
                padding: 25px;
            }
            
            h2 {
                font-size: 22px;
            }
            
            .log-img {
                width: 60px;
                height: 60px;
            }
            
            .input-box input {
                height: 45px;
                font-size: 14px;
            }
            
            .btn-register {
                height: 45px;
                font-size: 15px;
            }
            
            .password-strength {
                padding: 12px;
            }
        }
    </style>
</head>

<body>

<div class="register-box">
    <h2>Dean Office Registration</h2>
    <img class="log-img" src="images/logos/AU Logo.png" alt="Admas University Logo">
    
    <form action="dean_register_process.php" method="POST">
        <div class="input-box">
            <label for="dean_name">Dean Name</label>
            <input type="text" id="dean_name" name="dean_name" placeholder="Enter dean office name" required>
        </div>

        <div class="input-box">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="dean@admas.edu.et" required>
        </div>

        <div class="input-box">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Create a strong password" required>
            <button type="button" class="password-toggle" id="togglePassword" aria-label="Toggle password visibility">
                <i class="fas fa-eye"></i>
            </button>
            
            <div class="password-strength" id="passwordStrength">
                <div class="strength-text" id="strengthLabel">Password Strength: Weak</div>
                <div class="strength-meter">
                    <div class="strength-fill weak" id="strengthFill"></div>
                </div>
                <ul class="requirements" id="passwordRequirements">
                    <li class="requirement" id="reqLength">At least 8 characters</li>
                    <li class="requirement" id="reqLowercase">Contains lowercase letter</li>
                    <li class="requirement" id="reqUppercase">Contains uppercase letter</li>
                    <li class="requirement" id="reqNumber">Contains number</li>
                    <li class="requirement" id="reqSpecial">Contains special character</li>
                </ul>
            </div>
        </div>

        <div class="input-box">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required>
            <button type="button" class="password-toggle" id="toggleConfirmPassword" aria-label="Toggle confirm password visibility">
                <i class="fas fa-eye"></i>
            </button>
            <div id="passwordMatch" class="password-match"></div>
        </div>

        <button type="submit" class="btn-register">
            <i class="fas fa-user-plus"></i> Register Dean Account
        </button>
    </form>

    <div class="footer-link">
        <p>
            <a href="dean_login.php">
                <i class="fas fa-sign-in-alt"></i> Already have an account? Login
            </a>
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('confirm_password');
    const togglePasswordBtn = document.getElementById('togglePassword');
    const toggleConfirmBtn = document.getElementById('toggleConfirmPassword');
    const passwordStrength = document.getElementById('passwordStrength');
    const strengthFill = document.getElementById('strengthFill');
    const strengthLabel = document.getElementById('strengthLabel');
    const passwordMatchDiv = document.getElementById('passwordMatch');

    // Password requirements elements
    const requirements = {
        length: document.getElementById('reqLength'),
        lowercase: document.getElementById('reqLowercase'),
        uppercase: document.getElementById('reqUppercase'),
        number: document.getElementById('reqNumber'),
        special: document.getElementById('reqSpecial')
    };

    // Show/Hide Password Toggle
    togglePasswordBtn.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        this.setAttribute('aria-label', type === 'password' ? 'Show password' : 'Hide password');
    });

    toggleConfirmBtn.addEventListener('click', function() {
        const type = confirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmInput.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        this.setAttribute('aria-label', type === 'password' ? 'Show confirm password' : 'Hide confirm password');
    });

    // Check password strength
    function checkPasswordStrength(password) {
        let score = 0;
        
        // Check requirements
        const checks = {
            length: password.length >= 8,
            lowercase: /[a-z]/.test(password),
            uppercase: /[A-Z]/.test(password),
            number: /\d/.test(password),
            special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
        };
        
        // Update requirement indicators
        Object.keys(checks).forEach(key => {
            if (checks[key]) {
                requirements[key].classList.add('met');
                score++;
            } else {
                requirements[key].classList.remove('met');
            }
        });
        
        // Update strength meter
        let strengthClass = 'weak';
        let strengthText = 'Weak';
        
        if (score === 5) {
            strengthClass = 'strong';
            strengthText = 'Strong';
        } else if (score >= 4) {
            strengthClass = 'good';
            strengthText = 'Good';
        } else if (score >= 3) {
            strengthClass = 'fair';
            strengthText = 'Fair';
        }
        
        strengthFill.className = 'strength-fill ' + strengthClass;
        strengthLabel.textContent = 'Password Strength: ' + strengthText;
    }

    // Check if passwords match
    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirm = confirmInput.value;
        
        if (confirm.length === 0) {
            passwordMatchDiv.textContent = '';
            passwordMatchDiv.className = 'password-match';
            return;
        }
        
        if (password === confirm && password.length > 0) {
            passwordMatchDiv.innerHTML = '<i class="fas fa-check-circle"></i> Passwords match';
            passwordMatchDiv.className = 'password-match match';
        } else {
            passwordMatchDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Passwords do not match';
            passwordMatchDiv.className = 'password-match mismatch';
        }
    }

    // Event listeners
    passwordInput.addEventListener('input', function() {
        checkPasswordStrength(this.value);
        checkPasswordMatch();
    });

    confirmInput.addEventListener('input', checkPasswordMatch);
    
    // Initial setup
    checkPasswordStrength('');
    checkPasswordMatch();
});
</script>

</body>
</html>