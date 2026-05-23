<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_deans.php");
    exit();
}

$id = intval($_GET['id']);

// Get current status
$stmt = $conn->prepare("SELECT status FROM dean_login WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $newStatus = ($row['status'] === 'active') ? 'inactive' : 'active';

    $update = $conn->prepare(
        "UPDATE dean_login SET status = ? WHERE id = ?"
    );
    $update->bind_param("si", $newStatus, $id);
    $update->execute();
}

header("Location: manage_deans.php");
exit();
?>
