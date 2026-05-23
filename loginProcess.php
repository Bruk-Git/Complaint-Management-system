<?php
session_start();
include "Connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email    = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT * FROM register_table WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        // Check if account is active
        if ($user['status'] !== 'active') {
            echo "<script>
                alert('Your account has been deactivated. Please contact administration.');
                window.location='login.php';
            </script>";
            exit();
        }

        // Verify password
        if (password_verify($password, $user["password"])) {

            $_SESSION["user_id"]    = $user["id"];
            $_SESSION["full_name"] = $user["first_name"] . " " . $user["last_name"];
            $_SESSION["email"]     = $user["email"];
            $_SESSION["student_id"] = $user["student_id"];
            $_SESSION["department"] = $user["department"];
            $_SESSION["program"]    = $user["program"];
            $_SESSION["study_mode"] = $user["study_mode"];

            echo "<script>
                alert('Login successful!');
                window.location='student_dashboard.php';
            </script>";
            exit();

        } else {
            echo "<script>
                alert('Incorrect password.');
                window.history.back();
            </script>";
            exit();
        }

    } else {
        echo "<script>
            alert('Email not found.');
            window.history.back();
        </script>";
        exit();
    }
}
?>
