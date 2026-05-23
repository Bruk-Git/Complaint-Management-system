<?php
session_start();
include "Connection.php";


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name     = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    // Password strength check
    if (strlen($password) < 8 || 
        !preg_match('/[A-Za-z]/', $password) || 
        !preg_match('/[0-9]/', $password)) {

        echo "<script>
            alert('Password must be at least 8 characters and include letters and numbers.');
            window.history.back();
        </script>";
        exit();
    }

    // Confirm password check
    if ($password !== $confirm) {
        echo "<script>
            alert('Passwords do not match.');
            window.history.back();
        </script>";
        exit();
    }

    // Check email exists
    $check = mysqli_query($conn, "SELECT id FROM admin WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>
            alert('Email already registered.');
            window.history.back();
        </script>";
        exit();
    }

    // Hash password
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    // Insert admin
    $insert = mysqli_query($conn, "
        INSERT INTO admin (full_name, email, password)
        VALUES ('$name', '$email', '$hashed')
    ");

    if ($insert) {
        echo "<script>
            alert('Admin registered successfully.');
            window.location.href='admin_login.php';
        </script>";
    } else {
        echo "<script>
            alert('Registration failed.');
            window.history.back();
        </script>";
    }
}
?>
