<?php
session_start();
include "Connection.php";

/* Protect admin page */
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

/* COUNTS */
$students_count = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM register_table")
)['total'];

$teachers_count = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM teacher_login")
)['total'];

$departments_count = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM department_login")
)['total'];

$deans_count = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM dean_login")
)['total'];

$complaints_total = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM complain")
)['total'];

/* STATUS COUNTS */
$pending_count = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM complain WHERE status='Pending'")
)['total'];

$assigned_teacher_count = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM teacher_assignments")
)['total'];

$assigned_dean_count = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM dean_assignments")
)['total'];

$responded_count = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM responses WHERE status='Responded'")
)['total'];

$resolved_count = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM complaints WHERE status='Resolved'")
)['total'];


?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="images/Logos/AU Logo.png">
<title>Admin Overview</title>

<style>
body {
    font-family: Arial, sans-serif;
    background:#eef3f7;
    margin:0;
}
.container {
    padding:25px;
}
.cards {
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
    gap:15px;
}
.card {
    background:white;
    padding:18px;
    border-radius:8px;
    box-shadow:0 0 6px rgba(0,0,0,.1);
}
.card h3 {
    margin:0;
    font-size:18px;
    color:#003b6f;
}
.card p {
    font-size:26px;
    margin:10px 0 0;
    font-weight:bold;
}
.section {
    background:white;
    margin-top:30px;
    padding:20px;
    border-radius:8px;
    box-shadow:0 0 6px rgba(0,0,0,.1);
}
.section h3 {
    margin-top:0;
    color:#003b6f;
}
ul {
    padding-left:18px;
}
li {
    margin-bottom:6px;
}
</style>
</head>

<body>

<div class="container">

<!-- OVERVIEW CARDS -->
<div class="cards">
    <div class="card"><h3>Students</h3><p><?= $students_count ?></p></div>
    <div class="card"><h3>Teachers</h3><p><?= $teachers_count ?></p></div>
    <div class="card"><h3>Departments</h3><p><?= $departments_count ?></p></div>
    <div class="card"><h3>Deans</h3><p><?= $deans_count ?></p></div>
    <div class="card"><h3>Total Complaints</h3><p><?= $complaints_total ?></p></div>
</div>

<!-- COMPLAINT STATUS -->
<div class="section">
    <h3>Complaints by Status</h3>
    <ul>
        <li>Pending: <b><?= $pending_count ?></b></li>
        <li>Assigned to Teacher: <b><?= $assigned_teacher_count ?></b></li>
        <li>Assigned to Dean: <b><?= $assigned_dean_count ?></b></li>
        <li>Responded: <b><?= $responded_count ?></b></li>
        <li>Resolved: <b><?= $resolved_count ?></b></li>
    </ul>
</div>

<!-- RECENT ACTIVITY -->


</body>
</html>
