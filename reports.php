<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

/* Complaint stats */
$byDept = mysqli_query($conn,"
    SELECT department, COUNT(*) total
    FROM complain
    GROUP BY department
    ORDER BY total DESC
");

$byStatus = mysqli_query($conn,"
    SELECT status, COUNT(*) total
    FROM complain
    GROUP BY status
    ORDER BY total DESC
");

/* Fetch total counts */
$totalComplaints = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM complain"))[0];
$totalStudents = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM register_table"))[0];
$totalTeachers = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM teacher_login"))[0];

$page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/Logos/AU Logo.png">
    <title>Reports & Logs | CMS Admin</title>
    
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
        max-width: 1400px;
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

    /* STATS OVERVIEW */
    .stats-overview {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.03);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
    }

    .stat-card:nth-child(1)::before { background: linear-gradient(90deg, #003b6f, #005fa3); }
    .stat-card:nth-child(2)::before { background: linear-gradient(90deg, #28a745, #20c997); }
    .stat-card:nth-child(3)::before { background: linear-gradient(90deg, #ffc107, #fd7e14); }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.1);
    }

    .stat-card h4 {
        color: #666;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .stat-card h4 i {
        font-size: 16px;
    }

    .stat-card .stat-value {
        font-size: 36px;
        font-weight: 800;
        color: #003b6f;
        line-height: 1;
        margin: 10px 0;
    }

    .stat-card .stat-label {
        color: #666;
        font-size: 13px;
        font-weight: 500;
    }

    /* BOX CONTAINER */
    .box {
        background: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        border: 1px solid rgba(0, 0, 0, 0.03);
    }

    .box h3 {
        color: #003b6f;
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 3px solid rgba(0, 59, 111, 0.1);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .box h3 i {
        color: #ffc107;
    }

    /* TABLES */
    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-top: 10px;
    }

    thead {
        background: linear-gradient(90deg, #003b6f 0%, #005fa3 100%);
        border-radius: 12px 12px 0 0;
        overflow: hidden;
    }

    th {
        padding: 18px 20px;
        color: white;
        font-weight: 600;
        text-align: left;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
    }

    th:first-child {
        border-top-left-radius: 12px;
    }

    th:last-child {
        border-top-right-radius: 12px;
    }

    th::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: rgba(255, 255, 255, 0.2);
    }

    tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    tbody tr:hover {
        background: #f8fafd;
        transform: translateX(5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    }

    td {
        padding: 18px 20px;
        color: #555;
        font-size: 15px;
        font-weight: 500;
    }

    /* Progress bar for department stats */
    .progress-container {
        width: 100%;
        background: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 8px;
        height: 10px;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #003b6f, #005fa3);
        border-radius: 10px;
        transition: width 0.3s ease;
    }

    /* BUTTONS */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
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
        margin: 5px 10px 5px 0;
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
        background: linear-gradient(135deg, #005fa3 0%, #007acc 100%);
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 95, 163, 0.3);
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn i {
        font-size: 14px;
    }

    .btn.green {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .btn.green:hover {
        background: linear-gradient(135deg, #20c997 0%, #198754 100%);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
    }

    .btn.blue {
        background: linear-gradient(135deg, #17a2b8 0%, #0dcaf0 100%);
    }

    .btn.blue:hover {
        background: linear-gradient(135deg, #0dcaf0 0%, #138496 100%);
        box-shadow: 0 8px 25px rgba(23, 162, 184, 0.3);
    }

    .btn.orange {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    }

    .btn.orange:hover {
        background: linear-gradient(135deg, #fd7e14 0%, #e6a800 100%);
        box-shadow: 0 8px 25px rgba(255, 193, 7, 0.3);
    }

    /* DOWNLOADS SECTION */
    .downloads-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .download-card {
        background: #f8fafc;
        padding: 25px;
        border-radius: 16px;
        border: 2px dashed #dee2e6;
        text-align: center;
        transition: all 0.3s ease;
    }

    .download-card:hover {
        background: #e9f7fe;
        border-color: #005fa3;
        transform: translateY(-5px);
    }

    .download-card i {
        font-size: 36px;
        color: #003b6f;
        margin-bottom: 15px;
        display: block;
    }

    .download-card h4 {
        color: #003b6f;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .download-card p {
        color: #666;
        font-size: 14px;
        margin-bottom: 20px;
        line-height: 1.5;
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
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .box, .stat-card, .download-card {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    .box:nth-child(1) { animation-delay: 0.1s; }
    .box:nth-child(2) { animation-delay: 0.2s; }
    .box:nth-child(3) { animation-delay: 0.3s; }
    .box:nth-child(4) { animation-delay: 0.4s; }

    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }

    /* STATUS BADGES FOR TABLE */
    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: inline-block;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .status-pending { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); color: white; }
    .status-resolved { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; }
    .status-review { background: linear-gradient(135deg, #17a2b8 0%, #0dcaf0 100%); color: white; }
    .status-assigned { background: linear-gradient(135deg, #9c27b0 0%, #673ab7 100%); color: white; }

    /* RESPONSIVE DESIGN */
    @media (max-width: 1024px) {
        .sidebar {
            width: 220px;
        }
        
        .container {
            margin-left: 220px;
            padding: 20px;
        }
        
        .stats-overview {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .stats-overview {
            grid-template-columns: 1fr;
        }
        
        .downloads-grid {
            grid-template-columns: 1fr;
        }
        
        .box {
            padding: 25px;
        }
        
        .header {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }
        
        .header-actions {
            flex-direction: column;
            width: 100%;
        }
        
        .header-actions .btn {
            width: 100%;
            justify-content: center;
        }
        
        table {
            display: block;
            overflow-x: auto;
        }
        
        th, td {
            padding: 15px;
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .btn {
            padding: 10px 20px;
            font-size: 13px;
            margin: 5px 0;
            width: 100%;
            justify-content: center;
        }
        
        .box h3 {
            font-size: 20px;
        }
        
        .stat-card .stat-value {
            font-size: 32px;
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

    <a class="<?= $page == 'admin_dashboard.php' ? 'active' : '' ?>" href="admin_dashboard.php">
        <i class="fas fa-chart-pie"></i> Overview
    </a>
    <a class="<?= $page == 'user_management.php' ? 'active' : '' ?>" href="user_management.php">
        <i class="fas fa-users"></i> User Management
    </a>
    <a class="<?= $page == 'complaint_management.php' ? 'active' : '' ?>" href="complaint_management.php">
        <i class="fas fa-file-alt"></i> Complaint Management
    </a>
    <a class="<?= $page == 'reports.php' ? 'active' : '' ?>" href="reports.php">
        <i class="fas fa-chart-line"></i> Reports & Logs
    </a>
    
    <a href="admin_login.php">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
</div>

<!-- MAIN CONTAINER -->
<div class="container">
    <!-- HEADER -->
    <div class="header">
        <h2>
            <i class="fas fa-chart-line"></i> Reports & Analytics
        </h2>
        <div class="header-actions">
            <button class="menu-btn" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </button>
            
        </div>
    </div>

    <!-- STATS OVERVIEW -->
    <div class="stats-overview">
        <div class="stat-card">
            <h4><i class="fas fa-inbox"></i> Total Complaints</h4>
            <div class="stat-value" id="totalComplaints"><?= $totalComplaints ?></div>
            <div class="stat-label">All time complaints received</div>
        </div>
        
        <div class="stat-card">
            <h4><i class="fas fa-user-graduate"></i> Total Students</h4>
            <div class="stat-value" id="totalStudents"><?= $totalStudents ?></div>
            <div class="stat-label">Registered students</div>
        </div>
        
        <div class="stat-card">
            <h4><i class="fas fa-chalkboard-teacher"></i> Total Teachers</h4>
            <div class="stat-value" id="totalTeachers"><?= $totalTeachers ?></div>
            <div class="stat-label">Teaching staff</div>
        </div>
    </div>

    <!-- COMPLAINTS BY DEPARTMENT -->
    <div class="box">
        <h3><i class="fas fa-building"></i> Complaints by Department</h3>
        <table>
            <thead>
                <tr>
                    <th>Department</th>
                    <th>Total Complaints</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $deptMax = 0;
                $deptData = [];
                while($d = mysqli_fetch_assoc($byDept)) {
                    $deptData[] = $d;
                    if ($d['total'] > $deptMax) $deptMax = $d['total'];
                }
                
                if (!empty($deptData)) {
                    foreach($deptData as $d):
                        $percentage = $totalComplaints > 0 ? round(($d['total'] / $totalComplaints) * 100, 1) : 0;
                        $progressWidth = $deptMax > 0 ? round(($d['total'] / $deptMax) * 100) : 0;
                ?>
                <tr>
                    <td style="font-weight: 600; color: #003b6f;">
                        <i class="fas fa-university"></i> <?= htmlspecialchars($d['department']) ?>
                    </td>
                    <td>
                        <strong><?= $d['total'] ?></strong> complaints
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <span style="font-weight: 700; color: #005fa3; min-width: 40px;"><?= $percentage ?>%</span>
                            <div class="progress-container">
                                <div class="progress-bar" style="width: <?= $progressWidth ?>%"></div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; 
                } else { ?>
                <tr>
                    <td colspan="3" style="text-align: center; color: #666; padding: 30px;">
                        <i class="fas fa-inbox" style="font-size: 36px; margin-bottom: 15px; display: block; color: #ccc;"></i>
                        No complaint data available
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- COMPLAINTS BY STATUS -->
    <div class="box">
        <h3><i class="fas fa-chart-pie"></i> Complaints by Status</h3>
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $statusData = [];
                while($s = mysqli_fetch_assoc($byStatus)) {
                    $statusData[] = $s;
                }
                
                if (!empty($statusData)) {
                    foreach($statusData as $s):
                        $percentage = $totalComplaints > 0 ? round(($s['total'] / $totalComplaints) * 100, 1) : 0;
                        $statusClass = str_replace(' ', '', strtolower($s['status']));
                ?>
                <tr>
                    <td>
                        <span class="status-badge status-<?= $statusClass ?>">
                            <?= htmlspecialchars($s['status']) ?>
                        </span>
                    </td>
                    <td>
                        <strong><?= $s['total'] ?></strong> complaints
                    </td>
                    <td>
                        <div style="font-weight: 700; color: #005fa3; font-size: 16px;">
                            <?= $percentage ?>%
                        </div>
                    </td>
                </tr>
                <?php endforeach; 
                } else { ?>
                <tr>
                    <td colspan="3" style="text-align: center; color: #666; padding: 30px;">
                        <i class="fas fa-chart-pie" style="font-size: 36px; margin-bottom: 15px; display: block; color: #ccc;"></i>
                        No status data available
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- DOWNLOADS SECTION -->
    <div class="box">
        <h3><i class="fas fa-download"></i> Data Exports</h3>
        <div class="downloads-grid">
            <div class="download-card">
                <i class="fas fa-file-excel"></i>
                <h4>Complaints Report</h4>
                <p>Download all complaints data in Excel format with filters and analytics.</p>
                <a class="btn green" href="export_complaints.php">
                    <i class="fas fa-download"></i> Download Excel
                </a>
            </div>
            
            <div class="download-card">
                <i class="fas fa-users"></i>
                <h4>Students List</h4>
                <p>Export complete student database with department and registration details.</p>
                <a class="btn blue" href="export_students.php">
                    <i class="fas fa-download"></i> Download CSV
                </a>
            </div>
            
            <div class="download-card">
                <i class="fas fa-chalkboard-teacher"></i>
                <h4>Teachers List</h4>
                <p>Get comprehensive teacher information including department assignments.</p>
                <a class="btn orange" href="export_teachers.php">
                    <i class="fas fa-download"></i> Download CSV
                </a>
            </div>
        </div>
    </div>

    <!-- AUDIT LOGS -->
    <div class="box">
        <h3><i class="fas fa-clipboard-list"></i> System Logs</h3>
        <p style="color: #666; margin-bottom: 20px; line-height: 1.6;">
            View system audit logs, user activities, and administrative actions. Track all changes made within the system for security and accountability.
        </p>
        <a class="btn" href="audit_logs.php">
            <i class="fas fa-eye"></i> View Audit Logs
        </a>
        <a class="btn green" href="export_audit_logs.php">
            <i class="fas fa-download"></i> Export Logs
        </a>
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

// Animate number counters
function animateCounter(element, target, duration = 1000) {
    let start = 0;
    const increment = target / (duration / 16);
    
    const timer = setInterval(() => {
        start += increment;
        if (start >= target) {
            element.textContent = Math.round(target).toLocaleString();
            clearInterval(timer);
        } else {
            element.textContent = Math.round(start).toLocaleString();
        }
    }, 16);
}

// Initialize counters when page loads
document.addEventListener('DOMContentLoaded', function() {
    const stats = {
        totalComplaints: <?= $totalComplaints ?>,
        totalStudents: <?= $totalStudents ?>,
        totalTeachers: <?= $totalTeachers ?>
    };
    
    Object.entries(stats).forEach(([id, value], index) => {
        const element = document.getElementById(id);
        if (element) {
            setTimeout(() => {
                animateCounter(element, value, 1500);
            }, index * 200);
        }
    });
    
    // Animate progress bars
    setTimeout(() => {
        document.querySelectorAll('.progress-bar').forEach(bar => {
            const computedStyle = window.getComputedStyle(bar);
            const targetWidth = computedStyle.width;
            bar.style.width = '0';
            setTimeout(() => {
                bar.style.transition = 'width 1.5s ease-out';
                bar.style.width = targetWidth;
            }, 500);
        });
    }, 1000);
    
    // Update sidebar margin on window resize
    window.addEventListener('resize', function() {
        const sidebar = document.getElementById('sidebar');
        const container = document.querySelector('.container');
        
        if (window.innerWidth > 768 && !sidebar.classList.contains('show')) {
            container.style.marginLeft = '240px';
        } else {
            container.style.marginLeft = '0';
        }
    });
    
    // Initialize sidebar state based on screen size
    if (window.innerWidth <= 768) {
        document.querySelector('.container').style.marginLeft = '0';
    }
});

// Add click event to sidebar links to close sidebar on mobile
document.querySelectorAll('.sidebar a').forEach(link => {
    link.addEventListener('click', function() {
        if (window.innerWidth <= 768) {
            toggleMenu();
        }
    });
});

// Table row hover effects
document.querySelectorAll('tbody tr').forEach(row => {
    row.addEventListener('mouseenter', function() {
        this.style.transform = 'translateX(8px)';
    });
    
    row.addEventListener('mouseleave', function() {
        this.style.transform = 'translateX(0)';
    });
});
</script>
</body>
</html>