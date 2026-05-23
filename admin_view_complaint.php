<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$id = intval($_GET['id']);
$q = mysqli_query($conn,"SELECT * FROM complain WHERE complaint_id='$id'");
$c = mysqli_fetch_assoc($q);

if (!$c) die("Complaint not found");

$page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/Logos/AU Logo.png">
    <title>View Complaint | CMS Admin</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
        min-height: 100vh;
        color: #333;
        padding: 20px;
    }

    /* Overlay for mobile */
    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(3px);
        z-index: 998;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .overlay.active {
        display: block;
        opacity: 1;
    }

    /* SIDEBAR */
    .sidebar {
        width: 240px;
        background: linear-gradient(180deg, #003b6f 0%, #005fa3 100%);
        color: #fff;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        transition: transform 0.3s ease;
        z-index: 999;
        box-shadow: 5px 0 20px rgba(0, 0, 0, 0.15);
    }

    .sidebar h3 {
        text-align: center;
        padding: 25px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        font-size: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        background: rgba(0, 0, 0, 0.1);
    }

    .sidebar h3 i {
        color: #ffc107;
        font-size: 24px;
    }

    .sidebar a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px 20px;
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        font-size: 15px;
        font-weight: 500;
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }

    .sidebar a:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border-left: 3px solid #ffc107;
        padding-left: 25px;
    }

    .sidebar a.active {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        border-left: 3px solid #ffc107;
        font-weight: 600;
    }

    .sidebar a i {
        font-size: 18px;
        width: 24px;
        text-align: center;
    }

    .sidebar a:last-child {
        position: absolute;
        bottom: 0;
        width: 100%;
        background: rgba(220, 53, 69, 0.2);
        border-left: 3px solid #dc3545;
    }

    .sidebar a:last-child:hover {
        background: rgba(220, 53, 69, 0.3);
    }

    /* MOBILE */
    .menu-btn {
        display: none;
        background: #003b6f;
        color: white;
        border: none;
        width: 45px;
        height: 45px;
        border-radius: 8px;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        justify-content: center;
        align-items: center;
    }

    .menu-btn:hover {
        background: #005fa3;
        transform: scale(1.05);
    }

    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-240px);
        }
        
        .sidebar.show {
            transform: translateX(0);
        }
        
        .container {
            margin-left: 0 !important;
            padding: 15px;
        }
        
        .menu-btn {
            display: flex;
        }
    }

    /* CONTAINER */
    .container {
        margin-left: 240px;
        padding: 25px;
        transition: margin-left 0.3s ease;
        max-width: 1200px;
    }

    @media (max-width: 768px) {
        .container {
            margin-left: 0 !important;
            padding: 15px;
        }
    }

    /* HEADER */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .header h2 {
        font-size: 28px;
        font-weight: 700;
        color: #003b6f;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .header h2 i {
        color: #ffc107;
        background: rgba(255, 193, 7, 0.1);
        padding: 12px;
        border-radius: 12px;
        font-size: 24px;
    }

    .header-actions {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    /* COMPLAINT BOX */
    .complaint-box {
        background: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.03);
        margin-bottom: 30px;
    }

    /* COMPLAINT HEADER */
    .complaint-header {
        background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
        color: white;
        padding: 25px 30px;
        border-radius: 16px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .complaint-header h1 {
        font-size: 24px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .complaint-header h1 i {
        color: #ffc107;
        background: rgba(255, 255, 255, 0.1);
        padding: 10px;
        border-radius: 10px;
    }

    .complaint-id {
        font-size: 14px;
        background: rgba(255, 255, 255, 0.2);
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
    }

    /* STATUS BADGE */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .status-pending { 
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    }
    
    .status-processing { 
        background: linear-gradient(135deg, #17a2b8 0%, #0dcaf0 100%);
    }
    
    .status-resolved { 
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }
    
    .status-rejected { 
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    }

    /* DETAILS GRID */
    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .detail-card {
        background: #f8fafc;
        padding: 20px;
        border-radius: 12px;
        border-left: 4px solid #003b6f;
        transition: all 0.3s ease;
    }

    .detail-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
        background: #f0f7ff;
    }

    .detail-label {
        font-size: 12px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-label i {
        color: #005fa3;
        font-size: 14px;
    }

    .detail-value {
        font-size: 16px;
        font-weight: 600;
        color: #003b6f;
        line-height: 1.4;
    }

    .detail-value a {
        color: #005fa3;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .detail-value a:hover {
        color: #003b6f;
        text-decoration: underline;
    }

    /* COMPLAINT CONTENT */
    .complaint-content {
        background: #f8fafc;
        padding: 25px;
        border-radius: 16px;
        margin-bottom: 30px;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .content-label {
        font-size: 14px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .content-label i {
        color: #005fa3;
    }

    .content-text {
        background: white;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #e0e0e0;
        font-size: 15px;
        line-height: 1.6;
        color: #444;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    /* TIMELINE SECTION */
    .timeline-section {
        background: #f8fafc;
        padding: 30px;
        border-radius: 16px;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #003b6f;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(0, 59, 111, 0.1);
    }

    .section-title i {
        color: #005fa3;
        background: rgba(0, 95, 163, 0.1);
        padding: 10px;
        border-radius: 10px;
    }

    /* TIMELINE GRID */
    .timeline-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .timeline-item {
        background: white;
        padding: 20px;
        border-radius: 12px;
        border-left: 4px solid;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .timeline-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .timeline-item.teacher {
        border-left-color: #0078d4;
    }

    .timeline-item.dean {
        border-left-color: #17a2b8;
    }

    .timeline-item.responder {
        border-left-color: #28a745;
    }

    .timeline-item.status {
        border-left-color: #ffc107;
    }

    .timeline-label {
        font-size: 12px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .timeline-value {
        font-size: 15px;
        font-weight: 600;
        color: #003b6f;
        line-height: 1.4;
    }

    .timeline-value.empty {
        color: #999;
        font-style: italic;
    }

    /* RESPONSE SECTION */
    .response-section {
        background: white;
        padding: 25px;
        border-radius: 12px;
        border: 2px solid #e0e0e0;
        margin-top: 20px;
    }

    .response-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .response-title {
        font-size: 16px;
        font-weight: 700;
        color: #28a745;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .response-date {
        font-size: 14px;
        color: #666;
        background: #f8f9fa;
        padding: 6px 12px;
        border-radius: 6px;
    }

    .response-text {
        padding: 20px;
        background: #f9f9f9;
        border-radius: 10px;
        font-size: 15px;
        line-height: 1.6;
        color: #444;
        white-space: pre-wrap;
        word-wrap: break-word;
        border: 1px solid #e9ecef;
    }

    /* ATTACHMENT SECTION */
    .attachment-section {
        background: white;
        padding: 25px;
        border-radius: 12px;
        border: 2px dashed #e0e0e0;
        margin-top: 20px;
        text-align: center;
    }

    .attachment-icon {
        font-size: 48px;
        color: #005fa3;
        margin-bottom: 15px;
    }

    .attachment-link {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 24px;
        background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
        color: white;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .attachment-link:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 59, 111, 0.3);
    }

    /* BUTTONS */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        color: white;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        white-space: nowrap;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, 
            transparent, 
            rgba(255, 255, 255, 0.2), 
            transparent);
        transition: left 0.7s;
    }

    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn i {
        font-size: 14px;
    }

    .btn.back {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    }

    .btn.edit {
        background: linear-gradient(135deg, #0078d4 0%, #005fa3 100%);
    }

    .btn.assign {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    /* SCROLLBAR */
    body::-webkit-scrollbar {
        width: 8px;
    }

    body::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    body::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #003b6f, #005fa3);
        border-radius: 4px;
    }

    .sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 3px;
    }

    /* ANIMATIONS */
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

    .complaint-box {
        animation: fadeIn 0.5s ease-out;
    }

    /* RESPONSIVE DESIGN */
    @media (max-width: 1024px) {
        .sidebar {
            width: 220px;
        }
        
        .container {
            margin-left: 220px;
        }
    }

    @media (max-width: 768px) {
        .header {
            flex-direction: column;
            text-align: center;
        }
        
        .complaint-header {
            flex-direction: column;
            text-align: center;
        }
        
        .details-grid {
            grid-template-columns: 1fr;
        }
        
        .timeline-grid {
            grid-template-columns: 1fr;
        }
        
        .header-actions {
            flex-direction: column;
            width: 100%;
        }
        
        .header-actions .btn {
            width: 100%;
            justify-content: center;
        }
        
        .btn {
            padding: 12px 20px;
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        body {
            padding: 10px;
        }
        
        .container {
            padding: 15px;
        }
        
        .complaint-box {
            padding: 20px;
        }
        
        .complaint-header {
            padding: 20px;
        }
        
        .detail-card, .timeline-item, .complaint-content, .timeline-section {
            padding: 15px;
        }
    }
    </style>
</head>
<body>
<!-- Overlay for mobile -->
<div class="overlay" id="overlay"></div>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <h3><i class="fas fa-shield-alt"></i> CMS Admin</h3>

    <a href="admin_dashboard.php"><i class="fas fa-chart-pie"></i> Overview</a>
    <a href="user_management.php"><i class="fas fa-users"></i> User Management</a>
    <a href="complaint_management.php" class="active"><i class="fas fa-file-alt"></i> Complaint Management</a>
    <a href="reports.php"><i class="fas fa-chart-line"></i> Reports & Logs</a>
    <a href="admin_login.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- MAIN CONTAINER -->
<div class="container">
    <!-- HEADER -->
    <div class="header">
        <h2>
            <i class="fas fa-file-alt"></i> Complaint Details
        </h2>
        <div class="header-actions">
            <button class="menu-btn" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </button>
            <a href="admin_view_complaints.php" class="btn back">
                <i class="fas fa-arrow-left"></i> Back to Complaints
            </a>
        </div>
    </div>

    <!-- COMPLAINT BOX -->
    <div class="complaint-box">
        <!-- COMPLAINT HEADER -->
        <div class="complaint-header">
            <div>
                <h1><i class="fas fa-exclamation-circle"></i> Complaint Details</h1>
                <div class="complaint-id">ID: #<?= $c['complaint_id'] ?></div>
            </div>
            <div class="status-badge status-<?= strtolower($c['status']) ?>">
                <i class="fas fa-circle"></i>
                <?= ucfirst($c['status']) ?>
            </div>
        </div>

        <!-- STUDENT DETAILS -->
        <div class="details-grid">
            <div class="detail-card">
                <div class="detail-label">
                    <i class="fas fa-user-graduate"></i> Student Information
                </div>
                <div class="detail-value">
                    <?= htmlspecialchars($c['student_name']) ?>
                    <br>
                    <small style="color: #666; font-weight: 400;">ID: <?= htmlspecialchars($c['student_id']) ?></small>
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-label">
                    <i class="fas fa-envelope"></i> Contact Details
                </div>
                <div class="detail-value">
                    <a href="mailto:<?= htmlspecialchars($c['email']) ?>"><?= htmlspecialchars($c['email']) ?></a>
                    <br>
                    <small style="color: #666; font-weight: 400;">Phone: <?= htmlspecialchars($c['phone']) ?></small>
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-label">
                    <i class="fas fa-building"></i> Department
                </div>
                <div class="detail-value">
                    <?= htmlspecialchars($c['department']) ?>
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-label">
                    <i class="fas fa-tag"></i> Subject
                </div>
                <div class="detail-value">
                    <?= htmlspecialchars($c['subject']) ?>
                </div>
            </div>
        </div>

        <!-- COMPLAINT CONTENT -->
        <div class="complaint-content">
            <div class="content-label">
                <i class="fas fa-align-left"></i> Complaint Description
            </div>
            <div class="content-text">
                <?= nl2br(htmlspecialchars($c['complaint_text'])) ?>
            </div>
        </div>

        <!-- ATTACHMENTS -->
        <?php if($c['file_attachment']): ?>
        <div class="attachment-section">
            <div class="attachment-icon">
                <i class="fas fa-paperclip"></i>
            </div>
            <p style="color: #666; margin-bottom: 15px;">Attachment included with complaint</p>
            <a href="<?= htmlspecialchars($c['file_attachment']) ?>" download class="attachment-link">
                <i class="fas fa-download"></i> Download Attachment
            </a>
        </div>
        <?php endif; ?>

        <!-- TIMELINE -->
        <div class="timeline-section">
            <div class="section-title">
                <i class="fas fa-history"></i> Complaint Timeline
            </div>

            <div class="timeline-grid">
                <div class="timeline-item status">
                    <div class="timeline-label">
                        <i class="fas fa-info-circle"></i> Current Status
                    </div>
                    <div class="timeline-value">
                        <?= ucfirst($c['status']) ?>
                    </div>
                </div>

                <div class="timeline-item teacher">
                    <div class="timeline-label">
                        <i class="fas fa-chalkboard-teacher"></i> Assigned Teacher
                    </div>
                    <div class="timeline-value <?= empty($c['teacher_name']) ? 'empty' : '' ?>">
                        <?= $c['teacher_name'] ? htmlspecialchars($c['teacher_name']) : 'Not assigned' ?>
                    </div>
                </div>

                <div class="timeline-item dean">
                    <div class="timeline-label">
                        <i class="fas fa-user-tie"></i> Assigned Dean
                    </div>
                    <div class="timeline-value <?= empty($c['dean_name']) ? 'empty' : '' ?>">
                        <?= $c['dean_name'] ? htmlspecialchars($c['dean_name']) : 'Not assigned' ?>
                    </div>
                </div>

                <div class="timeline-item responder">
                    <div class="timeline-label">
                        <i class="fas fa-user-check"></i> Responder
                    </div>
                    <div class="timeline-value <?= empty($c['responder_name']) ? 'empty' : '' ?>">
                        <?= $c['responder_name'] ? htmlspecialchars($c['responder_name']) : 'No response yet' ?>
                    </div>
                </div>
            </div>

            <!-- RESPONSE SECTION -->
            <?php if($c['response_text'] || $c['response_file']): ?>
            <div class="response-section">
                <div class="response-header">
                    <div class="response-title">
                        <i class="fas fa-comment-dots"></i> Official Response
                    </div>
                    <?php if($c['response_date']): ?>
                    <div class="response-date">
                        <?= date('M d, Y h:i A', strtotime($c['response_date'])) ?>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if($c['response_text']): ?>
                <div class="response-text">
                    <?= nl2br(htmlspecialchars($c['response_text'])) ?>
                </div>
                <?php endif; ?>

                <?php if($c['response_file']): ?>
                <div style="margin-top: 20px; text-align: center;">
                    <a href="<?= htmlspecialchars($c['response_file']) ?>" download class="btn" style="background: linear-gradient(135deg, #17a2b8 0%, #0dcaf0 100%);">
                        <i class="fas fa-download"></i> Download Response File
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- ACTION BUTTONS -->
        <div style="display: flex; gap: 15px; margin-top: 30px; flex-wrap: wrap;">
            <a href="admin_view_complaints.php" class="btn back">
                <i class="fas fa-arrow-left"></i> Back to Complaints
            </a>
            <a href="admin_edit_complaint.php?id=<?= $c['complaint_id'] ?>" class="btn edit">
                <i class="fas fa-edit"></i> Edit Complaint
            </a>
            
        </div>
    </div>
</div>

<script>
// Toggle sidebar function
function toggleMenu() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const container = document.querySelector('.container');
    
    sidebar.classList.toggle('show');
    overlay.classList.toggle('active');
    
    if (sidebar.classList.contains('show')) {
        container.style.marginLeft = '0';
        document.body.style.overflow = 'hidden';
    } else {
        container.style.marginLeft = '240px';
        document.body.style.overflow = 'auto';
    }
}

// Close sidebar when clicking on overlay
document.getElementById('overlay').addEventListener('click', toggleMenu);

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const menuBtn = document.querySelector('.menu-btn');
    const overlay = document.getElementById('overlay');
    
    if (window.innerWidth <= 768 && 
        !sidebar.contains(event.target) && 
        !menuBtn.contains(event.target) && 
        sidebar.classList.contains('show')) {
        toggleMenu();
    }
});

// Close sidebar with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const sidebar = document.getElementById('sidebar');
        if (sidebar.classList.contains('show')) {
            toggleMenu();
        }
    }
});

// Initialize sidebar state based on screen size
document.addEventListener('DOMContentLoaded', function() {
    if (window.innerWidth <= 768) {
        document.querySelector('.container').style.marginLeft = '0';
    }
    
    // Add click event to sidebar links to close sidebar on mobile
    document.querySelectorAll('.sidebar a').forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                toggleMenu();
            }
        });
    });
});

// Card hover effects
document.querySelectorAll('.detail-card, .timeline-item').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});
</script>
</body>
</html>