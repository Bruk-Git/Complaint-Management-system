<?php
include "Connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $department_name = $_POST["department_name"];
    $username        = $_POST["username"];
    $email           = $_POST["email"];
    $password        = $_POST["password"];

    // Check duplicates
    $check = mysqli_query($conn, "SELECT * FROM department_login WHERE email='$email' OR username='$username'");

    if (mysqli_num_rows($check) > 0) {
        echo "<script>
                alert('Username or Email already exists!');
                window.history.back();
              </script>";
        exit();
    }

    // Secure password hash
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert department officer
    $sql = "INSERT INTO department_login (department_name, username, email, password)
            VALUES ('$department_name', '$username', '$email', '$hashed_password')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Department Officer Registered Successfully!');
                window.location.href='department_login.php';
              </script>";
    } 
    else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
