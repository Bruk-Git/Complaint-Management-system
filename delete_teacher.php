<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM teacher_login WHERE id='$id'");

echo "<script>alert('Teacher deleted');window.location='manage_teachers.php';</script>";
