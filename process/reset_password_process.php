<?php
session_start();
include "../Connection.php";

// Check if reset session exists
if (!isset($_SESSION['reset_email']) || !isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
    header("Location: ../forgot_password.php?error=invalid_session");
    exit();
}

// Get form data
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';
$email = $_SESSION['reset_email'];

// Validate passwords
$errors = [];

if (empty($password)) {
    $errors[] = "Password is required";
}

if (empty($confirm_password)) {
    $errors[] = "Confirm password is required";
}

if ($password !== $confirm_password) {
    $errors[] = "Passwords do not match";
}

// Password strength validation
if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long";
}

if (!preg_match('/[A-Z]/', $password)) {
    $errors[] = "Password must contain at least one uppercase letter";
}

if (!preg_match('/[a-z]/', $password)) {
    $errors[] = "Password must contain at least one lowercase letter";
}

if (!preg_match('/[0-9]/', $password)) {
    $errors[] = "Password must contain at least one number";
}

if (!preg_match('/[!@#$%^&*]/', $password)) {
    $errors[] = "Password must contain at least one special character (!@#$%^&*)";
}

// If there are errors, redirect back
if (!empty($errors)) {
    $_SESSION['reset_errors'] = $errors;
    header("Location: ../reset_password.php");
    exit();
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Update ONLY the password column (and clear OTP fields)
$stmt = $conn->prepare("
    UPDATE register_table
    SET password = ?,
        reset_otp = NULL,
        otp_expiry = NULL
    WHERE email = ?
");

if (!$stmt) {
    // Database error
    $_SESSION['reset_errors'] = ["Database error: " . $conn->error];
    header("Location: ../reset_password.php");
    exit();
}

$stmt->bind_param("ss", $hashed_password, $email);

if ($stmt->execute()) {
    // Check if any row was actually updated
    if ($stmt->affected_rows > 0) {
        // Password updated successfully
        
        // Log the password change (optional)
        $log_stmt = $conn->prepare("
            INSERT INTO audit_logs 
            (user_id, action, table_name, record_id, details) 
            SELECT id, 'PASSWORD_RESET', 'register_table', id, 'Password reset via OTP system'
            FROM register_table 
            WHERE email = ?
        ");
        
        if ($log_stmt) {
            $log_stmt->bind_param("s", $email);
            $log_stmt->execute();
            $log_stmt->close();
        }
        
        // Clear all reset session data
        unset($_SESSION['reset_email']);
        unset($_SESSION['otp_verified']);
        unset($_SESSION['otp_attempts']);
        unset($_SESSION['demo_otp']);
        
        // Redirect to login with success message
        header("Location: ../login.php?reset=success");
        exit();
    } else {
        // No rows affected - email not found
        $_SESSION['reset_errors'] = ["Email not found or password unchanged"];
        header("Location: ../forgot_password.php");
        exit();
    }
} else {
    // Update failed
    $_SESSION['reset_errors'] = ["Failed to update password: " . $stmt->error];
    header("Location: ../reset_password.php");
    exit();
}

$stmt->close();
?>