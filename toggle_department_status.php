<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    die("Unauthorized");
}

$id = intval($_GET['id']);

$q = mysqli_query($conn, "SELECT status FROM department_login WHERE id='$id'");
$row = mysqli_fetch_assoc($q);

$newStatus = ($row['status'] === 'active') ? 'inactive' : 'active';

mysqli_query($conn, "UPDATE department_login SET status='$newStatus' WHERE id='$id'");

header("Location: manage_departments.php");
exit();
?>
