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
<title>Sign Up</title> 
<link rel="icon" href="images/Logos/AU Logo.png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background: linear-gradient(135deg, #d2e5f5ff 0%, #000000ff 100%);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.container.box {
    background: white;
    width: 100%;
    max-width: 800px;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.25);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

h1 {
    text-align: center;
    color: #003b6f;
    font-size: 36px;
    font-weight: 800;
    margin-bottom: 30px;
    position: relative;
    padding-bottom: 20px;
}

h1::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 5px;
    background: linear-gradient(90deg, #003b6f, #4da6ff, #003b6f);
    border-radius: 3px;
}

/* Tab Navigation */
.nav-tabs {
    display: flex;
    list-style: none;
    margin-bottom: 30px;
    border-bottom: none;
    gap: 10px;
}

.nav-item {
    flex: 1;
}

.nav-link {
    display: block;
    padding: 15px;
    text-align: center;
    text-decoration: none;
    font-weight: 600;
    font-size: 15px;
    border-radius: 10px 10px 0 0;
    background: #f8fafc;
    color: #666;
    border: 2px solid #e0e0e0;
    border-bottom: none;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.nav-link.active_tab1 {
    background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
    color: white;
    border-color: #003b6f;
    box-shadow: 0 5px 15px rgba(0, 59, 111, 0.2);
}

.nav-link.inactive_tab1 {
    background: #f8fafc;
    color: #999;
    cursor: not-allowed;
    opacity: 0.6;
}

.nav-link:not(.inactive_tab1):hover {
    background: #e9f7fe;
    color: #003b6f;
    border-color: #003b6f;
    transform: translateY(-2px);
}

/* Progress indicator */
.nav-link::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0%;
    height: 3px;
    background: #003b6f;
    transition: width 0.3s ease;
}

.nav-link.active_tab1::after {
    width: 100%;
}

