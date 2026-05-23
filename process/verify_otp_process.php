<?php
session_start();
include "../Connection.php";

$email = trim($_POST['email']);
$otp   = trim($_POST['otp']);

$tables = ['register_table', 'teacher_login', 'admin', 'dean_login'];
$valid = false;

foreach ($tables as $table) {
    $stmt = $conn->prepare("
        SELECT id FROM $table 
        WHERE email = ? 
        AND reset_otp = ?
        AND otp_expiry >= NOW()
    ");
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_table'] = $table;
        $valid = true;
        break;
    }
}

if ($valid) {
    echo "<script>alert('Invalid or expired OTP'); window.history.back();</script>";
    exit();
}

header("Location: ../reset_password.php?email=" . urlencode($email));
exit();
