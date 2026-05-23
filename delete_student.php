<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM register_table WHERE id='$id'");

echo "<script>alert('Student deleted successfully'); window.location='manage_students.php';</script>";
