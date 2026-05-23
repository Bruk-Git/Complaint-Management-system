<?php
session_start();
include "Connection.php";

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$complaint_id = intval($_GET['id']);

$q = mysqli_query($conn, "
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
<title>Complaint Response Details</title>

<style>
/* Reset and Base Styles */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
    padding: 20px;
}

.container {
    max-width: 900px;
    margin: 30px auto;
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    padding: 40px;
    position: relative;
}

.log-img {
    display: block;
    margin: 0 auto 20px;
    width: 80px;
    height: auto;
}

h2 {
    color: #2c3e50;
    text-align: center;
    margin-bottom: 30px;
    font-size: 28px;
    border-bottom: 3px solid #3498db;
    padding-bottom: 10px;
}

h3 {
    color: #2c3e50;
    margin: 25px 0 15px;
    font-size: 22px;
    padding-left: 10px;
    border-left: 4px solid #3498db;
}

.section {
    display: flex;
    align-items: flex-start;
    margin: 15px 0;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #3498db;
    transition: all 0.3s ease;
}

.section:hover {
    background: #e8f4fc;
    transform: translateX(5px);
}

.label {
    flex: 0 0 180px;
    font-weight: 600;
    color: #2c3e50;
    padding-right: 15px;
    border-right: 1px solid #ddd;
}

.value {
    flex: 1;
    color: #34495e;
    padding-left: 15px;
    line-height: 1.5;
}

