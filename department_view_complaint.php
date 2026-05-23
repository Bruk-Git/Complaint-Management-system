<?php
session_start();
include "Connection.php";

/* CHECK LOGIN */
if (!isset($_SESSION["department_name"])) {
    header("Location: department_login.php");
    exit();
}

$department = $_SESSION["department_name"];

/* CHECK ID */
if (!isset($_GET['id'])) {
    echo "<script>alert('Invalid Complaint'); window.location='department_dashboard.php';</script>";
    exit();
}

$complaint_id = intval($_GET['id']);

/* FETCH COMPLAINT */
$sql = "
    SELECT *
    FROM complain
    WHERE complaint_id = '$complaint_id'
      AND department = '$department'
";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Complaint not found'); window.location='department_dashboard.php';</script>";
    exit();
}

$complaint = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="images/Logos/AU Logo.png">
<title>View Complaint</title>

<style>
/* Reset and Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
    color: #333;
    line-height: 1.6;
    padding: 20px;
}

/* Box Container */
.box {
    max-width: 800px;
    margin: 40px auto;
    background: white;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    padding: 40px;
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Logo */
.log-img {
    display: block;
    height: 60px;
    width: auto;
    margin: 0 auto 25px;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
}

/* Header */
h2 {
    color: #2c3e50;
    text-align: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
    font-weight: 600;
    font-size: 1.8rem;
}

/* Status Row */
.row:first-of-type {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #f8f9fa;
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
}

/* Row Styles */
.row {
    margin-bottom: 18px;
    padding: 5px 0;
}

.label {
    display: inline-block;
    width: 160px;
    font-weight: 600;
    color: #495057;
    font-size: 0.95rem;
}

/* Horizontal Rule */
hr {
    border: none;
    height: 1px;
    background: linear-gradient(to right, transparent, #e0e0e0, transparent);
    margin: 25px 0;
}

/* Status Badge */
.status {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Status Colors */
.status.Pending {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.status.Responded {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.status.Assigned {
    background: #cce5ff;
    color: #004085;
    border: 1px solid #b8daff;
}

.status.Resolved {
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.status.Review {
    background: #e2e3e5;
    color: #383d41;
    border: 1px solid #d6d8d9;
}

/* Complaint Text */
.row:nth-last-of-type(3) {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #3498db;
    margin-top: 20px;
    line-height: 1.8;
}

/* File Box */
.file-box {
    background: #e8f4fc;
    padding: 15px 20px;
    border-radius: 8px;
    border: 1px dashed #3498db;
    margin-top: 20px;
}

.file-box a {
    color: #2980b9;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    padding: 8px 16px;
    background: white;
    border-radius: 6px;
    border: 1px solid #d1e7f7;
    transition: all 0.3s ease;
}

.file-box a:hover {
    background: #f0f8ff;
    border-color: #3498db;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(52, 152, 219, 0.2);
}

.file-box a::before {
    content: "📎";
    font-size: 1.2rem;
}

/* Response Section */
.row:has(.label:contains("Response:")) {
    background: #f0f9ff;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #27ae60;
    margin-top: 20px;
}

/* Actions Section */
.actions {
    margin-top: 35px;
    padding-top: 25px;
    border-top: 1px solid #e0e0e0;
    text-align: center;
}

/* Back Button */
.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 28px;
    background: #2c3e50;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 2px solid #2c3e50;
}

.back-btn:hover {
    background: white;
    color: #2c3e50;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.back-btn::before {
    content: "←";
    font-weight: bold;
}

/* Print Styles */
@media print {
    body {
        background: white;
        padding: 0;
    }
    
    .box {
        box-shadow: none;
        margin: 0;
        padding: 20px;
    }
    
    .actions, .back-btn {
        display: none;
    }
    
    .file-box a {
        text-decoration: underline;
        color: #000;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    body {
        padding: 10px;
    }
    
    .box {
        padding: 25px;
        margin: 20px auto;
    }
    
    h2 {
        font-size: 1.5rem;
    }
    
    .label {
        display: block;
        width: 100%;
        margin-bottom: 5px;
        font-size: 0.9rem;
    }
    
    .row:first-of-type {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .file-box a {
        display: block;
        text-align: center;
    }
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
    body {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        color: #e0e0e0;
    }
    
    .box {
        background: #2d3436;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
    }
    
    h2 {
        color: #e0e0e0;
        border-color: #3d4447;
    }
    
    .row:first-of-type {
        background: #3d4447;
    }
    
    .label {
        color: #b0b0b0;
    }
    
    hr {
        background: linear-gradient(to right, transparent, #4a5255, transparent);
    }
    
    .row:nth-last-of-type(3),
    .row:has(.label:contains("Response:")) {
        background: #3d4447;
        border-color: #4a5255;
    }
    
    .file-box {
        background: #2c3e50;
        border-color: #3498db;
    }
    
    .file-box a {
        background: #3d4447;
        border-color: #4a5255;
        color: #e0e0e0;
    }
    
    .file-box a:hover {
        background: #4a5255;
        border-color: #3498db;
    }
    
    .back-btn {
        background: #3498db;
        border-color: #3498db;
    }
    
    .back-btn:hover {
        background: #2d3436;
        color: #e0e0e0;
    }
}

/* Accessibility */
a:focus,
.back-btn:focus {
    outline: 3px solid #3498db;
    outline-offset: 2px;
}

/* Selection Color */
::selection {
    background: rgba(52, 152, 219, 0.3);
}

::-moz-selection {
    background: rgba(52, 152, 219, 0.3);
}

/* Loading State (Optional) */
.loading {
    opacity: 0.7;
    pointer-events: none;
}
</style>
</head>

<body>

<div class="box">
<img class="log-img" src="images/logos/AU Logo.png">
    <h2>Complaint Details (#<?php echo $complaint['complaint_id']; ?>)</h2>

    <div class="row">
        <span class="label">Status:</span>
        <span class="status <?php echo $complaint['status']; ?>">
            <?php echo $complaint['status']; ?>
        </span>
    </div>

    <hr>

    <div class="row"><span class="label">Student Name:</span> <?php echo htmlspecialchars($complaint['student_name']); ?></div>
    <div class="row"><span class="label">Student ID:</span> <?php echo htmlspecialchars($complaint['student_id']); ?></div>
    <div class="row"><span class="label">Email:</span> <?php echo htmlspecialchars($complaint['email']); ?></div>
    <div class="row"><span class="label">Phone:</span> <?php echo htmlspecialchars($complaint['phone']); ?></div>
    <div class="row"><span class="label">Department:</span> <?php echo htmlspecialchars($complaint['department']); ?></div>
    <div class="row"><span class="label">Program:</span> <?php echo htmlspecialchars($complaint['program']); ?></div>
    <div class="row"><span class="label">Academic Year:</span> <?php echo htmlspecialchars($complaint['academic_year']); ?></div>

    <hr>

    <div class="row"><span class="label">Subject:</span> <?php echo htmlspecialchars($complaint['subject']); ?></div>

    <div class="row">
        <span class="label">Complaint:</span><br>
        <?php echo nl2br(htmlspecialchars($complaint['complaint_text'])); ?>
    </div>

    <!-- ATTACHMENT -->
    <div class="row file-box">
        <span class="label">Attachment:</span><br>
        <?php if (!empty($complaint['file_attachment'])) { ?>
            <a href="complaint_files/<?php echo htmlspecialchars($complaint['file_attachment']); ?>" target="_blank">
                View / Download Attachment
            </a>
        <?php } else { ?>
            <i>No attachment</i>
        <?php } ?>
    </div>

    <hr>

    <!-- RESPONSE INFO (IF EXISTS) -->
    <?php if (!empty($complaint['response_text'])) { ?>
        <div class="row">
            <span class="label">Responded By:</span>
            <?php echo htmlspecialchars($complaint['responder_name']); ?>
        </div>

        <div class="row">
            <span class="label">Response Date:</span>
            <?php echo $complaint['response_date']; ?>
        </div>

        <div class="row">
            <span class="label">Response:</span><br>
            <?php echo nl2br(htmlspecialchars($complaint['response_text'])); ?>
        </div>

        <?php if (!empty($complaint['response_file'])) { ?>
            <div class="row file-box">
                <span class="label">Response File:</span><br>
                <a href="uploads/<?php echo htmlspecialchars($complaint['response_file']); ?>" target="_blank">
                    Download Response Attachment
                </a>
            </div>
        <?php } ?>
    <?php } ?>

    <div class="actions">
        <a href="department_dashboard.php" class="btn back-btn">← Back to Dashboard</a>
    </div>

</div>

</body>
</html>