/* Tab Content */
.tab-content {
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.tab-pane {
    display: none;
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.3s ease;
}

.tab-pane.active {
    display: block;
    opacity: 1;
    transform: translateY(0);
}

/* Panel Styling */
.panel {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.panel-heading {
    background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
    color: white;
    padding: 20px 25px;
    font-size: 20px;
    font-weight: 700;
    border-bottom: 3px solid #ffc107;
}

.panel-body {
    padding: 30px;
}

/* Form Elements */
.form-group {
    margin-bottom: 25px;
    position: relative;
}

label {
    display: block;
    color: #003b6f;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 8px;
}

label::after {
    content: '*';
    color: #dc3545;
    margin-left: 3px;
}

label.radio-inline::after {
    content: '';
}

input[type="text"],
input[type="email"],
input[type="password"],
select,
textarea {
    width: 100%;
    padding: 15px 18px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 15px;
    transition: all 0.3s ease;
    background: #f8fafc;
    color: #333;
}

input:focus,
select:focus,
textarea:focus {
    outline: none;
    border-color: #003b6f;
    box-shadow: 0 0 0 3px rgba(0, 59, 111, 0.1);
    background: white;
    transform: translateY(-2px);
}

input[type="password"] {
    padding-right: 50px;
}

textarea {
    min-height: 120px;
    resize: vertical;
    line-height: 1.6;
}

/* Password toggle button */
.toggle-password {
    position: absolute;
    right: 15px;
    top: 40px;
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    font-size: 16px;
    padding: 10px;
    border-radius: 50%;
    transition: all 0.3s ease;
    z-index: 2;
}

.toggle-password:hover {
    color: #003b6f;
    background: rgba(0, 59, 111, 0.1);
}

/* Radio buttons */
.radio-inline {
    display: inline-flex;
    align-items: center;
    margin-right: 20px;
    cursor: pointer;
    color: #555;
    font-weight: 500;
}

.radio-inline input[type="radio"] {
    margin-right: 8px;
    accent-color: #003b6f;
    width: 18px;
    height: 18px;
}

/* Validation messages */
.text-danger {
    display: block;
    font-size: 12px;
    color: #dc3545;
    margin-top: 5px;
    display: none;
}

.text-danger.show {
    display: block;
}

/* Success validation */
input.valid,
select.valid,
textarea.valid {
    border-color: #28a745;
    background: #f8fff9;
}

input.invalid,
select.invalid,
textarea.invalid {
    border-color: #dc3545;
    background: #fff8f8;
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

/* Buttons */
button {
    padding: 16px 32px;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    margin: 0 10px;
}

button::before {
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

button:hover::before {
    left: 100%;
}

.btn-info {
    background: linear-gradient(135deg, #17a2b8 0%, #0dcaf0 100%);
    color: white;
}

.btn-info:hover:not(:disabled) {
    background: linear-gradient(135deg, #0dcaf0 0%, #17a2b8 100%);
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(23, 162, 184, 0.3);
}

.btn-default {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
}

.btn-default:hover:not(:disabled) {
    background: linear-gradient(135deg, #495057 0%, #6c757d 100%);
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(108, 117, 125, 0.3);
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.btn-success:hover:not(:disabled) {
    background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(40, 167, 69, 0.3);
}

button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
    box-shadow: none !important;
}

/* Button container */
div[align="center"] {
    margin-top: 30px;
    display: flex;
    justify-content: center;
    gap: 15px;
}

/* Loading spinner */
.btn-loading {
    position: relative;
    color: transparent !important;
}

.btn-loading::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    top: 50%;
    left: 50%;
    margin-left: -10px;
    margin-top: -10px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 768px) {
    .container.box {
        padding: 30px 25px;
        margin: 20px;
    }
    
    h1 {
        font-size: 28px;
    }
    
    .nav-tabs {
        flex-direction: column;
        gap: 5px;
    }
    
    .nav-link {
        border-radius: 8px;
        margin-bottom: 5px;
        font-size: 14px;
        padding: 12px;
    }
    
    .panel-body {
        padding: 20px;
    }
    
    button {
        padding: 14px 24px;
        font-size: 15px;
        margin: 5px;
    }
    
    div[align="center"] {
        flex-wrap: wrap;
    }
    
    .toggle-password {
        top: 38px;
        right: 10px;
    }
}

@media (max-width: 480px) {
    .container.box {
        padding: 25px 20px;
    }
    
    h1 {
        font-size: 24px;
    }
    
    .panel-heading {
        padding: 15px 20px;
        font-size: 18px;
    }
    
    input, select, textarea {
        padding: 12px 15px;
        font-size: 14px;
    }
    
    button {
        padding: 12px 20px;
        font-size: 14px;
    }
    
    .toggle-password {
        top: 36px;
        right: 8px;
        padding: 8px;
    }
}

/* Background pattern */
body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: 
        radial-gradient(circle at 20% 80%, rgba(77, 166, 255, 0.15) 0%, transparent 40%),
        radial-gradient(circle at 80% 20%, rgba(0, 95, 163, 0.15) 0%, transparent 40%);
    z-index: -1;
}

/* Password strength indicator */
.password-strength {
    margin-top: 5px;
    height: 4px;
    border-radius: 2px;
    transition: all 0.3s ease;
}

.strength-weak {
    width: 33%;
    background: #dc3545;
}

.strength-medium {
    width: 66%;
    background: #ffc107;
}

.strength-strong {
    width: 100%;
    background: #28a745;
}
</style>

</head>
<body>
<br><br>
<div class="container box">
   <h1 align="center">Register</h1>
   <form method="POST" action="register_student_process.php" id="register_form">
    <ul class="nav nav-tabs">
     <li class="nav-item">
      <a class="nav-link active_tab1" style="border:1px solid #ccc" id="list_login_details">Login Details</a>
     </li>
     <li class="nav-item">
      <a class="nav-link inactive_tab1" id="list_personal_details" style="border:1px solid #ccc">Personal Details</a>
     </li>
     <li class="nav-item">
      <a class="nav-link inactive_tab1" id="list_contact_details" style="border:1px solid #ccc">Academic & Contact</a>
     </li>
    </ul>

    <div class="tab-content" style="margin-top:16px;">

     <!-- ----------------------------------------------- -->
     <!-- STEP 1: REGISTER DETAILS -->
     <!-- ----------------------------------------------- -->
     <div class="tab-pane active" id="login_details">
      <div class="panel panel-default">
       <div class="panel-heading">Login Details</div>
       <div class="panel-body">

        <!-- 1 -->
        <div class="form-group">
         <label>Email Address</label>
         <input type="email" name="email" id="email" class="form-control" />
         <span id="error_email" class="text-danger"></span>
        </div>

        <!-- 2 -->
        <div class="form-group">
         <label>Password</label>
         <input type="password" name="password" id="password" class="form-control" />
         <button type="button" class="toggle-password" onclick="togglePassword('password', 'togglePasswordIcon')">
            <i id="togglePasswordIcon" class="fas fa-eye"></i>
         </button>
         <span id="error_password" class="text-danger"></span>
         <div id="passwordStrength" class="password-strength"></div>
        </div>

        <!-- 3 -->
        <div class="form-group">
         <label>Confirm Password</label>
         <input type="password" name="confirm_password" id="confirm_password" class="form-control" />
         <button type="button" class="toggle-password" onclick="togglePassword('confirm_password', 'toggleConfirmPasswordIcon')">
            <i id="toggleConfirmPasswordIcon" class="fas fa-eye"></i>
         </button>
         <span id="error_confirm_password" class="text-danger"></span>
        </div>

        <br />
        <div align="center">
         <button type="button" name="btn_login_details" id="btn_login_details" class="btn btn-info btn-lg">Next</button>
        </div>
       </div>
      </div>
     </div>
     <!-- ----------------------------------------------- -->
     <!-- STEP 2: PERSONAL DETAILS -->
     <!-- ----------------------------------------------- -->
     <div class="tab-pane fade" id="personal_details">
      <div class="panel panel-default">
       <div class="panel-heading">Personal Details</div>
       <div class="panel-body">

        <!-- 4 -->
        <div class="form-group">
         <label>First Name</label>
         <input type="text" name="first_name" id="first_name" class="form-control" />
         <span id="error_first_name" class="text-danger"></span>
        </div>

        <!-- 5 -->
        <div class="form-group">
         <label>Last Name</label>
         <input type="text" name="last_name" id="last_name" class="form-control" />
         <span id="error_last_name" class="text-danger"></span>
        </div>

        <!-- 6 -->
        <div class="form-group">
         <label>Gender</label><br>
         <label class="radio-inline">
          <input type="radio" name="gender" value="Male" checked> Male
         </label>
         &nbsp;&nbsp;&nbsp;
         <label class="radio-inline">
          <input type="radio" name="gender" value="Female"> Female
         </label>
        </div>

        <!-- 7 -->
        <div class="form-group">
       <label>Student ID</label>
       <input type="text" name="student_id" id="student_id" class="form-control" />
       <small style="color: #666; font-size: 12px;">Enter your official student ID number (e.g., AU/2023/001)</small>
       <span id="error_student_id" class="text-danger"></span>
       </div>

        <br />
        <div align="center">
         <button type="button" name="previous_btn_personal_details" id="previous_btn_personal_details" class="btn btn-default btn-lg">Previous</button>
         <button type="button" name="btn_personal_details" id="btn_personal_details" class="btn btn-info btn-lg">Next</button>
        </div>

       </div>
      </div>
     </div>

     <!-- ----------------------------------------------- -->
     <!-- STEP 3: ACADEMIC & CONTACT DETAILS -->
     <!-- ----------------------------------------------- -->
     <div class="tab-pane fade" id="contact_details">
      <div class="panel panel-default">
       <div class="panel-heading">Academic & Contact Details</div>
       <div class="panel-body">

      <!-- PROGRAM -->
<div class="form-group">
    <label>Program</label>
    <select name="program" id="program" class="form-control">
        <option value="">Select Program</option>
        <option value="TVET">TVET</option>
        <option value="DEGREE">Degree</option>
        <option value="MASTERS">Masters</option>
    </select>
    <span id="error_program" class="text-danger"></span>
</div>

<!-- STUDY MODE -->
<div class="form-group">
    <label>Study Mode</label>
    <select name="study_mode" id="study_mode" class="form-control">
        <option value="">Select Study Mode</option>
        <option value="REGULAR">Regular</option>
        <option value="EXTENSION">Extension</option>
        <option value="DISTANCE">Distance</option>
    </select>
    <span id="error_study_mode" class="text-danger"></span>
</div>

<!-- DEPARTMENT -->
<div class="form-group">
    <label>Department</label>
    <select name="department" id="department" class="form-control">
        <option value="">Select Department</option>
    </select>
    <span id="error_department" class="text-danger"></span>
</div>

<!-- YEAR -->
<div class="form-group">
    <label>Year / Level</label>
    <select name="year" id="year" class="form-control">
        <option value="">Select Year</option>
        <!-- Years will be populated dynamically -->
    </select>
    <span id="error_year" class="text-danger"></span>
</div>

        <!-- 10 -->
        <div class="form-group">
         <label>Address</label>
         <textarea name="address" id="address" class="form-control"></textarea>
         <span id="error_address" class="text-danger"></span>
        </div>

        <!-- 11 -->
        <div class="form-group">
         <label>Phone Number</label>
         <input type="text" name="mobile_no" id="mobile_no" class="form-control" placeholder="09xxxxxxxxx or +251xxxxxxxxx"/>
         <small style="color: #666; font-size: 12px;">Format: 09xxxxxxxxx or +251xxxxxxxxx</small>
         <span id="error_mobile_no" class="text-danger"></span>
        </div>

        <br />
        <div align="center">
         <button type="button" name="previous_btn_contact_details" id="previous_btn_contact_details" class="btn btn-default btn-lg">Previous</button>
         <button type="submit" name="btn_contact_details" id="btn_contact_details" class="btn btn-success btn-lg">
            <span id="submitText">Register</span>
            <span id="submitSpinner" style="display: none;"></span>
         </button>
        </div>

       </div>
      </div>
     </div>

    </div>
   </form>
</div>

<script>
// Password toggle function
function togglePassword(fieldId, iconId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);
    if (field.type === "password") {
        field.type = "text";
        icon.className = "fas fa-eye-slash";
    } else {
        field.type = "password";
        icon.className = "fas fa-eye";
    }
}

// Ethiopian phone validation
function validateEthiopianPhone(phone) {
    if (!phone.trim()) return true; // Phone is optional
    
    const cleaned = phone.replace(/[^\d+]/g, '');
    
    // Accepts: 09xxxxxxxxx, 07xxxxxxxxx, +251xxxxxxxxx
    return /^(09|07|\+251)[0-9]{8,9}$/.test(cleaned);
}

// Format phone number
function formatPhoneNumber(phone) {
    const cleaned = phone.replace(/[^\d+]/g, '');
    
    if (cleaned.startsWith('09') && cleaned.length === 11) {
        return '+251' + cleaned.substring(1);
    } else if (cleaned.startsWith('07') && cleaned.length === 11) {
        return '+251' + cleaned.substring(1);
    }
    
    return phone;
}

// Dynamic form update
const program = document.getElementById("program");
const studyMode = document.getElementById("study_mode");
const department = document.getElementById("department");
const year = document.getElementById("year");

program.addEventListener("change", updateForm);
studyMode.addEventListener("change", updateYears);

function updateForm() {
    department.innerHTML = '<option value="">Select Department</option>';
    year.innerHTML = '<option value="">Select Year</option>';

    if (program.value === "TVET") {
        addDepartments([
            "Accounting TVET",
            "Marketing TVET",
            "Business TVET",
            "IT TVET"
        ]);
        // TVET: 3 years for all modes
        addYears(3);
    } else if (program.value === "DEGREE") {
        addDepartments([
            "Accounting",
            "Marketing Management",
            "Business Management",
            "Computer Science"
        ]);
        updateYears();
    } else if (program.value === "MASTERS") {
        addDepartments([
            "Accounting",
            "Marketing Management",
            "Business Management"
        ]);
        // Masters: 2 years for all modes
        addYears(2);
    }
}

function updateYears() {
    year.innerHTML = '<option value="">Select Year</option>';

    if (program.value === "TVET") {
        // TVET: Always 3 years regardless of study mode
        addYears(3);
    } else if (program.value === "DEGREE") {
        if (studyMode.value === "REGULAR") {
            addYears(4);
        } else if (studyMode.value === "EXTENSION" || studyMode.value === "DISTANCE") {
            addYears(5);
        }
    } else if (program.value === "MASTERS") {
        // Masters: Always 2 years regardless of study mode
        addYears(2);
    }
}

function addDepartments(list) {
    list.forEach(dep => {
        let opt = document.createElement("option");
        opt.text = dep;
        opt.value = dep;
        department.add(opt);
    });
}

function addYears(max) {
    for (let i = 1; i <= max; i++) {
        let opt = document.createElement("option");
        opt.value = i;
        opt.text = i + (i === 1 ? "st Year" : i === 2 ? "nd Year" : i === 3 ? "rd Year" : "th Year");
        year.add(opt);
    }
}

// Multi-step form logic
let currentTab = 0;
showTab(currentTab);

function showTab(n) {
    const tabs = document.querySelectorAll('.tab-pane');
    const navLinks = document.querySelectorAll('.nav-link');
    
    // Hide all tabs
    tabs.forEach(tab => {
        tab.style.display = 'none';
        tab.classList.remove('active');
    });
    
    // Deactivate all nav links
    navLinks.forEach(link => {
        link.classList.remove('active_tab1');
        link.classList.add('inactive_tab1');
    });
    
    // Show current tab
    tabs[n].style.display = 'block';
    tabs[n].classList.add('active');
    
    // Activate current nav link
    navLinks[n].classList.remove('inactive_tab1');
    navLinks[n].classList.add('active_tab1');
}

// Validation functions
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePassword(password) {
    return password.length >= 6;
}

function validateName(name) {
    return name.trim().length >= 2 && /^[A-Za-z\s]+$/.test(name);
}

function validateStudentID(id) {
    return id.trim().length >= 3;
}

// Password strength checker
function checkPasswordStrength(password) {
    let strength = 0;
    const strengthBar = document.getElementById('passwordStrength');
    
    if (password.length >= 8) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) strength++;
    
    strengthBar.className = 'password-strength';
    
    if (strength <= 2) {
        strengthBar.classList.add('strength-weak');
    } else if (strength <= 4) {
        strengthBar.classList.add('strength-medium');
    } else {
        strengthBar.classList.add('strength-strong');
    }
}

// Form validation by tab
function validateStep1() {
    let isValid = true;
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    
    // Email validation
    if (!validateEmail(email.value)) {
        showError('error_email', 'Please enter a valid email address');
        email.classList.add('invalid');
        isValid = false;
    } else {
        hideError('error_email');
        email.classList.remove('invalid');
        email.classList.add('valid');
    }
    
    // Password validation
    if (!validatePassword(password.value)) {
        showError('error_password', 'Password must be at least 6 characters');
        password.classList.add('invalid');
        isValid = false;
    } else {
        hideError('error_password');
        password.classList.remove('invalid');
        password.classList.add('valid');
    }
    
    // Confirm password
    if (password.value !== confirmPassword.value) {
        showError('error_confirm_password', 'Passwords do not match');
        confirmPassword.classList.add('invalid');
        isValid = false;
    } else {
        hideError('error_confirm_password');
        confirmPassword.classList.remove('invalid');
        confirmPassword.classList.add('valid');
    }
    
    return isValid;
}

function validateStep2() {
    let isValid = true;
    const firstName = document.getElementById('first_name');
    const lastName = document.getElementById('last_name');
    const studentId = document.getElementById('student_id');
    
    // First name validation
    if (!validateName(firstName.value)) {
        showError('error_first_name', 'Please enter a valid first name (minimum 2 letters)');
        firstName.classList.add('invalid');
        isValid = false;
    } else {
        hideError('error_first_name');
        firstName.classList.remove('invalid');
        firstName.classList.add('valid');
    }
    
    // Last name validation
    if (!validateName(lastName.value)) {
        showError('error_last_name', 'Please enter a valid last name (minimum 2 letters)');
        lastName.classList.add('invalid');
        isValid = false;
    } else {
        hideError('error_last_name');
        lastName.classList.remove('invalid');
        lastName.classList.add('valid');
    }
    
    // Student ID validation
    if (!validateStudentID(studentId.value)) {
        showError('error_student_id', 'Please enter a valid student ID (minimum 3 characters)');
        studentId.classList.add('invalid');
        isValid = false;
    } else {
        hideError('error_student_id');
        studentId.classList.remove('invalid');
        studentId.classList.add('valid');
    }
    
    return isValid;
}

function validateStep3() {
    let isValid = true;
    const program = document.getElementById('program');
    const studyMode = document.getElementById('study_mode');
    const department = document.getElementById('department');
    const year = document.getElementById('year');
    const phone = document.getElementById('mobile_no');
    const address = document.getElementById('address');
    
    // Program validation
    if (!program.value) {
        showError('error_program', 'Please select a program');
        program.classList.add('invalid');
        isValid = false;
    } else {
        hideError('error_program');
        program.classList.remove('invalid');
        program.classList.add('valid');
    }
    
    // Study mode validation
    if (!studyMode.value) {
        showError('error_study_mode', 'Please select study mode');
        studyMode.classList.add('invalid');
        isValid = false;
    } else {
        hideError('error_study_mode');
        studyMode.classList.remove('invalid');
        studyMode.classList.add('valid');
    }
    
    // Department validation
    if (!department.value) {
        showError('error_department', 'Please select department');
        department.classList.add('invalid');
        isValid = false;
    } else {
        hideError('error_department');
        department.classList.remove('invalid');
        department.classList.add('valid');
    }
    
    // Year validation
    if (!year.value) {
        showError('error_year', 'Please select year');
        year.classList.add('invalid');
        isValid = false;
    } else {
        hideError('error_year');
        year.classList.remove('invalid');
        year.classList.add('valid');
    }
    
    // Address validation
    if (address.value.trim().length < 5) {
        showError('error_address', 'Please enter a valid address (minimum 5 characters)');
        address.classList.add('invalid');
        isValid = false;
    } else {
        hideError('error_address');
        address.classList.remove('invalid');
        address.classList.add('valid');
    }
    
    // Phone validation - OPTIONAL
    if (phone.value && !validateEthiopianPhone(phone.value)) {
        showError('error_mobile_no', 'Phone must start with 09, 07 or +251 followed by 9-10 digits');
        phone.classList.add('invalid');
        isValid = false;
    } else if (phone.value) {
        hideError('error_mobile_no');
        phone.classList.remove('invalid');
        phone.classList.add('valid');
        phone.value = formatPhoneNumber(phone.value);
    }
    
    return isValid;
}

// Error handling functions
function showError(elementId, message) {
    const element = document.getElementById(elementId);
    element.textContent = message;
    element.classList.add('show');
}

function hideError(elementId) {
    const element = document.getElementById(elementId);
    element.classList.remove('show');
}

// Step navigation
document.getElementById('btn_login_details').addEventListener('click', function() {
    if (validateStep1()) {
        currentTab = 1;
        showTab(currentTab);
        // Enable next tab navigation
        document.getElementById('list_personal_details').classList.remove('inactive_tab1');
    }
});

document.getElementById('btn_personal_details').addEventListener('click', function() {
    if (validateStep2()) {
        currentTab = 2;
        showTab(currentTab);
        // Enable next tab navigation
        document.getElementById('list_contact_details').classList.remove('inactive_tab1');
    }
});

document.getElementById('previous_btn_personal_details').addEventListener('click', function() {
    currentTab = 0;
    showTab(currentTab);
});

document.getElementById('previous_btn_contact_details').addEventListener('click', function() {
    currentTab = 1;
    showTab(currentTab);
});

// Form submission
document.getElementById('register_form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (validateStep1() && validateStep2() && validateStep3()) {
        // All validations passed
        // Show loading state
        const submitBtn = document.getElementById('btn_contact_details');
        const submitText = document.getElementById('submitText');
        submitBtn.disabled = true;
        submitText.textContent = 'Registering...';
        submitBtn.classList.add('btn-loading');
        
        // Submit the form
        this.submit();
    } else {
        // Go back to first invalid step
        if (!validateStep1()) {
            currentTab = 0;
            showTab(currentTab);
        } else if (!validateStep2()) {
            currentTab = 1;
            showTab(currentTab);
        } else {
            currentTab = 2;
            showTab(currentTab);
        }
    }
});

// Real-time validation and password strength
document.getElementById('password').addEventListener('input', function() {
    checkPasswordStrength(this.value);
});

document.querySelectorAll('input, select, textarea').forEach(element => {
    element.addEventListener('blur', function() {
        switch(this.id) {
            case 'email':
                validateEmail(this.value) ? markValid(this) : markInvalid(this);
                break;
            case 'password':
                validatePassword(this.value) ? markValid(this) : markInvalid(this);
                break;
            case 'confirm_password':
                this.value === document.getElementById('password').value ? markValid(this) : markInvalid(this);
                break;
            case 'first_name':
            case 'last_name':
                validateName(this.value) ? markValid(this) : markInvalid(this);
                break;
            case 'student_id':
                validateStudentID(this.value) ? markValid(this) : markInvalid(this);
                break;
            case 'mobile_no':
                if (this.value) {
                    validateEthiopianPhone(this.value) ? markValid(this) : markInvalid(this);
                }
                break;
        }
    });
});

function markValid(element) {
    element.classList.remove('invalid');
    element.classList.add('valid');
}

function markInvalid(element) {
    element.classList.remove('valid');
    element.classList.add('invalid');
}

// Phone formatting on blur
document.getElementById('mobile_no').addEventListener('blur', function() {
    if (this.value && validateEthiopianPhone(this.value)) {
        this.value = formatPhoneNumber(this.value);
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tabs
    document.getElementById('list_personal_details').classList.add('inactive_tab1');
    document.getElementById('list_contact_details').classList.add('inactive_tab1');
    
    // Initialize form updates
    updateForm();
    
    // Clear any existing validation classes on page load
    document.querySelectorAll('input, select, textarea').forEach(element => {
        element.classList.remove('valid', 'invalid');
    });
    
    // Set year select to show appropriate placeholder
    year.innerHTML = '<option value="">Select Program first</option>';
    
    // Add event listeners for dynamic form updates
    studyMode.addEventListener('change', function() {
        if (program.value) {
            updateYears();
        }
    });
});
</script>
</body>
</html>