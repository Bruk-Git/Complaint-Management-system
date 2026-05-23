<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_POST['submit'])) {

    $department_name = trim($_POST['department_name']);
    $username        = trim($_POST['username']);
    $email           = trim($_POST['email']);
    $password        = $_POST['password'];

    // Password validation
    if (
        strlen($password) < 8 ||
        !preg_match("/[A-Z]/", $password) ||
        !preg_match("/[a-z]/", $password) ||
        !preg_match("/[0-9]/", $password)
    ) {
        echo "<script>alert('Password must contain at least 8 characters, uppercase, lowercase and a number.');</script>";
    } 
    else {

        // Check email exists
        $check = mysqli_query($conn, "SELECT * FROM department_login WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            echo "<script>alert('Email already exists');</script>";
        } else {

            $hashed = password_hash($password, PASSWORD_DEFAULT);

            mysqli_query($conn, "
                INSERT INTO department_login
                (department_name, username, email, password, status)
                VALUES
                ('$department_name','$username','$email','$hashed','active')
            ");

            echo "<script>
                alert('Department account created successfully');
                window.location.href='manage_departments.php';
            </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="images/Logos/AU Logo.png">
    <title>Add Department | CMS Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .form-container {
            width: 90%;
            max-width: 450px;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            margin: 30px auto;
        }

        .log-img {
            display: block;
            width: 80px;
            margin: 0 auto 20px;
            height: auto;
        }

        h2 {
            color: #003b6f;
            text-align: center;
            margin-bottom: 30px;
            font-size: 26px;
            padding-bottom: 15px;
            border-bottom: 3px solid #003b6f;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .required::after {
            content: " *";
            color: #dc3545;
        }

        input, select {
            width: 100%;
            padding: 14px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #003b6f;
            box-shadow: 0 0 0 3px rgba(0, 59, 111, 0.1);
            background: white;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon input {
            padding-left: 45px;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .show-password {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 15px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .show-password input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin: 0;
        }

        .show-password label {
            margin: 0;
            font-weight: normal;
            color: #666;
            cursor: pointer;
            flex: 1;
        }

        .password-hint {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #007bff;
        }

        .password-hint strong {
            color: #003b6f;
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .password-hint ul {
            padding-left: 20px;
            margin: 8px 0;
        }

        .password-hint li {
            margin: 5px 0;
            font-size: 13px;
            color: #666;
            list-style-type: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .password-hint li i {
            font-size: 12px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        button[type="submit"]:hover {
            background: linear-gradient(135deg, #005fa3 0%, #007acc 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 95, 163, 0.3);
        }

        button[type="submit"]:disabled {
            background: #cccccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 25px;
            color: #003b6f;
            text-decoration: none;
            font-weight: 500;
            padding: 12px;
            border: 1px solid #003b6f;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            background: #003b6f;
            color: white;
            transform: translateX(-5px);
        }

        .form-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .form-header i {
            color: #003b6f;
            font-size: 28px;
            background: rgba(0, 59, 111, 0.1);
            padding: 12px;
            border-radius: 50%;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 30px;
                width: 95%;
            }
            
            h2 {
                font-size: 24px;
            }
            
            input, select {
                padding: 12px;
                font-size: 15px;
            }
            
            button[type="submit"] {
                padding: 14px;
            }
        }

        @media (max-width: 480px) {
            .form-container {
                padding: 25px;
            }
            
            h2 {
                font-size: 22px;
            }
            
            .input-with-icon input {
                padding-left: 40px;
            }
            
            .input-icon {
                left: 12px;
            }
            
            .password-hint {
                padding: 12px;
            }
        }

        /* Validation styling */
        .valid-input {
            border-color: #28a745 !important;
        }

        .invalid-input {
            border-color: #dc3545 !important;
        }

        .loading {
            position: relative;
            color: transparent !important;
        }

        .loading::after {
            content: "";
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="form-container">
        <img class="log-img" src="images/logos/AU Logo.png" alt="AU Logo">
        
        <div class="form-header">
            <i class="fas fa-building"></i>
            <h2>Add Department Account</h2>
        </div>

        <form method="POST" id="departmentForm">
            <div class="form-group">
                <label class="required">Department Name</label>
                <div class="input-with-icon">
                    <i class="input-icon fas fa-university"></i>
                    <input type="text" name="department_name" placeholder="e.g., Computer Science" required>
                </div>
            </div>

            <div class="form-group">
                <label class="required">Username</label>
                <div class="input-with-icon">
                    <i class="input-icon fas fa-user"></i>
                    <input type="text" name="username" placeholder="Enter username" required>
                </div>
            </div>

            <div class="form-group">
                <label class="required">Email Address</label>
                <div class="input-with-icon">
                    <i class="input-icon fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="department@admas.edu.et" required>
                </div>
            </div>

            <div class="form-group">
                <label class="required">Password</label>
                <div class="input-with-icon">
                    <i class="input-icon fas fa-lock"></i>
                    <input type="password" name="password" id="password" placeholder="Create secure password" required>
                </div>
                
                <div class="show-password">
                    <input type="checkbox" id="showPassword" onclick="togglePassword()">
                    <label for="showPassword">Show Password</label>
                </div>

                <div class="password-hint">
                    <strong>Password Requirements:</strong>
                    <ul>
                        <li id="reqLength"><i class="fas fa-times-circle"></i> Minimum 8 characters</li>
                        <li id="reqUpper"><i class="fas fa-times-circle"></i> At least one uppercase letter (A-Z)</li>
                        <li id="reqLower"><i class="fas fa-times-circle"></i> At least one lowercase letter (a-z)</li>
                        <li id="reqNumber"><i class="fas fa-times-circle"></i> At least one number (0-9)</li>
                    </ul>
                </div>
            </div>

            <button type="submit" name="submit" id="submitBtn">
                <i class="fas fa-plus-circle"></i> Create Department Account
            </button>
        </form>

        <a href="manage_departments.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Departments
        </a>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const checkbox = document.getElementById('showPassword');
            
            if (checkbox.checked) {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        }

        // Real-time password strength indicator
        document.getElementById('password').addEventListener('input', function(e) {
            const password = e.target.value;
            
            // Update requirement indicators
            const requirements = {
                length: document.getElementById('reqLength'),
                upper: document.getElementById('reqUpper'),
                lower: document.getElementById('reqLower'),
                number: document.getElementById('reqNumber')
            };
            
            // Length requirement
            if (password.length >= 8) {
                requirements.length.innerHTML = '<i class="fas fa-check-circle" style="color:#28a745"></i> Minimum 8 characters ✓';
                requirements.length.parentElement.classList.add('valid');
            } else {
                requirements.length.innerHTML = '<i class="fas fa-times-circle" style="color:#dc3545"></i> Minimum 8 characters';
                requirements.length.parentElement.classList.remove('valid');
            }
            
            // Uppercase requirement
            if (/[A-Z]/.test(password)) {
                requirements.upper.innerHTML = '<i class="fas fa-check-circle" style="color:#28a745"></i> At least one uppercase letter ✓';
                requirements.upper.parentElement.classList.add('valid');
            } else {
                requirements.upper.innerHTML = '<i class="fas fa-times-circle" style="color:#dc3545"></i> At least one uppercase letter';
                requirements.upper.parentElement.classList.remove('valid');
            }
            
            // Lowercase requirement
            if (/[a-z]/.test(password)) {
                requirements.lower.innerHTML = '<i class="fas fa-check-circle" style="color:#28a745"></i> At least one lowercase letter ✓';
                requirements.lower.parentElement.classList.add('valid');
            } else {
                requirements.lower.innerHTML = '<i class="fas fa-times-circle" style="color:#dc3545"></i> At least one lowercase letter';
                requirements.lower.parentElement.classList.remove('valid');
            }
            
            // Number requirement
            if (/[0-9]/.test(password)) {
                requirements.number.innerHTML = '<i class="fas fa-check-circle" style="color:#28a745"></i> At least one number ✓';
                requirements.number.parentElement.classList.add('valid');
            } else {
                requirements.number.innerHTML = '<i class="fas fa-times-circle" style="color:#dc3545"></i> At least one number';
                requirements.number.parentElement.classList.remove('valid');
            }
        });

        // Form validation
        document.getElementById('departmentForm').addEventListener('submit', function(e) {
            const password = document.querySelector('input[name="password"]').value;
            
            // Password validation
            if (password.length < 8) {
                alert('Password must be at least 8 characters long.');
                e.preventDefault();
                return;
            }
            
            if (!/[A-Z]/.test(password)) {
                alert('Password must contain at least one uppercase letter.');
                e.preventDefault();
                return;
            }
            
            if (!/[a-z]/.test(password)) {
                alert('Password must contain at least one lowercase letter.');
                e.preventDefault();
                return;
            }
            
            if (!/[0-9]/.test(password)) {
                alert('Password must contain at least one number.');
                e.preventDefault();
                return;
            }
            
            // If all validations pass, show loading state
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Account...';
            submitBtn.disabled = true;
        });

        // Add visual feedback on input focus
        const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>