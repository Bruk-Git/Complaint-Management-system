<?php
session_start();
include "Connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // Basic empty check
    if ($email == "" || $password == "") {
        echo "<script>
            alert('Please fill in all fields.');
            window.history.back();
        </script>";
        exit();
    }

    // Check admin email
    $query  = "SELECT * FROM admin WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {

        $admin = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $admin['password'])) {

            // Create session
            $_SESSION['admin_id']   = $admin['id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            $_SESSION['admin_email']= $admin['email'];

            echo "<script>
                alert('Admin login successful!');
                window.location.href = 'admin_dashboard.php';
            </script>";
            exit();

        } else {
            echo "<script>
                alert('Incorrect password!');
                window.history.back();
            </script>";
            exit();
        }

    } else {
        echo "<script>
            alert('Admin email not found!');
            window.history.back();
        </script>";
        exit();
    }
}
?>
