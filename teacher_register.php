<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Teacher Registration | CMS</title>
<link rel="icon" href="images/Logos/AU Logo.png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.container {
    max-width: 850px;
    width: 100%;
    margin: 0 auto;
    background: white;
    padding: 35px;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 59, 111, 0.1);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.header {
    text-align: center;
    margin-bottom: 30px;
}

.header h2 {
    color: #003b6f;
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 8px;
    position: relative;
    padding-bottom: 12px;
}

.header h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: #003b6f;
    border-radius: 2px;
}

.header p {
    color: #666;
    font-size: 14px;
    margin-top: 5px;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    position: relative;
}

.form-group label {
    display: block;
    color: #003b6f;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 8px;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: #f8fafc;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #003b6f;
    box-shadow: 0 0 0 3px rgba(0, 59, 111, 0.1);
    background: white;
}

.form-group input.error-border,
.form-group select.error-border {
    border-color: #dc3545;
    animation: shake 0.5s ease-in-out;
}

.form-group input.success-border,
.form-group select.success-border {
    border-color: #28a745;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-3px); }
    75% { transform: translateX(3px); }
}

.error {
    color: #dc3545;
    font-size: 12px;
    margin-top: 5px;
    display: none;
    align-items: center;
    gap: 5px;
}

.error.show {
    display: flex;
}

.error i {
    font-size: 12px;
}

.success {
    color: #28a745;
    font-size: 12px;
    margin-top: 5px;
    display: none;
    align-items: center;
    gap: 5px;
}

.success.show {
    display: flex;
}

.success i {
    font-size: 12px;
}

.input-icon {
    position: relative;
}

