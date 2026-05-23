<?php
session_start();
include "Connection.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: register.php");
    exit();
}

// Collect data safely
$email       = mysqli_real_escape_string($conn, $_POST['email']);
$password    = $_POST['password'];
$first_name  = mysqli_real_escape_string($conn, $_POST['first_name']);
$last_name   = mysqli_real_escape_string($conn, $_POST['last_name']);
$gender      = $_POST['gender'];
$student_id  = mysqli_real_escape_string($conn, $_POST['student_id']);

$program     = $_POST['program'];
$study_mode  = $_POST['study_mode'];
$department  = mysqli_real_escape_string($conn, $_POST['department']);
$year_level  = intval($_POST['year']);

$address     = mysqli_real_escape_string($conn, $_POST['address']);
$mobile_no   = mysqli_real_escape_string($conn, $_POST['mobile_no']);

// ---------------- VALIDATION ----------------

// Email check
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("<script>alert('Invalid email');history.back();</script>");
}

// Password strength (same rule as JS)
if (
    strlen($password) < 8 ||
    !preg_match('/[A-Z]/', $password) ||
    !preg_match('/[a-z]/', $password) ||
    !preg_match('/[0-9]/', $password) ||
    !preg_match('/[\W]/', $password)
) {
    die("<script>alert('Weak password');history.back();</script>");
}

// Year rules (IMPORTANT)
if ($program === 'DEGREE') {
    if ($study_mode === 'REGULAR' && $year_level > 4) {
        die("<script>alert('Degree Regular max 4 years');history.back();</script>");
    }
    if (in_array($study_mode, ['EXTENSION','DISTANCE']) && $year_level > 5) {
        die("<script>alert('Degree Extension/Distance max 5 years');history.back();</script>");
    }
}

if ($program === 'MASTERS' && $year_level > 2) {
    die("<script>alert('Masters max 2 years');history.back();</script>");
}

if ($program === 'TVET' && $year_level > 4) {
    die("<script>alert('TVET max 4 years');history.back();</script>");
}

// Check duplicate email or student ID
$check = mysqli_query($conn, "
    SELECT id FROM register_table
    WHERE email='$email' OR student_id='$student_id'
");

if (mysqli_num_rows($check) > 0) {
    die("<script>alert('Email or Student ID already exists');history.back();</script>");
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// ---------------- INSERT ----------------
$insert = mysqli_query($conn, "
    INSERT INTO register_table
    (email,password,first_name,last_name,gender,student_id,
     program,study_mode,department,year_level,address,mobile_no)
    VALUES
    ('$email','$hashed_password','$first_name','$last_name','$gender','$student_id',
     '$program','$study_mode','$department','$year_level','$address','$mobile_no')
");

if ($insert) {
    echo "<script>
        alert('Registration successful');
        window.location='manage_students.php';
    </script>";
} else {
    echo "<script>
        alert('Registration failed');
        history.back();
    </script>";
}
?>
