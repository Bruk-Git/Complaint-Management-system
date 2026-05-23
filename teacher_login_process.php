<?php
session_start();
include "Connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM teacher_login WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $teacher = $result->fetch_assoc();

        // Check if account is active
        if ($teacher['status'] !== 'active') {
            echo "<script>
                alert('Your account has been deactivated. Please contact the administrator.');
                window.location.href='teacher_login.php';
            </script>";
            exit();
        }

        // Verify password
        if (password_verify($password, $teacher['password'])) {

            $_SESSION['teacher_id'] = $teacher['id'];
            $_SESSION['teacher_name'] = $teacher['teacher_name'];
            $_SESSION['teacher_department'] = $teacher['department'];

            echo "<script>
                alert('Login Successful!');
                window.location.href = 'teacher_dashboard.php';
            </script>";
            exit();

        } else {
            echo "<script>
                alert('Incorrect password!');
                window.location.href='teacher_login.php';
            </script>";
            exit();
        }

    } else {
        echo "<script>
            alert('Email not found in teacher accounts!');
            window.location.href='teacher_login.php';
        </script>";
        exit();
    }
}
?>
