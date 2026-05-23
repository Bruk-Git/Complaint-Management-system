<?php
session_start();
include "Connection.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Get admin ID from URL
$admin_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch admin data
$admin = null;
if ($admin_id > 0) {
    $query = "SELECT * FROM admin WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    $stmt->close();
}

// If admin not found, redirect
if (!$admin) {
    header("Location: manage_admins.php?error=notfound");
    exit();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $status = $_POST['status'];
    $change_password = isset($_POST['change_password']) ? true : false;
    
    // Validate inputs
    $errors = [];
    
    if (empty($full_name)) {
        $errors[] = "Full name is required";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    // Check if email already exists (excluding current admin)
    $check_email = "SELECT id FROM admin WHERE email = ? AND id != ?";
    $stmt = $conn->prepare($check_email);
    $stmt->bind_param("si", $email, $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors[] = "Email already exists";
    }
    $stmt->close();
    
    // Handle password change if requested
    $password_update = "";
    if ($change_password) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if (empty($new_password)) {
            $errors[] = "New password is required";
        } elseif (strlen($new_password) < 8) {
            $errors[] = "Password must be at least 8 characters";
        } elseif ($new_password !== $confirm_password) {
            $errors[] = "Passwords do not match";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $password_update = ", password = '$hashed_password'";
        }
    }
    
    // If no errors, update admin
    if (empty($errors)) {
        $update_query = "UPDATE admin SET 
                        full_name = ?, 
                        email = ?, 
                        status = ? 
                        $password_update 
                        WHERE id = ?";
        
        $stmt = $conn->prepare($update_query);
        if ($change_password) {
            $stmt->bind_param("sssi", $full_name, $email, $status, $admin_id);
        } else {
            $stmt->bind_param("sssi", $full_name, $email, $status, $admin_id);
        }
        
        if ($stmt->execute()) {
            $success = "Admin updated successfully!";
            // Refresh admin data
            $refresh_query = "SELECT * FROM admin WHERE id = ?";
            $stmt = $conn->prepare($refresh_query);
            $stmt->bind_param("i", $admin_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $admin = $result->fetch_assoc();
            $stmt->close();
        } else {
            $errors[] = "Failed to update admin. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/Logos/AU Logo.png">
    <title>Edit Admin | CMS Admin</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            min-height: 100vh;
            color: #333;
        }

        .header {
            background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
            color: white;
            padding: 20px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(0, 59, 111, 0.2);
        }

        .header h2 {
            font-size: 24px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header h2 i {
            color: #ffc107;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn.back {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        }

        .btn.back:hover {
            background: linear-gradient(135deg, #495057 0%, #343a40 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .edit-box {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 91, 163, 0.1);
        }

        .admin-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f4f8;
        }

        .admin-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            box-shadow: 0 8px 20px rgba(0, 95, 163, 0.2);
        }

        .admin-info h3 {
            color: #003b6f;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .admin-info p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #003b6f;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            height: 50px;
            padding: 0 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            color: #333;
            background: #f8fafc;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: #003b6f;
            background: white;
            box-shadow: 0 0 0 3px rgba(0, 59, 111, 0.1);
        }

        select.form-control {
            cursor: pointer;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .checkbox-group label {
            margin: 0;
            text-transform: none;
            cursor: pointer;
        }

        .password-fields {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 10px;
            border: 1px solid #e9ecef;
            display: none;
        }

        .password-fields.show {
            display: block;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .password-strength {
            margin-top: 10px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
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

        .strength-fill.weak { background: #dc3545; width: 25%; }
        .strength-fill.fair { background: #fd7e14; width: 50%; }
        .strength-fill.good { background: #ffc107; width: 75%; }
        .strength-fill.strong { background: #28a745; width: 100%; }

        .strength-text {
            font-size: 12px;
            color: #666;
            font-weight: 600;
        }

        .requirements {
            margin-top: 10px;
            padding-left: 20px;
        }

        .requirement {
            font-size: 12px;
            color: #666;
            margin-bottom: 4px;
            position: relative;
        }

        .requirement:before {
            content: '✗';
            position: absolute;
            left: -20px;
            color: #dc3545;
        }

        .requirement.met:before {
            content: '✓';
            color: #28a745;
        }

        .btn-submit {
            width: 100%;
            height: 52px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #20c997 0%, #1abc9c 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        }

        .btn-submit:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert ul {
            margin: 10px 0 0 20px;
        }

        .alert li {
            margin-bottom: 5px;
        }

        .footer-links {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 15px;
            }
            
            .edit-box {
                padding: 25px;
            }
            
            .admin-header {
                flex-direction: column;
                text-align: center;
            }
            
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
                padding: 20px;
            }
            
            .footer-links {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>

<div class="header">
    <h2><i class="fas fa-user-shield"></i> Edit Administrator</h2>
    <a href="manage_admins.php" class="btn back">
        <i class="fas fa-arrow-left"></i> Back to Admins
    </a>
</div>

<div class="container">
    <div class="edit-box">
        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?= $success ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-error">
                <strong><i class="fas fa-exclamation-triangle"></i> Please fix the following errors:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="admin-header">
            <div class="admin-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="admin-info">
                <h3><?= htmlspecialchars($admin['full_name']) ?></h3>
                <p>Admin ID: A<?= $admin['id'] ?> | Created: <?= date('M d, Y', strtotime($admin['created_at'] ?? 'now')) ?></p>
            </div>
        </div>

        <form method="POST" id="editAdminForm">
            <div class="form-group">
                <label for="full_name"><i class="fas fa-user"></i> Full Name</label>
                <input type="text" 
                       id="full_name" 
                       name="full_name" 
                       class="form-control" 
                       value="<?= htmlspecialchars($admin['full_name']) ?>" 
                       required>
            </div>

            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-control" 
                       value="<?= htmlspecialchars($admin['email']) ?>" 
                       required>
            </div>

            <div class="form-group">
                <label for="status"><i class="fas fa-power-off"></i> Account Status</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="active" <?= $admin['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $admin['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="change_password" name="change_password">
                <label for="change_password">Change Password</label>
            </div>

            <div class="password-fields" id="passwordFields">
                <div class="form-group">
                    <label for="new_password"><i class="fas fa-lock"></i> New Password</label>
                    <input type="password" 
                           id="new_password" 
                           name="new_password" 
                           class="form-control" 
                           placeholder="Leave blank to keep current">
                    
                    <div class="password-strength" id="passwordStrength">
                        <div class="strength-text" id="strengthLabel">Password Strength: None</div>
                        <div class="strength-meter">
                            <div class="strength-fill" id="strengthFill"></div>
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

                <div class="form-group">
                    <label for="confirm_password"><i class="fas fa-lock"></i> Confirm Password</label>
                    <input type="password" 
                           id="confirm_password" 
                           name="confirm_password" 
                           class="form-control" 
                           placeholder="Confirm new password">
                    <div id="passwordMatch" style="font-size: 12px; margin-top: 5px;"></div>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                <i class="fas fa-save"></i> Update Administrator
            </button>
        </form>

        <div class="footer-links">
            <a href="manage_admins.php" style="color: #005fa3; text-decoration: none;">
                <i class="fas fa-undo"></i> Cancel Changes
            </a>
            <a href="delete_admin.php?id=<?= $admin['id'] ?>" 
               onclick="return confirm('Are you sure you want to delete this admin? This action cannot be undone.')"
               style="color: #dc3545; text-decoration: none;">
                <i class="fas fa-trash"></i> Delete Admin
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const changePasswordCheckbox = document.getElementById('change_password');
    const passwordFields = document.getElementById('passwordFields');
    const newPasswordInput = document.getElementById('new_password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const passwordStrength = document.getElementById('passwordStrength');
    const strengthFill = document.getElementById('strengthFill');
    const strengthLabel = document.getElementById('strengthLabel');
    const passwordMatchDiv = document.getElementById('passwordMatch');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('editAdminForm');

    // Password requirements elements
    const requirements = {
        length: document.getElementById('reqLength'),
        lowercase: document.getElementById('reqLowercase'),
        uppercase: document.getElementById('reqUppercase'),
        number: document.getElementById('reqNumber'),
        special: document.getElementById('reqSpecial')
    };

    // Toggle password fields
    changePasswordCheckbox.addEventListener('change', function() {
        if (this.checked) {
            passwordFields.classList.add('show');
            newPasswordInput.required = true;
            confirmPasswordInput.required = true;
        } else {
            passwordFields.classList.remove('show');
            newPasswordInput.required = false;
            confirmPasswordInput.required = false;
            newPasswordInput.value = '';
            confirmPasswordInput.value = '';
            passwordMatchDiv.textContent = '';
            strengthFill.className = 'strength-fill';
            strengthLabel.textContent = 'Password Strength: None';
            
            // Reset requirement indicators
            Object.values(requirements).forEach(req => {
                req.classList.remove('met');
            });
        }
    });

    // Check password strength
    function checkPasswordStrength(password) {
        let score = 0;
        
        const checks = {
            length: password.length >= 8,
            lowercase: /[a-z]/.test(password),
            uppercase: /[A-Z]/.test(password),
            number: /\d/.test(password),
            special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
        };
        
        Object.keys(checks).forEach(key => {
            if (checks[key]) {
                requirements[key].classList.add('met');
                score++;
            } else {
                requirements[key].classList.remove('met');
            }
        });
        
        let strengthClass = '';
        let strengthText = 'None';
        
        if (password.length === 0) {
            strengthClass = '';
            strengthText = 'None';
        } else if (score === 5) {
            strengthClass = 'strong';
            strengthText = 'Strong';
        } else if (score >= 4) {
            strengthClass = 'good';
            strengthText = 'Good';
        } else if (score >= 3) {
            strengthClass = 'fair';
            strengthText = 'Fair';
        } else {
            strengthClass = 'weak';
            strengthText = 'Weak';
        }
        
        strengthFill.className = 'strength-fill ' + strengthClass;
        strengthLabel.textContent = 'Password Strength: ' + strengthText;
    }

    // Check if passwords match
    function checkPasswordMatch() {
        const password = newPasswordInput.value;
        const confirm = confirmPasswordInput.value;
        
        if (confirm.length === 0) {
            passwordMatchDiv.textContent = '';
            passwordMatchDiv.style.color = '';
            return;
        }
        
        if (password === confirm) {
            passwordMatchDiv.innerHTML = '<i class="fas fa-check-circle"></i> Passwords match';
            passwordMatchDiv.style.color = '#28a745';
        } else {
            passwordMatchDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Passwords do not match';
            passwordMatchDiv.style.color = '#dc3545';
        }
    }

    // Event listeners
    newPasswordInput.addEventListener('input', function() {
        checkPasswordStrength(this.value);
        checkPasswordMatch();
    });

    confirmPasswordInput.addEventListener('input', checkPasswordMatch);

    // Form validation
    form.addEventListener('submit', function(e) {
        if (changePasswordCheckbox.checked) {
            const password = newPasswordInput.value;
            const confirm = confirmPasswordInput.value;
            
            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long');
                newPasswordInput.focus();
                return;
            }
            
            if (password !== confirm) {
                e.preventDefault();
                alert('Passwords do not match');
                confirmPasswordInput.focus();
                return;
            }
        }
        
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        submitBtn.disabled = true;
    });
});
</script>

</body>
</html>