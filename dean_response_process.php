<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['dean_id'])) {
    header("Location: dean_login.php");
    exit();
}

$complaint_id  = intval($_POST['complaint_id']);
$response_text = mysqli_real_escape_string($conn, $_POST['response_text']);

$dean_id   = $_SESSION['dean_id'];
$dean_name = $_SESSION['dean_name'];

$response_file = NULL;

/* FILE VALIDATION */
if (!empty($_FILES['response_file']['name'])) {

    // Size check (2MB)
    if ($_FILES['response_file']['size'] > 2097152) {
        echo "<script>
            alert('File size must be less than 2MB.');
            window.history.back();
        </script>";
        exit();
    }

    // Type check
    $allowed = ['pdf','jpg','jpeg','png'];
    $ext = strtolower(pathinfo($_FILES['response_file']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        echo "<script>
            alert('Invalid file type. Only PDF, JPG, JPEG, PNG allowed.');
            window.history.back();
        </script>";
        exit();
    }

    // Upload folder
    $dir = "uploads/responses/";
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    // Safe file name
    $response_file = time() . "_" . basename($_FILES['response_file']['name']);

    if (!move_uploaded_file($_FILES['response_file']['tmp_name'], $dir . $response_file)) {
        echo "<script>
            alert('Failed to upload response file.');
            window.history.back();
        </script>";
        exit();
    }
}

/* UPDATE COMPLAINT */
$update = mysqli_query($conn, "
    UPDATE complain SET
        response_text   = '$response_text',
        response_file   = '$response_file',
        responder_id    = '$dean_id',
        responder_name  = '$dean_name',
        status          = 'Resolved',
        response_date   = NOW()
    WHERE complaint_id = '$complaint_id'
");

if ($update) {
    echo "<script>
        alert('Response sent successfully.');
        window.location='dean_dashboard.php';
    </script>";
} else {
    echo "<script>
        alert('Failed to send response. Please try again.');
        window.history.back();
    </script>";
}
?>