.input-icon i {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
    cursor: pointer;
    font-size: 15px;
    padding: 5px;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.input-icon i:hover {
    color: #003b6f;
    background: rgba(0, 59, 111, 0.1);
}

.strength {
    margin-top: 8px;
    font-size: 13px;
    color: #666;
}

.strength-meter {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 5px;
}

.strength-bar {
    flex: 1;
    height: 5px;
    background: #e0e0e0;
    border-radius: 3px;
    overflow: hidden;
}

.strength-fill {
    height: 100%;
    width: 25%;
    border-radius: 3px;
    transition: width 0.3s ease;
}

.strength.weak .strength-fill {
    background: #dc3545;
    width: 25%;
}

.strength.fair .strength-fill {
    background: #fd7e14;
    width: 50%;
}

.strength.good .strength-fill {
    background: #ffc107;
    width: 75%;
}

.strength.strong .strength-fill {
    background: #28a745;
    width: 100%;
}

.strength.very-strong .strength-fill {
    background: #28a745;
    width: 100%;
}

.file-box {
    border: 2px dashed #e0e0e0;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    border-radius: 8px;
    transition: all 0.3s ease;
    background: #f8fafc;
}

.file-box:hover {
    border-color: #003b6f;
    background: #e9f7fe;
}

.file-box i {
    font-size: 24px;
    color: #003b6f;
    margin-bottom: 10px;
    display: block;
}

.file-box div {
    color: #666;
    font-size: 14px;
}

.file-name {
    font-size: 12px;
    color: #666;
    margin-top: 5px;
    text-align: center;
    padding: 3px 0;
}

.submit-btn {
    margin-top: 25px;
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.submit-btn:hover {
    background: linear-gradient(135deg, #005fa3 0%, #007acc 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 95, 163, 0.2);
}

.submit-btn:active {
    transform: translateY(0);
}

.submit-btn:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Password requirements grid */
.requirements {
    margin-top: 15px;
    font-size: 12px;
    color: #666;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
}

.req-item {
    display: flex;
    align-items: center;
    gap: 6px;
}

.req-item i {
    font-size: 10px;
    width: 14px;
}

.req-item.valid {
    color: #28a745;
}

.req-item.invalid {
    color: #dc3545;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .container {
        padding: 25px 20px;
        margin: 20px;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .header h2 {
        font-size: 24px;
    }
    
    .requirements {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 20px 15px;
    }
    
    .header h2 {
        font-size: 22px;
    }
    
    .form-group input,
    .form-group select {
        padding: 10px 12px;
    }
    
    .submit-btn {
        padding: 12px;
        font-size: 15px;
    }
}
</style>
</head>

<body>

<div class="container">
<div class="header">
    <h2>Teacher Registration</h2>
    <p>All fields marked * are required</p>
</div>

<form method="POST" action="register_teacher_process.php" enctype="multipart/form-data" onsubmit="return validateForm()">

<div class="form-grid">

<div class="form-group">
<label>Full Name *</label>
<input type="text" name="teacher_name" id="teacher_name" required>
<div class="error" id="nameErr">Minimum 2 characters</div>
</div>

<div class="form-group">
<label>Email *</label>
<input type="email" name="email" id="email" required>
<div class="error" id="emailErr">Invalid email</div>
</div>

<div class="form-group">
<label>Username *</label>
<input type="text" name="username" id="username" required>
<div class="error" id="userErr">3–20 characters</div>
</div>

<div class="form-group">
<label>Department *</label>
<select name="department" id="department" required>
<option value="">Select</option>
<option>Computer Science</option>
<option>Accounting</option>
<option>Marketing</option>
<option>Business</option>
</select>
<div class="error" id="deptErr">Select department</div>
</div>

<div class="form-group">
<label>Phone *</label>
<input type="text" name="phone" id="phone" required>
<div class="error" id="phoneErr">Must start with 09, 07 or +251</div>
<div class="success" id="phoneOk">Valid number</div>
</div>

<div class="form-group">
<label>Password *</label>
<div class="input-icon">
<input type="password" name="password" id="password" required>
<i class="fas fa-eye" onclick="toggle('password')"></i>
</div>
<div class="strength" id="strength">Strength: Weak</div>
</div>

<div class="form-group">
<label>Confirm Password *</label>
<div class="input-icon">
<input type="password" id="confirm" required>
<i class="fas fa-eye" onclick="toggle('confirm')"></i>
</div>
<div class="error" id="passErr">Passwords do not match</div>
</div>

<div class="form-group">
<label>Profile Photo</label>
<div class="file-box" onclick="document.getElementById('photo').click()">
<i class="fas fa-upload"></i><br>
Click to upload (max 2MB)
</div>
<input type="file" name="photo" id="photo" hidden>
<div class="error" id="fileErr">Invalid file</div>
</div>

</div>

<button class="submit-btn">Register Teacher</button>
</form>
</div>
<script>
// Toggle password visibility
function toggle(id) {
    const field = document.getElementById(id);
    const icon = field.parentElement.querySelector('i');
    if (field.type === "password") {
        field.type = "text";
        icon.className = "fas fa-eye-slash";
    } else {
        field.type = "password";
        icon.className = "fas fa-eye";
    }
}

// Ethiopian phone validation
function validateEthiopianPhone(value) {
    if (!value.trim()) return false;
    
    // Remove all non-digit characters except +
    const cleaned = value.replace(/[^\d+]/g, '');
    
    // Check patterns: 09xxxxxxxxx, 07xxxxxxxxx, +251xxxxxxxxx
    return /^(09|07|\+251)[0-9]{8,9}$/.test(cleaned);
}

// Format phone number
function formatPhoneNumber(value) {
    const cleaned = value.replace(/[^\d+]/g, '');
    
    if (cleaned.startsWith('09') && cleaned.length === 11) {
        return '+251' + cleaned.substring(1);
    } else if (cleaned.startsWith('07') && cleaned.length === 11) {
        return '+251' + cleaned.substring(1);
    } else if (cleaned.startsWith('251') && (cleaned.startsWith('2519') || cleaned.startsWith('2517'))) {
        return '+' + cleaned;
    }
    
    return value;
}

// Validate email
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Validate name (minimum 2 characters, letters and spaces only)
function validateName(name) {
    return name.trim().length >= 2 && /^[A-Za-z\s]+$/.test(name);
}

// Validate username (3-20 characters, alphanumeric and underscore)
function validateUsername(username) {
    return username.trim().length >= 3 && 
           username.trim().length <= 20 && 
           /^[A-Za-z0-9_]+$/.test(username);
}

// Validate password strength
function validatePassword(password) {
    if (password.length < 8) return false;
    
    // Check for at least one uppercase letter
    if (!/[A-Z]/.test(password)) return false;
    
    // Check for at least one lowercase letter
    if (!/[a-z]/.test(password)) return false;
    
    // Check for at least one number
    if (!/[0-9]/.test(password)) return false;
    
    // Check for at least one special character
    if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) return false;
    
    return true;
}

// Check password strength level
function checkPasswordStrength(password) {
    let score = 0;
    if (password.length >= 8) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[a-z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) score++;
    
    return score;
}

// Real-time validation functions
function validateNameField() {
    const nameField = document.getElementById('teacher_name');
    const nameError = document.getElementById('nameErr');
    const name = nameField.value.trim();
    
    if (!name) {
        nameError.textContent = "Full name is required";
        nameError.style.display = "flex";
        nameField.classList.add("error-border");
        nameField.classList.remove("success-border");
        return false;
    } else if (!validateName(name)) {
        nameError.textContent = "Name must be at least 2 letters (no numbers/symbols)";
        nameError.style.display = "flex";
        nameField.classList.add("error-border");
        nameField.classList.remove("success-border");
        return false;
    } else {
        nameError.style.display = "none";
        nameField.classList.remove("error-border");
        nameField.classList.add("success-border");
        return true;
    }
}

function validateEmailField() {
    const emailField = document.getElementById('email');
    const emailError = document.getElementById('emailErr');
    const email = emailField.value.trim();
    
    if (!email) {
        emailError.textContent = "Email is required";
        emailError.style.display = "flex";
        emailField.classList.add("error-border");
        emailField.classList.remove("success-border");
        return false;
    } else if (!validateEmail(email)) {
        emailError.textContent = "Please enter a valid email address";
        emailError.style.display = "flex";
        emailField.classList.add("error-border");
        emailField.classList.remove("success-border");
        return false;
    } else {
        emailError.style.display = "none";
        emailField.classList.remove("error-border");
        emailField.classList.add("success-border");
        return true;
    }
}

function validateUsernameField() {
    const usernameField = document.getElementById('username');
    const usernameError = document.getElementById('userErr');
    const username = usernameField.value.trim();
    
    if (!username) {
        usernameError.textContent = "Username is required";
        usernameError.style.display = "flex";
        usernameField.classList.add("error-border");
        usernameField.classList.remove("success-border");
        return false;
    } else if (!validateUsername(username)) {
        usernameError.textContent = "Username must be 3-20 characters (letters, numbers, underscore)";
        usernameError.style.display = "flex";
        usernameField.classList.add("error-border");
        usernameField.classList.remove("success-border");
        return false;
    } else {
        usernameError.style.display = "none";
        usernameField.classList.remove("error-border");
        usernameField.classList.add("success-border");
        return true;
    }
}

function validateDepartmentField() {
    const departmentField = document.getElementById('department');
    const departmentError = document.getElementById('deptErr');
    const department = departmentField.value;
    
    if (!department) {
        departmentError.textContent = "Please select a department";
        departmentError.style.display = "flex";
        departmentField.classList.add("error-border");
        departmentField.classList.remove("success-border");
        return false;
    } else {
        departmentError.style.display = "none";
        departmentField.classList.remove("error-border");
        departmentField.classList.add("success-border");
        return true;
    }
}

function validatePhoneField() {
    const phoneField = document.getElementById('phone');
    const phoneError = document.getElementById('phoneErr');
    const phoneSuccess = document.getElementById('phoneOk');
    const phone = phoneField.value.trim();
    
    if (!phone) {
        phoneError.style.display = "none";
        phoneSuccess.style.display = "none";
        phoneField.classList.remove("error-border", "success-border");
        return true; // Phone is optional
    } else if (!validateEthiopianPhone(phone)) {
        phoneError.textContent = "Must start with 09, 07 or +251 (9-10 digits)";
        phoneError.style.display = "flex";
        phoneSuccess.style.display = "none";
        phoneField.classList.add("error-border");
        phoneField.classList.remove("success-border");
        return false;
    } else {
        phoneError.style.display = "none";
        phoneSuccess.style.display = "flex";
        phoneField.classList.remove("error-border");
        phoneField.classList.add("success-border");
        
        // Auto-format phone number
        const formatted = formatPhoneNumber(phone);
        if (formatted !== phone) {
            phoneField.value = formatted;
        }
        return true;
    }
}

function validatePasswordField() {
    const passwordField = document.getElementById('password');
    const password = passwordField.value;
    const strengthDiv = document.getElementById('strength');
    
    // Update strength display
    if (password) {
        const score = checkPasswordStrength(password);
        const levels = ["weak", "fair", "good", "strong", "very-strong"];
        const labels = ["Weak", "Fair", "Good", "Strong", "Very Strong"];
        
        strengthDiv.className = "strength";
        strengthDiv.classList.add(levels[score]);
        
        // Update text content
        let strengthText = strengthDiv.querySelector('.strength-text');
        if (!strengthText) {
            strengthText = document.createElement('span');
            strengthText.className = 'strength-text';
            strengthDiv.appendChild(strengthText);
        }
        strengthText.textContent = labels[score];
        
        // Add visual strength bar if not exists
        let strengthBar = strengthDiv.querySelector('.strength-bar');
        let strengthFill = strengthDiv.querySelector('.strength-fill');
        
        if (!strengthBar) {
            strengthBar = document.createElement('div');
            strengthBar.className = 'strength-bar';
            strengthFill = document.createElement('div');
            strengthFill.className = 'strength-fill';
            strengthBar.appendChild(strengthFill);
            strengthDiv.appendChild(strengthBar);
        }
        
        // Update fill width
        const widths = ["25%", "50%", "75%", "100%", "100%"];
        strengthFill.style.width = widths[score];
        
        // Update field styling
        if (validatePassword(password)) {
            passwordField.classList.remove("error-border");
            passwordField.classList.add("success-border");
            return true;
        } else {
            passwordField.classList.add("error-border");
            passwordField.classList.remove("success-border");
            return false;
        }
    } else {
        // Reset if empty
        passwordField.classList.remove("error-border", "success-border");
        return false;
    }
}

function validateConfirmPasswordField() {
    const passwordField = document.getElementById('password');
    const confirmField = document.getElementById('confirm');
    const confirmError = document.getElementById('passErr');
    const password = passwordField.value;
    const confirmPassword = confirmField.value;
    
    if (!confirmPassword) {
        confirmError.style.display = "none";
        confirmField.classList.remove("error-border", "success-border");
        return false;
    } else if (password !== confirmPassword) {
        confirmError.textContent = "Passwords do not match";
        confirmError.style.display = "flex";
        confirmField.classList.add("error-border");
        confirmField.classList.remove("success-border");
        return false;
    } else if (!validatePassword(password)) {
        confirmError.textContent = "Password does not meet requirements";
        confirmError.style.display = "flex";
        confirmField.classList.add("error-border");
        confirmField.classList.remove("success-border");
        return false;
    } else {
        confirmError.style.display = "none";
        confirmField.classList.remove("error-border");
        confirmField.classList.add("success-border");
        return true;
    }
}

function validateFileUpload() {
    const fileInput = document.getElementById('photo');
    const fileError = document.getElementById('fileErr');
    
    if (fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const maxSize = 2 * 1024 * 1024; // 2MB
        
        if (!file.type.match('image.*')) {
            fileError.textContent = "Only image files allowed (JPG, PNG, GIF)";
            fileError.style.display = "flex";
            fileInput.value = "";
            return false;
        } else if (file.size > maxSize) {
            fileError.textContent = "File size must be less than 2MB";
            fileError.style.display = "flex";
            fileInput.value = "";
            return false;
        } else {
            fileError.style.display = "none";
            
            // Update file display
            const fileBox = document.querySelector('.file-box');
            const size = (file.size / (1024 * 1024)).toFixed(2);
            
            // Remove existing file name if any
            let fileNameDiv = fileBox.querySelector('.file-name');
            if (!fileNameDiv) {
                fileNameDiv = document.createElement('div');
                fileNameDiv.className = 'file-name';
                fileBox.appendChild(fileNameDiv);
            }
            
            fileNameDiv.textContent = `Selected: ${file.name} (${size} MB)`;
            return true;
        }
    }
    
    fileError.style.display = "none";
    return true; // File upload is optional
}

// Update submit button state
function updateSubmitButton() {
    const isNameValid = validateNameField();
    const isEmailValid = validateEmailField();
    const isUsernameValid = validateUsernameField();
    const isDepartmentValid = validateDepartmentField();
    const isPhoneValid = validatePhoneField();
    const isPasswordValid = validatePasswordField();
    const isConfirmValid = validateConfirmPasswordField();
    const isFileValid = validateFileUpload();
    
    // All required fields must be valid, phone and file are optional
    const isValid = isNameValid && isEmailValid && isUsernameValid && 
                   isDepartmentValid && isPhoneValid && 
                   validatePassword(document.getElementById('password').value) && 
                   isConfirmValid && isFileValid;
    
    const submitBtn = document.querySelector('.submit-btn');
    if (submitBtn) {
        submitBtn.disabled = !isValid;
    }
}

// Main form validation
function validateForm() {
    // Validate all fields
    const isNameValid = validateNameField();
    const isEmailValid = validateEmailField();
    const isUsernameValid = validateUsernameField();
    const isDepartmentValid = validateDepartmentField();
    const isPhoneValid = validatePhoneField();
    const isPasswordValid = validatePassword(document.getElementById('password').value);
    const isConfirmValid = document.getElementById('password').value === document.getElementById('confirm').value;
    const isFileValid = validateFileUpload();
    
    // Check each validation
    if (!isNameValid) {
        alert("Please enter a valid full name (minimum 2 letters, no numbers/symbols)");
        document.getElementById('teacher_name').focus();
        return false;
    }
    
    if (!isEmailValid) {
        alert("Please enter a valid email address");
        document.getElementById('email').focus();
        return false;
    }
    
    if (!isUsernameValid) {
        alert("Username must be 3-20 characters (letters, numbers, underscore only)");
        document.getElementById('username').focus();
        return false;
    }
    
    if (!isDepartmentValid) {
        alert("Please select a department");
        document.getElementById('department').focus();
        return false;
    }
    
    if (!isPhoneValid && document.getElementById('phone').value.trim()) {
        alert("Phone number must start with 09, 07 or +251 followed by 9-10 digits");
        document.getElementById('phone').focus();
        return false;
    }
    
    if (!isPasswordValid) {
        alert("Password must be at least 8 characters and contain:\n• One uppercase letter\n• One lowercase letter\n• One number\n• One special character");
        document.getElementById('password').focus();
        return false;
    }
    
    if (!isConfirmValid) {
        alert("Passwords do not match");
        document.getElementById('confirm').focus();
        return false;
    }
    
    if (!isFileValid) {
        alert("Please select a valid image file (max 2MB) or remove the file");
        document.getElementById('photo').focus();
        return false;
    }
    
    return true;
}

// Initialize event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Set up real-time validation for all fields
    const fields = [
        'teacher_name', 'email', 'username', 'department', 
        'phone', 'password', 'confirm'
    ];
    
    fields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', updateSubmitButton);
            field.addEventListener('blur', updateSubmitButton);
        }
    });
    
    // Special handling for department (select element)
    document.getElementById('department').addEventListener('change', updateSubmitButton);
    
    // File upload handling
    document.getElementById('photo').addEventListener('change', function() {
        validateFileUpload();
        updateSubmitButton();
    });
    
    // Initialize submit button state
    updateSubmitButton();
    
    // Phone field placeholder and auto-format
    const phoneField = document.getElementById('phone');
    phoneField.addEventListener('focus', function() {
        if (!this.value && !this.placeholder) {
            this.placeholder = "09xxxxxxxxx or 07xxxxxxxxx";
        }
    });
    
    phoneField.addEventListener('blur', function() {
        if (this.value && validateEthiopianPhone(this.value)) {
            const formatted = formatPhoneNumber(this.value);
            if (formatted !== this.value) {
                this.value = formatted;
            }
        }
    });
    
    // Create strength display if not exists
    const strengthDiv = document.getElementById('strength');
    if (strengthDiv && !strengthDiv.querySelector('.strength-bar')) {
        const strengthHTML = `
            <div class="strength-meter">
                <div class="strength-bar">
                    <div class="strength-fill"></div>
                </div>
                <span class="strength-text">Weak</span>
            </div>
        `;
        strengthDiv.innerHTML = strengthHTML;
    }
});
</script>

</body>
</html>
