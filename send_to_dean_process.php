<?php
session_start();
include "Connection.php";

/* Department login check */
if (!isset($_SESSION["department_name"])) {
    header("Location: department_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Invalid complaint ID");
}

$complaint_id = intval($_GET['id']);
$department   = $_SESSION['department_name'];
$assigned_by  = $_SESSION['username'];

/* Fetch complaint */
$q = mysqli_query($conn,"
    SELECT * FROM complain
    WHERE complaint_id='$complaint_id'
    AND department='$department'
");

if (mysqli_num_rows($q) !== 1) {
    die("Complaint not found");
}

$complaint = mysqli_fetch_assoc($q);

/* Prevent duplicate forwarding */
if ($complaint['status'] === 'Assigned to Dean') {
    echo "<script>
        alert('This complaint is already assigned to the Dean.');
        window.location='department_dashboard.php';
    </script>";
    exit();
}

/* Update complaint → assign to dean */
mysqli_query($conn,"
    UPDATE complain SET
        status        = 'Assigned to Dean',
        dean_name     = 'Dean Office',
        assigned_by   = '$assigned_by',
        assigned_date = NOW()
    WHERE complaint_id = '$complaint_id'
");

/* Success */
echo "<script>
    alert('Complaint forwarded to Dean Office successfully.');
    window.location='department_dashboard.php';
</script>";
exit();
?>
