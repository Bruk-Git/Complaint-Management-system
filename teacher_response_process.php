<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request");
}

$complaint_id  = intval($_POST['complaint_id']);
$response_text = mysqli_real_escape_string($conn, $_POST['response_text']);

$teacher_id   = $_SESSION['teacher_id'];
$teacher_name = $_SESSION['teacher_name'];

$response_file = NULL;

/* ---------- FILE UPLOAD (SAFE) ---------- */
if (!empty($_FILES['response_file']['name'])) {

    $allowed = ['pdf','jpg','jpeg','png'];
    $maxSize = 2 * 1024 * 1024; // 2MB

    $ext = strtolower(pathinfo($_FILES['response_file']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        echo "<script>alert('Invalid file type'); history.back();</script>";
        exit();
    }

    if ($_FILES['response_file']['size'] > $maxSize) {
        echo "<script>alert('File must be less than 2MB'); window.location='teacher_response.php';</script>";
        exit();
    }

    $dir = "uploads/responses/";
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $response_file = time() . "_" . basename($_FILES['response_file']['name']);
    move_uploaded_file($_FILES['response_file']['tmp_name'], $dir . $response_file);
}

/* ---------- UPDATE COMPLAINT ---------- */
$update = mysqli_query($conn, "
    UPDATE complain SET
        response_text  = '$response_text',
        response_file  = '$response_file',
        responder_id   = '$teacher_id',
        responder_name = '$teacher_name',
        response_date  = NOW(),
        status         = 'Resolved'
    WHERE complaint_id = '$complaint_id'
");

if ($update) {
    echo "<script>
        alert('Response submitted successfully');
        window.location='teacher_dashboard.php';
    </script>";
} else {
    echo "<script>
        alert('Failed to submit response');
        history.back();
    </script>";
}
?>
