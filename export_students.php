<?php
session_start();
include "Connection.php";

/* Protect page */
if (!isset($_SESSION['admin_id'])) {
    exit("Unauthorized access");
}

/* CSV headers */
header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=students_list.csv");

$output = fopen("php://output", "w");

/* CSV column titles */
fputcsv($output, [
    "Student ID",
    "First Name",
    "Last Name",
    "Gender",
    "Year Level",
    "Department",
    "Program",
    "Study Mode",
    "Email",
    "Phone"
]);

/* Fetch students */
$query = mysqli_query($conn, "
    SELECT 
        student_id,
        first_name,
        last_name,
        gender,
        year_level,
        department,
        program,
        study_mode,
        email,
        mobile_no
    FROM register_table
    ORDER BY department, program, study_mode, student_id
");

while ($row = mysqli_fetch_assoc($query)) {
    fputcsv($output, [
        $row['student_id'],
        $row['first_name'],
        $row['last_name'],
        $row['gender'],
        $row['year_level'],
        $row['department'],
        $row['program'],
        $row['study_mode'],
        $row['email'],
        $row['mobile_no']
    ]);
}

fclose($output);
exit;
?>
