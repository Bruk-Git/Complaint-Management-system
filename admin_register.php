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
    <link rel="icon" href="images/Logos/AU Logo.png">
<title>Admin Register</title>
<style>
/* Reset and Base Styles */
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

/* Animated Background */
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

/* Box Container */
.box {
    width: 100%;
    max-width: 480px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    padding: 40px 35px;
    border-radius: 20px;
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.3);
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.2);
    animation: boxAppear 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.1);
    z-index: 1;
}

@keyframes boxAppear {
    0% {
        opacity: 0;
        transform: translateY(50px) scale(0.8);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Admin Badge */
.box::before {
    content: '👨‍💼';
    position: absolute;
    top: -25px;
    right: -25px;
    font-size: 100px;
    opacity: 0.1;
    z-index: -1;
    animation: adminFloat 4s ease-in-out infinite;
}

@keyframes adminFloat {
    0%, 100% { transform: translate(0, 0) rotate(0deg); }
    50% { transform: translate(10px, -10px) rotate(5deg); }
}

/* Heading */
.box h3 {
    color: #003b6f;
    font-size: 28px;
    text-align: center;
    margin-bottom: 35px;
    position: relative;
    padding-bottom: 15px;
    font-weight: 700;
    letter-spacing: 1px;
}

.box h3::after {
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

/* Form Styles */
form {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

/* Input Fields */
input[type="text"],
input[type="email"],
input[type="password"] {
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
    position: relative;
}

input[type="text"]:focus,
input[type="email"]:focus,
input[type="password"]:focus {
    outline: none;
    border-color: #003b6f;
    background: white;
    box-shadow: 0 8px 25px rgba(0, 59, 111, 0.15);
    transform: translateY(-2px);
}

input::placeholder {
    color: #8a9aad;
    transition: all 0.3s ease;
}

input:focus::placeholder {
    opacity: 0.5;
    transform: translateX(10px);
}

/* Input Icons using pseudo-elements */
input[type="text"] {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23003b6f' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2'%3E%3C/path%3E%3Ccircle cx='12' cy='7' r='4'%3E%3C/circle%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 20px center;
    background-size: 20px;
    padding-right: 55px;
}

input[type="email"] {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23003b6f' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z'%3E%3C/path%3E%3Cpolyline points='22,6 12,13 2,6'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 20px center;
    background-size: 20px;
    padding-right: 55px;
}

/* Password Container */
.password-container {
    position: relative;
}

.password-container input[type="password"] {
    padding-right: 85px !important;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23003b6f' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='11' width='18' height='11' rx='2' ry='2'%3E%3C/rect%3E%3Cpath d='M7 11V7a5 5 0 0 1 10 0v4'%3E%3C/path%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 55px center;
    background-size: 20px;
}

/* Show/Hide Password Toggle */
.toggle-password {
    position: absolute;
    right: 20px;
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

/* Password Strength Indicator */
.password-strength {
    height: 6px;
    background: #e0e6ed;
    border-radius: 3px;
    overflow: hidden;
    margin-top: 5px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.password-strength.show {
    opacity: 1;
}

.password-strength-bar {
    height: 100%;
    width: 0%;
    border-radius: 3px;
    transition: width 0.5s ease, background 0.5s ease;
}

.password-requirements {
    list-style: none;
    padding: 0;
    margin-top: 10px;
    opacity: 0;
    transition: opacity 0.3s ease;
    max-height: 0;
    overflow: hidden;
}

.password-requirements.show {
    opacity: 1;
    max-height: 200px;
}

.password-requirements li {
    padding: 5px 0;
    font-size: 13px;
    color: #5a6c7d;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
}

.password-requirements li::before {
    content: '✗';
    margin-right: 8px;
    color: #ff4d4d;
    font-size: 14px;
    transition: all 0.3s ease;
}

.password-requirements li.passed::before {
    content: '✓';
    color: #28a745;
}

.password-requirements li.passed {
    color: #28a745;
}

.password-requirements li.failed {
    color: #ff4d4d;
}

/* Password Match Indicator */
.password-match {
    height: 6px;
    background: #e0e6ed;
    border-radius: 3px;
    overflow: hidden;
    margin-top: 5px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.password-match.show {
    opacity: 1;
}

.password-match-bar {
    height: 100%;
    width: 0%;
    border-radius: 3px;
    transition: width 0.5s ease, background 0.5s ease;
}

/* Submit Button */
button[type="submit"] {
    background: linear-gradient(135deg, #003b6f, #0056a8);
    color: white;
    padding: 18px;
    border: none;
    border-radius: 12px;
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

button[type="submit"]:hover {
    background: linear-gradient(135deg, #0056a8, #003b6f);
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(0, 59, 111, 0.4);
}

button[type="submit"]:active {
    transform: translateY(-1px);
}

button[type="submit"]::after {
    content: '⚡';
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 20px;
    opacity: 0;
    transition: all 0.3s ease;
}

button[type="submit"]:hover::after {
    opacity: 1;
    animation: boltFlash 0.5s ease;
}

@keyframes boltFlash {
    0% { opacity: 0; transform: translateY(-50%) scale(0); }
    50% { opacity: 1; transform: translateY(-50%) scale(1.5); }
    100% { opacity: 1; transform: translateY(-50%) scale(1); }
}

/* Error Message */
.error-message {
    color: #ff4d4d;
    font-size: 14px;
    margin-top: 5px;
    display: block;
    animation: errorShake 0.5s ease;
}

@keyframes errorShake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

/* Success Message */
.success-message {
    background: linear-gradient(135deg, #28a745, #1e7e34);
    color: white;
    padding: 12px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    text-align: center;
    font-weight: 500;
    animation: successBounce 0.5s ease;
    box-shadow: 0 5px 20px rgba(40, 167, 69, 0.2);
}

@keyframes successBounce {
    0% { transform: scale(0.8); opacity: 0; }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); opacity: 1; }
}

/* Responsive Design */
@media (max-width: 480px) {
    body {
        padding: 15px;
    }
    
    .box {
        padding: 30px 25px;
        margin: 20px;
    }
    
    .box h3 {
        font-size: 24px;
        margin-bottom: 25px;
    }
    
    form {
        gap: 20px;
    }
    
    input[type="text"],
    input[type="email"],
    input[type="password"] {
        padding: 14px 16px;
        font-size: 15px;
    }
    
    .password-container input[type="password"] {
        padding-right: 70px !important;
    }
    
    .toggle-password {
        width: 35px;
        height: 35px;
        font-size: 18px;
        right: 15px;
    }
    
    button[type="submit"] {
        padding: 16px;
        font-size: 16px;
    }
    
    .box::before {
        font-size: 80px;
        top: -15px;
        right: -15px;
    }
}

/* Loading State */
button[type="submit"].loading {
    position: relative;
    color: transparent;
}

button[type="submit"].loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: translate(-50%, -50%) rotate(360deg); }
}
</style>
</head>

<body>
<div class="box">
<h3>Admin Registration</h3>

<form action="admin_register_process.php" method="post">

<input type="text" name="full_name" placeholder="Full Name" required>

<input type="email" name="email" placeholder="Email" required>

<input type="password" name="password" placeholder="Password" required>

<input type="password" name="confirm_password" placeholder="Confirm Password" required>

<button type="submit">Register</button>

</form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get password inputs
    const passwordInput = document.querySelector('input[name="password"]');
    const confirmPasswordInput = document.querySelector('input[name="confirm_password"]');
    
    // Wrap password inputs in containers for show/hide functionality
    [passwordInput, confirmPasswordInput].forEach(input => {
        if (input) {
            const container = document.createElement('div');
            container.className = 'password-container';
            input.parentNode.insertBefore(container, input);
            container.appendChild(input);
            
            // Add toggle button
            const toggleButton = document.createElement('button');
            toggleButton.type = 'button';
            toggleButton.className = 'toggle-password';
            toggleButton.setAttribute('aria-label', 'Show password');
            container.appendChild(toggleButton);
            
            // Toggle functionality
            toggleButton.addEventListener('click', function() {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.classList.toggle('showing');
                this.setAttribute('aria-label', type === 'text' ? 'Hide password' : 'Show password');
                
                // Animation effect
                this.style.transform = 'translateY(-50%) scale(1.2)';
                setTimeout(() => {
                    this.style.transform = 'translateY(-50%) scale(1)';
                }, 200);
                
                input.focus();
            });
        }
    });
    
    // Create password strength indicator
    const strengthIndicator = document.createElement('div');
    strengthIndicator.className = 'password-strength';
    passwordInput.parentNode.insertBefore(strengthIndicator, passwordInput.nextSibling);
    
    const strengthBar = document.createElement('div');
    strengthBar.className = 'password-strength-bar';
    strengthIndicator.appendChild(strengthBar);
    
    // Create password requirements list
    const requirementsList = document.createElement('ul');
    requirementsList.className = 'password-requirements';
    requirementsList.innerHTML = `
        <li data-rule="length">At least 8 characters</li>
        <li data-rule="uppercase">Contains uppercase letter</li>
        <li data-rule="lowercase">Contains lowercase letter</li>
        <li data-rule="number">Contains number</li>
        <li data-rule="special">Contains special character (!@#$%^&*)</li>
    `;
    passwordInput.parentNode.insertBefore(requirementsList, strengthIndicator.nextSibling);
    
    // Create password match indicator
    const matchIndicator = document.createElement('div');
    matchIndicator.className = 'password-match';
    confirmPasswordInput.parentNode.insertBefore(matchIndicator, confirmPasswordInput.nextSibling);
    
    const matchBar = document.createElement('div');
    matchBar.className = 'password-match-bar';
    matchIndicator.appendChild(matchBar);
    
    // Password strength validation
    function validatePasswordStrength(password) {
        const requirements = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[!@#$%^&*]/.test(password)
        };
        
        // Update requirements list
        Object.entries(requirements).forEach(([rule, passed]) => {
            const requirementItem = requirementsList.querySelector(`[data-rule="${rule}"]`);
            if (requirementItem) {
                requirementItem.classList.toggle('passed', passed);
                requirementItem.classList.toggle('failed', !passed);
            }
        });
        
        // Calculate strength
        let strength = Object.values(requirements).filter(Boolean).length * 20;
        strengthBar.style.width = `${strength}%`;
        
        // Set strength bar color
        if (strength < 40) {
            strengthBar.style.background = '#ff4d4d';
        } else if (strength < 70) {
            strengthBar.style.background = '#ff9800';
        } else if (strength < 100) {
            strengthBar.style.background = '#28a745';
        } else {
            strengthBar.style.background = '#00b894';
        }
        
        // Show/hide indicators
        if (password.length > 0) {
            strengthIndicator.classList.add('show');
            requirementsList.classList.add('show');
        } else {
            strengthIndicator.classList.remove('show');
            requirementsList.classList.remove('show');
        }
        
        return Object.values(requirements).every(Boolean);
    }
    
    // Password match validation
    function validatePasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (confirmPassword.length === 0) {
            matchIndicator.classList.remove('show');
            return true;
        }
        
        if (password === confirmPassword) {
            matchBar.style.width = '100%';
            matchBar.style.background = '#28a745';
            matchIndicator.classList.add('show');
            return true;
        } else {
            matchBar.style.width = '0%';
            matchBar.style.background = '#ff4d4d';
            matchIndicator.classList.add('show');
            return false;
        }
    }
    
    // Real-time validation
    passwordInput.addEventListener('input', function() {
        validatePasswordStrength(this.value);
        validatePasswordMatch();
    });
    
    confirmPasswordInput.addEventListener('input', validatePasswordMatch);
    
    // Form submission validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        const fullName = this.querySelector('input[name="full_name"]').value.trim();
        const email = this.querySelector('input[name="email"]').value.trim();
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        let isValid = true;
        const errors = [];
        
        // Clear previous errors
        document.querySelectorAll('.error-message').forEach(el => el.remove());
        
        // Validate Full Name
        if (fullName.length < 2) {
            showError(this.querySelector('input[name="full_name"]'), 'Full name must be at least 2 characters');
            isValid = false;
        }
        
        // Validate Email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showError(this.querySelector('input[name="email"]'), 'Please enter a valid email address');
            isValid = false;
        }
        
        // Validate Password Strength
        if (!validatePasswordStrength(password)) {
            showError(passwordInput, 'Password does not meet security requirements');
            isValid = false;
        }
        
        // Validate Password Match
        if (!validatePasswordMatch()) {
            showError(confirmPasswordInput, 'Passwords do not match');
            isValid = false;
        }
        
        if (!isValid) {
            event.preventDefault();
            
            // Show alert with password requirements
            if (password.length > 0 && !validatePasswordStrength(password)) {
                alert('Password Requirements:\n\n' +
                      '• At least 8 characters\n' +
                      '• Include uppercase and lowercase letters\n' +
                      '• Include at least one number\n' +
                      '• Include at least one special character (!@#$%^&*)\n\n' +
                      'Please create a stronger password.');
            }
        }
    });
    
    function showError(inputElement, message) {
        const errorDiv = document.createElement('span');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        inputElement.parentNode.appendChild(errorDiv);
        
        // Add error class to input
        inputElement.classList.add('error');
        
        // Remove error class on input
        inputElement.addEventListener('input', function() {
            this.classList.remove('error');
            if (errorDiv.parentNode === this.parentNode) {
                this.parentNode.removeChild(errorDiv);
            }
        }, { once: true });
    }
    
    // Add error class for styling
    const style = document.createElement('style');
    style.textContent = `
        input.error {
            border-color: #ff4d4d !important;
            animation: errorShake 0.5s ease;
        }
    `;
    document.head.appendChild(style);
});
</script>
</body>
</html>
