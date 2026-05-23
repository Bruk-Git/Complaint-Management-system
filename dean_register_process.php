<?php
include "Connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $dean_name = trim($_POST['dean_name']);
    $email     = trim($_POST['email']);
    $password  = $_POST['password'];
    $confirm   = $_POST['confirm_password'];

    /* BASIC VALIDATION */
    if (empty($dean_name) || empty($email) || empty($password)) {
        echo "<script>
            alert('All fields are required');
            window.history.back();
        </script>";
        exit();
    }

    /* PASSWORD MATCH CHECK */
    if ($password !== $confirm) {
        echo "<script>
            alert('Passwords do not match');
            window.history.back();
        </script>";
        exit();
    }

    /* CHECK DUPLICATE EMAIL */
    $check = $conn->prepare(
        "SELECT id FROM dean_login WHERE email = ? LIMIT 1"
    );
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>
            alert('Email already exists');
            window.history.back();
        </script>";
        exit();
    }

    /* HASH PASSWORD */
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    /* INSERT DEAN */
    $stmt = $conn->prepare(
        "INSERT INTO dean_login (dean_name, email, password, status)
         VALUES (?, ?, ?, 'active')"
    );
    $stmt->bind_param("sss", $dean_name, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>
            alert('Dean registered successfully');
            window.location.href = 'manage_deans.php';
        </script>";
        exit();
    } else {
        echo "<script>
            alert('Registration failed');
            window.history.back();
        </script>";
        exit();
    }
}
?>
