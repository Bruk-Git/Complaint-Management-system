<?php
// department_register_process.php
session_start();
require_once 'connection.php';

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Sanitize and validate inputs
    $department_name = sanitize_input($_POST['department_name'] ?? '', $conn);
    $department_code = strtoupper(sanitize_input($_POST['department_code'] ?? '', $conn));
    $faculty = sanitize_input($_POST['faculty'] ?? '', $conn);
    $description = sanitize_input($_POST['description'] ?? '', $conn);
    
    $contact_person = sanitize_input($_POST['contact_person'] ?? '', $conn);
    $contact_position = sanitize_input($_POST['contact_position'] ?? '', $conn);
    $contact_email = sanitize_input($_POST['contact_email'] ?? '', $conn);
    $contact_phone = sanitize_input($_POST['contact_phone'] ?? '', $conn);
    
    $username = sanitize_input($_POST['username'] ?? '', $conn);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    $terms = isset($_POST['terms']) ? true : false;
    
    // Validate required fields
    $errors = [];
    
    if (empty($department_name)) $errors[] = "Department name is required";
    if (empty($department_code)) $errors[] = "Department code is required";
    if (empty($faculty)) $errors[] = "Faculty is required";
    if (empty($contact_person)) $errors[] = "Contact person is required";
    if (empty($contact_email) || !filter_var($contact_email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid contact email is required";
    if (empty($contact_phone)) $errors[] = "Contact phone is required";
    if (empty($username)) $errors[] = "Username is required";
    if (empty($password)) $errors[] = "Password is required";
    if ($password !== $confirm_password) $errors[] = "Passwords do not match";
    if (!$terms) $errors[] = "You must agree to the terms and conditions";
    
    // Additional validation
    if (strlen($password) < 8) $errors[] = "Password must be at least 8 characters";
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) $errors[] = "Username can only contain letters, numbers, and underscores";
    
    // Check if username already exists in department_users
    $check_stmt = $conn->prepare("SELECT username FROM department_users WHERE username = ?");
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_stmt->store_result();
    if ($check_stmt->num_rows > 0) {
        $errors[] = "Username is already taken";
    }
    $check_stmt->close();
    
    // Check if department code already exists
    $check_stmt = $conn->prepare("SELECT department_code FROM department_registration_requests WHERE department_code = ? AND status = 'pending'");
    $check_stmt->bind_param("s", $department_code);
    $check_stmt->execute();
    $check_stmt->store_result();
    if ($check_stmt->num_rows > 0) {
        $errors[] = "A registration request for this department code is already pending";
    }
    $check_stmt->close();
    
    // Check if department already exists in departments table
    $check_stmt = $conn->prepare("SELECT department_code FROM departments WHERE department_code = ?");
    $check_stmt->bind_param("s", $department_code);
    $check_stmt->execute();
    $check_stmt->store_result();
    if ($check_stmt->num_rows > 0) {
        $errors[] = "Department with this code already exists";
    }
    $check_stmt->close();
    
    // If there are errors, redirect back with error messages
    if (!empty($errors)) {
        $error_message = urlencode(implode(", ", $errors));
        header("Location: department_register.php?message=$error_message&type=error");
        exit();
    }
    
    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Begin transaction
    $conn->begin_transaction();
    
    try {
        // Insert into registration requests table
        $stmt = $conn->prepare("INSERT INTO department_registration_requests 
            (department_name, department_code, faculty, contact_person, contact_email, 
             contact_phone, username, password_hash, staff_name, staff_email, staff_position, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
        
        // Use contact person as staff name for now
        $staff_name = $contact_person;
        $staff_email = $contact_email;
        $staff_position = $contact_position;
        
        $stmt->bind_param("sssssssssss", 
            $department_name, $department_code, $faculty, 
            $contact_person, $contact_email, $contact_phone,
            $username, $password_hash, $staff_name, $staff_email, $staff_position);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to save registration: " . $stmt->error);
        }
        
        $request_id = $stmt->insert_id;
        $stmt->close();
        
        // Send email notification to admin (simulated)
        sendAdminNotification($department_name, $department_code, $contact_person, $contact_email);
        
        // Send confirmation email to department contact
        sendConfirmationEmail($contact_email, $department_name, $request_id);
        
        // Log the registration request
        logActivity($conn, null, "Department registration request submitted: $department_name ($department_code)", "REGISTRATION_REQUEST");
        
        // Commit transaction
        $conn->commit();
        
        // Redirect with success message
        $success_message = urlencode("Registration request submitted successfully! Your request ID is #$request_id. You will receive an email once approved.");
        header("Location: department_register.php?message=$success_message&type=success");
        exit();
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        
        // Log error
        error_log("Registration error: " . $e->getMessage());
        
        // Redirect with error message
        $error_message = urlencode("Registration failed. Please try again. Error: " . $e->getMessage());
        header("Location: department_register.php?message=$error_message&type=error");
        exit();
    }
    
} else {
    // If not POST request, redirect to registration page
    header("Location: department_register.php");
    exit();
}

// Function to send admin notification (simulated - implement actual email)
function sendAdminNotification($dept_name, $dept_code, $contact_name, $contact_email) {
    $to = "admin@admas.edu"; // Admin email
    $subject = "New Department Registration Request - Admas University CMS";
    $message = "
    <html>
    <head>
        <title>New Department Registration</title>
    </head>
    <body>
        <h2>New Department Registration Request</h2>
        <p>A new department has requested access to the Complaint Management System:</p>
        <ul>
            <li><strong>Department:</strong> $dept_name ($dept_code)</li>
            <li><strong>Contact Person:</strong> $contact_name</li>
            <li><strong>Contact Email:</strong> $contact_email</li>
            <li><strong>Request Date:</strong> " . date('Y-m-d H:i:s') . "</li>
        </ul>
        <p>Please review and approve the request in the admin panel.</p>
        <p><a href='http://localhost/complaint_management/admin/approve_requests.php'>Review Requests</a></p>
    </body>
    </html>
    ";
    
    // Headers for HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: noreply@admas.edu" . "\r\n";
    
    // In production, use PHPMailer or similar
    // mail($to, $subject, $message, $headers);
    
    // For now, just log it
    error_log("Admin notification email would be sent to: $to");
}

// Function to send confirmation email
function sendConfirmationEmail($to_email, $dept_name, $request_id) {
    $subject = "Registration Request Received - Admas University CMS";
    $message = "
    <html>
    <head>
        <title>Registration Confirmation</title>
    </head>
    <body>
        <h2>Registration Request Received</h2>
        <p>Dear Department Representative,</p>
        <p>Thank you for registering <strong>$dept_name</strong> for the Admas University Complaint Management System.</p>
        <p><strong>Your Request ID:</strong> #$request_id</p>
        <p><strong>Status:</strong> Pending Approval</p>
        <p>Your registration request has been submitted successfully and is currently under review by the system administrator. 
        You will receive another email once your request is approved.</p>
        <p><strong>Note:</strong> Approval typically takes 1-2 business days.</p>
        <hr>
        <p>If you have any questions, please contact: support@admas.edu</p>
        <p>Best regards,<br>Admas University IT Department</p>
    </body>
    </html>
    ";
    
    // Headers for HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: noreply@admas.edu" . "\r\n";
    
    // In production, use PHPMailer or similar
    // mail($to_email, $subject, $message, $headers);
    
    error_log("Confirmation email would be sent to: $to_email");
}

// Function to log activities
function logActivity($conn, $department_id, $description, $activity_type) {
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    
    $stmt = $conn->prepare("INSERT INTO activity_logs (department_id, activity_type, description, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $department_id, $activity_type, $description, $ip_address, $user_agent);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
?>