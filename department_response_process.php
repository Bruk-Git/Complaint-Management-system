<?php
session_start();
include "Connection.php";

/* ===== AUTH CHECK ===== */
if (!isset($_SESSION['department_name'])) {
    header("Location: department_login.php");
    exit();
}

$department_name = $_SESSION['department_name'];

/* ===== VALIDATE INPUT ===== */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request");
}

if (!isset($_POST['complaint_id'], $_POST['response_text'])) {
    die("Missing data");
}

$complaint_id  = intval($_POST['complaint_id']);
$response_text = trim($_POST['response_text']);

if ($response_text === '') {
    echo "<script>alert('Response cannot be empty'); history.back();</script>";
    exit();
}

/* ===== FILE UPLOAD SETTINGS ===== */
$response_file = NULL;
$upload_dir = "response_files/";
$allowed_types = ['pdf', 'jpg', 'jpeg', 'png'];
$max_size = 2 * 1024 * 1024; // 2MB

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

/* ===== HANDLE FILE UPLOAD ===== */
if (!empty($_FILES['response_file']['name'])) {

    $file_name = $_FILES['response_file']['name'];
    $file_tmp  = $_FILES['response_file']['tmp_name'];
    $file_size = $_FILES['response_file']['size'];

    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed_types)) {
        echo "<script>alert('Only PDF, JPG, JPEG, PNG allowed'); history.back();</script>";
        exit();
    }

    if ($file_size > $max_size) {
        echo "<script>alert('File size must be less than 2MB'); history.back();</script>";
        exit();
    }

    $new_name = "dept_" . time() . "_" . rand(1000,9999) . "." . $ext;
    $target   = $upload_dir . $new_name;

    if (!move_uploaded_file($file_tmp, $target)) {
        echo "<script>alert('File upload failed'); history.back();</script>";
        exit();
    }

    $response_file = $new_name;
}

/* ===== UPDATE COMPLAINT ===== */
$sql = "
UPDATE complain SET
    response_text = ?,
    response_file = ?,
    responder_name = ?,
    responder_id = ?,
    response_date = NOW(),
    status = 'Responded'
WHERE complaint_id = ?
";

$stmt = mysqli_prepare($conn, $sql);

$responder_id = "DEPT";
mysqli_stmt_bind_param(
    $stmt,
    "ssssi",
    $response_text,
    $response_file,
    $department_name,
    $responder_id,
    $complaint_id
);

if (mysqli_stmt_execute($stmt)) {
    echo "<script>
        alert('Response sent successfully');
        window.location='department_dashboard.php';
    </script>";
} else {
    echo "<script>alert('Database error'); history.back();</script>";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
