<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_students.php");
    exit();
}

$id = intval($_GET['id']);

// Get current status
$q = $conn->prepare("SELECT status FROM register_table WHERE id = ?");
$q->bind_param("i", $id);
$q->execute();
$result = $q->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    $newStatus = ($row['status'] === 'active') ? 'inactive' : 'active';

    $update = $conn->prepare(
        "UPDATE register_table SET status = ? WHERE id = ?"
    );
    $update->bind_param("si", $newStatus, $id);
    $update->execute();
}

header("Location: manage_students.php");
exit();
?>
