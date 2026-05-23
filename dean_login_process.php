<?php
session_start();
include "Connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate inputs
    if (empty($email) || empty($password)) {
        echo "<script>
            alert('Please fill in all fields');
            window.history.back();
        </script>";
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
            alert('Invalid email format');
            window.history.back();
        </script>";
        exit();
    }

    // Check dean email using prepared statement
    $sql = "SELECT * FROM dean_login WHERE email = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo "<script>
            alert('Database error. Please try again.');
            window.history.back();
        </script>";
        exit();
    }
    
    $stmt->bind_param("s", $email);
    
    if (!$stmt->execute()) {
        echo "<script>
            alert('Database error. Please try again.');
            window.history.back();
        </script>";
        exit();
    }
    
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $dean = $result->fetch_assoc();

        // 🔒 CHECK ACTIVATION STATUS
        if (!isset($dean['status']) || $dean['status'] !== 'active') {
            echo "<script>
                alert('Your account is deactivated. Please contact the administrator.');
                window.history.back();
            </script>";
            exit();
        }

        // Verify password
        if (password_verify($password, $dean['password'])) {

            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);

            // Set all required session variables
            $_SESSION['dean_id']         = $dean['id'];
            $_SESSION['dean_name']       = $dean['dean_name'];
            $_SESSION['dean_email']      = $dean['email'];
            $_SESSION['role']            = 'dean';
            
            // 🔒 SECURITY ENHANCEMENTS
            $_SESSION['authenticated']   = true;
            $_SESSION['last_activity']   = time();
            $_SESSION['user_agent']      = $_SERVER['HTTP_USER_AGENT'];
            
            // Set session expiration time (30 minutes)
            $_SESSION['expire_time']     = time() + (30 * 60);

            $stmt->close();

            // Redirect to dashboard
            header("Location: dean_dashboard.php");
            exit();

        } else {
            echo "<script>
                alert('Incorrect password');
                window.history.back();
            </script>";
            exit();
        }

    } else {
        echo "<script>
            alert('Dean email not found');
            window.history.back();
        </script>";
        exit();
    }
} else {
    // Invalid request method
    header("Location: dean_login.php");
    exit();
}
?>