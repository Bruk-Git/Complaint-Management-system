<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    die("Unauthorized");
}

$id = intval($_GET['id']);

$q = $conn->prepare("SELECT status FROM teacher_login WHERE id=?");
$q->bind_param("i", $id);
$q->execute();
$res = $q->get_result();

if ($res->num_rows != 1) {
    header("Location: manage_teachers.php");
    exit();
}

$row = $res->fetch_assoc();
$newStatus = ($row['status'] === 'active') ? 'inactive' : 'active';

$u = $conn->prepare("UPDATE teacher_login SET status=? WHERE id=?");
$u->bind_param("si", $newStatus, $id);
$u->execute();

header("Location: manage_teachers.php");
exit();
?>
