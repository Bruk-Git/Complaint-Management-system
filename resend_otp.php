<?php
session_start();
include "Connection.php";

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    
    // Verify email exists
    $stmt = $conn->prepare("SELECT id FROM register_table WHERE email = ? AND status != 'inactive'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Generate new OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $otp_expiry = date('Y-m-d H:i:s', strtotime('+5 minutes'));
        
        // Update database
        $update_stmt = $conn->prepare("UPDATE register_table SET reset_otp = ?, otp_expiry = ? WHERE email = ?");
        $update_stmt->bind_param("sss", $otp, $otp_expiry, $email);
        
        if ($update_stmt->execute()) {
            // Store demo OTP in session
            $_SESSION['demo_otp'] = $otp;
            
            // Send email (implement your email function)
            // sendOTPEmail($email, $user['first_name'], $otp);
            
            echo json_encode([
                'success' => true,
                'message' => 'New OTP sent successfully'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to generate new OTP'
            ]);
        }
        $update_stmt->close();
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Email not found'
        ]);
    }
    $stmt->close();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>