.status {
    display: inline-block;
    padding: 6px 15px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status.Submitted {
    background: #f39c12;
    color: white;
}

.status.InReview {
    background: #3498db;
    color: white;
}

.status.Responded {
    background: #2ecc71;
    color: white;
}

.status.Resolved {
    background: #27ae60;
    color: white;
}

.status.Escalated {
    background: #e74c3c;
    color: white;
}

.file-link a {
    display: inline-flex;
    align-items: center;
    color: #2980b9;
    text-decoration: none;
    font-weight: 500;
    padding: 8px 15px;
    background: #e8f4fc;
    border-radius: 5px;
    transition: all 0.3s ease;
    border: 1px solid #3498db;
}

.file-link a:hover {
    background: #3498db;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
}

.file-link a:before {
    content: "📎";
    margin-right: 8px;
    font-size: 16px;
}

hr {
    border: none;
    height: 2px;
    background: linear-gradient(to right, transparent, #3498db, transparent);
    margin: 30px 0;
}

.back-btn {
    display: inline-block;
    margin-top: 30px;
    padding: 12px 25px;
    background: #2c3e50;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.back-btn:hover {
    background: #3498db;
    transform: translateX(-5px);
    box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
}

i {
    color: #7f8c8d;
    font-style: italic;
}

/* Responsive design */
@media (max-width: 768px) {
    .container {
        padding: 20px;
        margin: 10px;
    }
    
    .section {
        flex-direction: column;
    }
    
    .label {
        flex: none;
        border-right: none;
        border-bottom: 1px solid #ddd;
        padding-right: 0;
        padding-bottom: 8px;
        margin-bottom: 8px;
        width: 100%;
    }
    
    .value {
        padding-left: 0;
        width: 100%;
    }
}

</style>
</head>

<body>

<div class="container">
<img class="log-img" src="images/logos/AU Logo.png" alt="AU Logo">
<h2>Complaint & Response Details</h2>

<!-- STUDENT INFO -->
<div class="section">
    <div class="label">Student Name</div>
    <div class="value"><?= htmlspecialchars($data['student_name']); ?></div>
</div>

<div class="section">
    <div class="label">Student ID</div>
    <div class="value"><?= htmlspecialchars($data['student_id']); ?></div>
</div>

<div class="section">
    <div class="label">Email</div>
    <div class="value"><?= htmlspecialchars($data['email']); ?></div>
</div>

<div class="section">
    <div class="label">Phone</div>
    <div class="value"><?= htmlspecialchars($data['phone']); ?></div>
</div>

<!-- COMPLAINT INFO -->
<div class="section">
    <div class="label">Department</div>
    <div class="value"><?= htmlspecialchars($data['department']); ?></div>
</div>

<div class="section">
    <div class="label">Subject</div>
    <div class="value"><?= htmlspecialchars($data['subject']); ?></div>
</div>

<div class="section">
    <div class="label">Complaint Text</div>
    <div class="value"><?= nl2br(htmlspecialchars($data['complaint_text'])); ?></div>
</div>

<?php if (!empty($data['file_attachment'])) { 
    $file_path = "complaint_files/" . htmlspecialchars($data['file_attachment']);
    $file_exists = file_exists($file_path);
?>
<div class="section file-link">
    <div class="label">Complaint Attachment</div>
    <?php if ($file_exists): ?>
        <a href="<?= $file_path; ?>" 
           download 
           target="_blank" 
           title="Download attachment (opens in new tab)">
            📎 Download Attachment
        </a>
        <small style="color: #27ae60; margin-left: 10px;">(File available)</small>
    <?php else: ?>
        <span style="color: #e74c3c;">
            ⚠️ File not found on server
        </span>
        <small style="color: #95a5a6; margin-left: 10px;">(<?= htmlspecialchars($data['file_attachment']); ?>)</small>
    <?php endif; ?>
</div>
<?php } ?>

<!-- STATUS -->
<div class="section">
    <div class="label">Current Status</div>
    <span class="status <?= str_replace(' ', '', $data['status']); ?>">
        <?= $data['status']; ?>
    </span>
</div>

<!-- ASSIGNMENT INFO -->
<?php if (!empty($data['teacher_name'])) { ?>
<div class="section">
    <div class="label">Assigned Teacher</div>
    <div class="value"><?= htmlspecialchars($data['teacher_name']); ?></div>
</div>
<?php } ?>

<?php if (!empty($data['dean_name'])) { ?>
<div class="section">
    <div class="label">Dean Office</div>
    <div class="value"><?= htmlspecialchars($data['dean_name']); ?></div>
</div>
<?php } ?>

<!-- RESPONSE -->
<?php if (!empty($data['response_text'])) { ?>
<hr>

<h3>Response Details</h3>

<div class="section">
    <div class="label">Responded By</div>
    <div class="value">
        <?= htmlspecialchars($data['responder_name']); ?>
    </div>
</div>

<div class="section">
    <div class="label">Response Text</div>
    <div class="value"><?= nl2br(htmlspecialchars($data['response_text'])); ?></div>
</div>

<div class="section">
    <div class="label">Response Date</div>
    <div class="value"><?= $data['response_date']; ?></div>
</div>

<?php if (!empty($data['response_file'])) { 
    $response_path = "uploads/responses/" . htmlspecialchars($data['response_file']);
    $response_exists = file_exists($response_path);
?>
<div class="section file-link">
    <div class="label">Response Attachment</div>
    <?php if ($response_exists): ?>
        <a href="<?= $response_path; ?>" 
           download 
           target="_blank" 
           title="Download response file (opens in new tab)">
            📎 Download Response File
        </a>
        <small style="color: #27ae60; margin-left: 10px;">(File available)</small>
    <?php else: ?>
        <span style="color: #e74c3c;">
            ⚠️ Response file not found
        </span>
        <small style="color: #95a5a6; margin-left: 10px;">(<?= htmlspecialchars($data['response_file']); ?>)</small>
    <?php endif; ?>
</div>
<?php } ?>

<?php } else { ?>
<div class="section">
    <i>No response has been submitted yet.</i>
</div>
<?php } ?>

<a class="back-btn" href="javascript:history.back()">← Back to Previous Page</a>

</div>

<script>
// JavaScript to enhance file download experience
document.addEventListener('DOMContentLoaded', function() {
    // Add file size and type info if available
    const fileLinks = document.querySelectorAll('.file-link a[download]');
    
    fileLinks.forEach(link => {
        // Check if file exists and get info (this would need server-side implementation)
        // For now, we'll just add some visual enhancements
        
        link.addEventListener('click', function(e) {
            // Open in new tab automatically
            if (this.getAttribute('target') === '_blank') {
                // Allow the default behavior
                return true;
            }
            
            // Optional: Add loading indicator
            this.innerHTML = '⏳ Downloading...';
            setTimeout(() => {
                this.innerHTML = '📎 Download Complete';
                setTimeout(() => {
                    this.innerHTML = '📎 Download Attachment';
                }, 2000);
            }, 1000);
        });
    });
    
    // Add tooltip for status
    const statusElement = document.querySelector('.status');
    if (statusElement) {
        statusElement.title = 'Current complaint status';
    }
    
    // Auto-refresh page if status is "InReview" (every 30 seconds)
    const currentStatus = "<?= $data['status']; ?>";
    if (currentStatus === 'In Review') {
        setTimeout(() => {
            if (confirm('Would you like to check for status updates?')) {
                location.reload();
            }
        }, 30000);
    }
});
</script>

</body>
</html>
</html>
