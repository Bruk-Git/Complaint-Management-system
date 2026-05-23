<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) exit();

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=complaints_report.csv");

$out = fopen("php://output", "w");

fputcsv($out, [
    "ID","Student Name","Student ID","Department",
    "Subject","Status","Responder","Response Date"
]);

$q = mysqli_query($conn,"SELECT * FROM complain ORDER BY created_at DESC");

while($r=mysqli_fetch_assoc($q)){
    fputcsv($out, [
        $r['complaint_id'],
        $r['student_name'],
        $r['student_id'],
        $r['department'],
        $r['academic_year'],
        $r['email'],
        $r['phone'],
        $r['subject'],
        $r['complaint_text'],
        $r['status'],
        $r['responder_name'],
        $r['response_text'],
        $r['response_date']
    ]);
}

fclose($out);
exit();
