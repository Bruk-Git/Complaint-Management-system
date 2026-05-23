<?php
include "Connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name  = mysqli_real_escape_string($conn, $_POST['teacher_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $user  = mysqli_real_escape_string($conn, $_POST['username']);
    $dept  = mysqli_real_escape_string($conn, $_POST['department']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    /* CHECK DUPLICATE EMAIL OR USERNAME */
    $check = mysqli_query($conn, "
        SELECT id FROM teacher_login 
        WHERE email = '$email' OR username = '$user'
        LIMIT 1
    ");

    if (mysqli_num_rows($check) > 0) {
        echo "<script>
            alert('Email or Username already exists!');
            window.history.back();
        </script>";
        exit();
    }

    /* PASSWORD */
    $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);

    /* FILE UPLOAD */
    $file = null;
    if (!empty($_FILES['photo']['name'])) {

        if ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
            echo "<script>alert('File too large (max 2MB)'); window.history.back();</script>";
            exit();
        }

        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
            echo "<script>alert('Invalid file type. JPG or PNG only'); window.history.back();</script>";
            exit();
        }

        $dir = "uploads/teachers/";
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        $file = time() . "." . $ext;
        move_uploaded_file($_FILES['photo']['tmp_name'], $dir . $file);
    }

    /* INSERT TEACHER */
    mysqli_query($conn, "
        INSERT INTO teacher_login
        (teacher_name, email, username, department, phone, password, photo, status)
        VALUES
        ('$name', '$email', '$user', '$dept', '$phone', '$pass', '$file', 'active')
    ");

    echo "<script>
        alert('Teacher Registered Successfully');
        location='manage_teachers.php';
    </script>";
}
?>
