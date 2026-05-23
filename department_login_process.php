<?php
session_start();
include "Connection.php"; // DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Check if email exists
    $sql = "SELECT * FROM department_login WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // If email found
    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        // Check if account is active
        if ($user['status'] !== 'active') {
            echo "<script>
                alert('Your department account is currently deactivated. Please contact the administrator.');
                window.history.back();
            </script>";
            exit();
        }

        // Verify password
        if (password_verify($password, $user["password"])) {

            // Create session
            $_SESSION["department_id"]   = $user["id"];
            $_SESSION["department_name"] = $user["department_name"];
            $_SESSION["username"]        = $user["username"];
            $_SESSION["email"]           = $user["email"];

            echo "<script>
                alert('Login Successful!');
                window.location.href = 'department_dashboard.php';
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
            alert('Email not found!');
            window.history.back();
        </script>";
        exit();
    }
}
?>
