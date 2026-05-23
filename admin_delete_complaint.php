<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$id = intval($_GET['id']);

/* Soft delete option (recommended)
mysqli_query($conn,"
    UPDATE complain SET status='Resolved'
    WHERE complaint_id='$id'
");
*/

// Hard delete
mysqli_query($conn,"DELETE FROM complain WHERE complaint_id='$id'");

header("Location: admin_view_complaints.php");
exit();
