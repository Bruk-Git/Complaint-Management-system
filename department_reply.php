<?php
session_start();
include "Connection.php";

/* Protect page */
if (!isset($_SESSION['department_name'])) {
    header("Location: department_login.php");
    exit();
}

/* Validate ID */
if (!isset($_GET['id'])) {
    die("Invalid complaint");
}

$complaint_id = intval($_GET['id']);

/* Fetch complaint from complain table */
$q = mysqli_query($conn,"
    SELECT * FROM complain
    WHERE complaint_id = '$complaint_id'
");

$data = mysqli_fetch_assoc($q);

if (!$data) {
    die("Complaint not found");
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="images/Logos/AU Logo.png">
<title>Department Response</title>

<style>
body {
    font-family: Arial, sans-serif;
    background:#eef3f7;
    margin:0;
}

.box {
    width:700px;
    margin:40px auto;
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0 4px 10px rgba(0,0,0,.15);
}

h3 {
    margin-top:0;
    color:#003b6f;
}

.info {
    background:#f5f7fa;
    padding:15px;
    border-radius:8px;
    margin-bottom:20px;
}

.info p {
    margin:6px 0;
}

.label {
    font-weight:bold;
}

textarea {
    width:100%;
    height:130px;
    padding:10px;
    border-radius:6px;
    border:1px solid #ccc;
    resize:none;
}

input[type=file] {
    margin-top:8px;
}

button {
    margin-top:15px;
    padding:10px 18px;
    background:#0078d4;
    color:white;
    border:none;
    border-radius:6px;
    font-size:15px;
    cursor:pointer;
}

button:hover {
    background:#005fa3;
}

.file-box {
    margin-top:8px;
}

.file-box a {
    color:#0078d4;
    text-decoration:none;
}
</style>
</head>

<body>

<div class="box">
    <h3>Department Response</h3>

    <!-- Complaint Information -->
    <div class="info">
        <p><span class="label">Student Name:</span> <?= htmlspecialchars($data['student_name']); ?></p>
        <p><span class="label">Student ID:</span> <?= htmlspecialchars($data['student_id']); ?></p>
        <p><span class="label">Email:</span> <?= htmlspecialchars($data['email']); ?></p>
        <p><span class="label">Phone:</span> <?= htmlspecialchars($data['phone']); ?></p>
        <p><span class="label">Department:</span> <?= htmlspecialchars($data['department']); ?></p>
        <p><span class="label">Program:</span> <?= htmlspecialchars($data['program']); ?></p>
        <p><span class="label">Academic Year:</span> <?= htmlspecialchars($data['academic_year']); ?></p>
        <p><span class="label">Subject:</span> <?= htmlspecialchars($data['subject']); ?></p>

        <p><span class="label">Complaint:</span><br>
            <?= nl2br(htmlspecialchars($data['complaint_text'])); ?>
        </p>

        <!-- Attached complaint file -->
        <?php if (!empty($data['file_attachment'])) { ?>
            <div class="file-box">
                <span class="label">Attached File:</span>
                <a href="complaint_files/<?= htmlspecialchars($data['file_attachment']); ?>" target="_blank">
                    View / Download
                </a>
            </div>
        <?php } else { ?>
            <p><span class="label">Attached File:</span> None</p>
        <?php } ?>
    </div>

    <!-- Department Response Form -->
    <form method="post" action="department_response_process.php" enctype="multipart/form-data">

        <input type="hidden" name="complaint_id" value="<?= $data['complaint_id']; ?>">

        <label class="label">Department Response</label>
        <textarea name="response_text" required></textarea>

        <label class="label">Attach File (optional)</label><br>
        <input type="file" name="response_file" accept="image/*,application/pdf">

        <button type="submit">Submit Response</button>
    </form>
</div>

</body>
</html>
