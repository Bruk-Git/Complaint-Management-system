<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$dean_id = $_GET['id'];

$complaints = mysqli_query($conn, "
    SELECT 
        complaint_id,
        student_name,
        student_id,
        department,
        academic_year,
        email,
        phone,
        subject,
        complaint_text,
        file_attachment,
        file_original_name,
        file_size,
        file_type,
        status,
        teacher_id,
        teacher_name,
        assigned_by,
        assigned_date,
        dean_name,
        responder_id,
        responder_name,
        response_file,
        response_date,
        response_text,
        created_at,
        updated_at,
        program,
        is_anonymous
    FROM complain
    WHERE status = 'Assigned to Dean'
    ORDER BY created_at DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="images/Logos/AU Logo.png">
<title>Dean Complaint History</title>
<style>
table { width:100%; border-collapse:collapse; }
th,td { padding:10px; border:1px solid #ccc; }
th { background:#003b6f; color:white; }
</style>
</head>
<body>

<h2>Dean Complaint History</h2>

<table>
<tr>
    <th>ID</th>
    <th>Student</th>
    <th>Subject</th>
    <th>Status</th>
    <th>Assigned Date</th>
    <th>Response</th>
</tr>

<?php while ($c = mysqli_fetch_assoc($complaints)) { ?>
<tr>
    <td><?= $c['student_id']; ?></td>
    <td><?= $c['student_name']; ?></td>
    <td><?= $c['subject']; ?></td>
    <td><?= $c['status']; ?></td>
    <td><?= $c['assigned_date']; ?></td>
    <td><?= $c['response_text'] ? 'Responded' : 'Pending'; ?></td>
</tr>
<?php } ?>

</table>

<br>
<a href="manage_deans.php">← Back</a>

</body>
</html>
