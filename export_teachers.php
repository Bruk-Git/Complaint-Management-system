<?php
session_start();
include "Connection.php";

/* Protect page */
if (!isset($_SESSION['admin_id'])) {
    exit("Unauthorized access");
}

/* CSV headers */
header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=teachers_list.csv");

$output = fopen("php://output", "w");

/* CSV column titles */
fputcsv($output, [
    "ID",
    "Teacher Name",
    "Email",
    "Username",
    "Department",
    "Phone",
    "Status",
    "Date Created"
]);

/* Fetch teachers */
$query = mysqli_query($conn, "
    SELECT 
        id,
        teacher_name,
        email,
        username,
        department,
        phone,
        status,
        date_created
    FROM teacher_login
    ORDER BY department, teacher_name
");

/* Write rows */
while ($row = mysqli_fetch_assoc($query)) {
    fputcsv($output, [
        $row['id'],
        $row['teacher_name'],
        $row['email'],
        $row['username'],
        $row['department'],
        $row['phone'],
        $row['status'],
        $row['date_created']
    ]);
}

fclose($output);
exit;
?>
