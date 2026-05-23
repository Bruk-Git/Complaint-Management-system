<?php
session_start();
include "Connection.php";

/* PROTECT PAGE */
if (!isset($_SESSION['department_name'])) {
    header("Location: department_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request.");
}

/* INPUTS */
$complaint_id = intval($_POST['complaint_id']);
$teacher_id   = intval($_POST['teacher_id']);
$assigned_by  = $_SESSION['department_name']; // department name as assigner

/* FETCH COMPLAINT */
$cq = mysqli_query($conn,
    "SELECT * FROM complain WHERE complaint_id='$complaint_id'"
);

if (mysqli_num_rows($cq) !== 1) {
    die("Complaint not found.");
}

$complaint = mysqli_fetch_assoc($cq);

/* PREVENT DOUBLE ASSIGNMENT */
if ($complaint['status'] === 'Assigned to Teacher' ||
    $complaint['status'] === 'Assigned to Dean' ||
    $complaint['status'] === 'Responded' ||
    $complaint['status'] === 'Resolved') {

    echo "<script>
        alert('This complaint already has an action taken.');
        window.location='department_dashboard.php';
    </script>";
    exit();
}

/* FETCH TEACHER */
$tq = mysqli_query($conn,
    "SELECT id, teacher_name FROM teacher_login WHERE id='$teacher_id'"
);

if (mysqli_num_rows($tq) !== 1) {
    die("Invalid teacher selected.");
}

$teacher = mysqli_fetch_assoc($tq);

/* UPDATE COMPLAINT (SINGLE TABLE LOGIC) */
$update = mysqli_query($conn, "
    UPDATE complain SET
        status = 'Assigned to Teacher',
        teacher_id = '{$teacher['id']}',
        teacher_name = '{$teacher['teacher_name']}',
        assigned_by = '$assigned_by',
        assigned_date = NOW()
    WHERE complaint_id = '$complaint_id'
");

if (!$update) {
    die("Failed to assign complaint.");
}

/* SUCCESS */
echo "<script>
    alert('Complaint successfully assigned to teacher.');
    window.location='department_dashboard.php';
</script>";
exit();
?>
