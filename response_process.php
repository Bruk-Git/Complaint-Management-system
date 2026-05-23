<?php
session_start();
include "Connection.php";

$complaint_id = $_POST['complaint_id'];
$response_text = $_POST['response_text'];

// Identify role based on login
if (isset($_SESSION['teacher_id'])) {
    $role = "teacher";
    $name = $_SESSION['teacher_name'];
    $new_status = "Teacher Responded";
} 
elseif (isset($_SESSION['department_id'])) {
    $role = "department";
    $name = $_SESSION['username'];
    $new_status = "Reviewed by Department";
}
elseif (isset($_SESSION['dean_id'])) {
    $role = "dean";
    $name = $_SESSION['dean_name'];
    $new_status = "Dean Responded";
} else {
    die("Unauthorized");
}

// Insert into responses table
$sql = "INSERT INTO responses (complaint_id, responder_role, responder_name, response_text, response_date, status)
        VALUES ('$complaint_id', '$role', '$name', '$response_text', NOW(), '$new_status')";
mysqli_query($conn, $sql);

// Update complaints table
mysqli_query($conn, "UPDATE complaints SET status='$new_status' WHERE id='$complaint_id'");

echo "<script>alert('Response sent successfully!'); window.history.back();</script>";
?>
