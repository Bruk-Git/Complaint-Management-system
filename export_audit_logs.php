<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    exit();
}

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=audit_logs.csv");

$out = fopen("php://output", "w");

/* Excel headers */
fputcsv($out, [
    "Reference ID",
    "Actor",
    "Role",
    "Action",
    "Entity",
    "Date"
]);

$query = mysqli_query($conn, "

    SELECT
        complaint_id AS ref_id,
        student_name AS actor,
        'Student' AS role,
        'Submitted complaint' AS action,
        'Complaint' AS entity,
        created_at AS log_date
    FROM complain

    UNION ALL

    SELECT
        complaint_id,
        teacher_name,
        'Teacher',
        'Assigned complaint',
        'Complaint',
        assigned_date
    FROM complain
    WHERE teacher_name IS NOT NULL

    UNION ALL

    SELECT
        complaint_id,
        dean_name,
        'Dean',
        'Assigned to Dean',
        'Complaint',
        assigned_date
    FROM complain
    WHERE dean_name IS NOT NULL

    UNION ALL

    SELECT
        complaint_id,
        responder_name,
        'Responder',
        CONCAT('Responded (', status, ')'),
        'Complaint',
        response_date
    FROM complain
    WHERE response_text IS NOT NULL

    ORDER BY log_date DESC
");

while ($row = mysqli_fetch_assoc($query)) {
    fputcsv($out, [
        $row['ref_id'],
        $row['actor'],
        $row['role'],
        $row['action'],
        $row['entity'],
        $row['log_date']
            ? date("Y-m-d H:i:s", strtotime($row['log_date']))
            : ''
    ]);
}

fclose($out);
exit();
