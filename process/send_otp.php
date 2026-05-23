<?php
session_start();
include "../Connection.php";

$email = trim($_POST['email']);

// Generate 6-digit OTP
$otp = rand(100000, 999999);
$expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

// Check user in all tables
$tables = ['register_table', 'teacher_login', 'admin_login', 'dean_login'];
$found = false;

foreach ($tables as $table) {
    $stmt = $conn->prepare("SELECT id FROM $table WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $stmt = $conn->prepare("
            UPDATE $table 
            SET reset_otp = ?, otp_expiry = ?
            WHERE email = ?
        ");
        $stmt->bind_param("sss", $otp, $expiry, $email);
        $stmt->execute();

        $found = true;
        break;
    }
}

if (!$found) {
    echo "<script>alert('Email not found'); window.history.back();</script>";
    exit();
}

/* TEMP: Show OTP (replace with email later) */
echo "<script>
    alert('Your OTP is: $otp');
    window.location.href='../verify_otp.php';
</script>";
exit();
