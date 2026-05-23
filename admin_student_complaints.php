<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$student_id = $_GET['student_id'];

$complaints = mysqli_query($conn, "
    SELECT * FROM complain
    WHERE student_id='$student_id'
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="images/Logos/AU Logo.png">
<title>Student Complaints</title>
<style>
table { width:100%; border-collapse:collapse; }
th,td { padding:10px; border:1px solid #ccc; }
th { background:#003b6f; color:white; }
</style>
</head>
<body>

<h2>Student Complaint History</h2>

<table>
<tr>
    <th>ID</th>
    <th>Subject</th>
    <th>Status</th>
    <th>Date</th>
</tr>

<?php while ($c = mysqli_fetch_assoc($complaints)) { ?>
<tr>
    <td><?= $c['id']; ?></td>
    <td><?= $c['subject']; ?></td>
    <td><?= $c['status']; ?></td>
    <td><?= $c['created_at']; ?></td>
</tr>
<?php } ?>

</table>

<br>
<a href="manage_students.php">← Back</a>

</body>
</html>
