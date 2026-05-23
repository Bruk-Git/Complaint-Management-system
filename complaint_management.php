<?php
session_start();
include "Connection.php";

/* Protect page */
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

/* Fetch complaints count by department */
$departments = mysqli_query($conn, "
    SELECT department, COUNT(*) AS total
    FROM complain
    GROUP BY department
");

/* Fetch overall stats */
$totalComplaints = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM complain"))[0];
$pendingComplaints = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM complain WHERE status='Pending'"))[0];
$resolvedComplaints = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM complain WHERE status='Resolved'"))[0];
$escalatedComplaints = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM complain WHERE status='Escalated'"))[0];

$page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="icon" href="images/Logos/AU Logo.png">
<title>Complaint Management | CMS Admin</title>

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

.sidebar h4 {
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

.sidebar h4 i {
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
    
    .main {
        margin-left: 0 !important;
        padding: 15px;
    }
    
    .menu-btn {
        display: flex;
    }
}

/* MAIN */
.main {
    margin-left: 240px;
    padding: 25px;
    transition: margin-left 0.3s ease;
}

/* TOPBAR */
.topbar {
    background: white;
    padding: 20px 25px;
    border-radius: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.topbar h5 {
    font-size: 22px;
    font-weight: 700;
    color: #003b6f;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.topbar h5 i {
    color: #ffc107;
}

.topbar div {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 15px;
    font-weight: 500;
    color: #555;
}

.topbar div i {
    color: #ffc107;
    font-size: 20px;
}

/* Page Title */
.page-title {
    color: #003b6f;
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 3px solid rgba(0, 59, 111, 0.1);
    display: flex;
    align-items: center;
    gap: 12px;
}

.page-title i {
    color: #ffc107;
}

/* Stats Overview */
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
.stat-card:nth-child(2)::before { background: linear-gradient(90deg, #ffc107, #fd7e14); }
.stat-card:nth-child(3)::before { background: linear-gradient(90deg, #28a745, #20c997); }
.stat-card:nth-child(4)::before { background: linear-gradient(90deg, #6f42c1, #9c27b0); }

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.1);
}

.stat-card h6 {
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

.stat-card h6 i {
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

/* GRID CARDS */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
    margin-top: 20px;
}

.card-box {
    background: white;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0, 0, 0, 0.03);
    position: relative;
    overflow: hidden;
}

.card-box::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #003b6f, #005fa3, #007acc);
    opacity: 0.8;
}

.card-box:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.card-box h5 {
    color: #003b6f;
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-box h5 i {
    color: #ffc107;
    background: rgba(255, 193, 7, 0.1);
    padding: 8px;
    border-radius: 8px;
    font-size: 18px;
}

.count {
    font-size: 48px;
    font-weight: 800;
    margin: 15px 0;
    color: #003b6f;
    background: linear-gradient(90deg, #003b6f, #005fa3);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1;
    transition: all 0.3s ease;
}

.card-box:hover .count {
    transform: scale(1.1);
}

.card-box p {
    color: #666;
    font-size: 15px;
    line-height: 1.6;
    margin-bottom: 25px;
    min-height: 48px;
}

.card-box a {
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
}

.card-box a::before {
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

.card-box a:hover {
    background: linear-gradient(135deg, #005fa3 0%, #007acc 100%);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 95, 163, 0.3);
}

.card-box a:hover::before {
    left: 100%;
}

.card-box a i {
    font-size: 14px;
}

/* Department-specific colors */
.card-box[data-dept*="Computer"] h5 i { color: #4CAF50; }
.card-box[data-dept*="Information"] h5 i { color: #2196F3; }
.card-box[data-dept*="Electrical"] h5 i { color: #FF9800; }
.card-box[data-dept*="Mechanical"] h5 i { color: #9C27B0; }
.card-box[data-dept*="Business"] h5 i { color: #E91E63; }
.card-box[data-dept*="Accounting"] h5 i { color: #00BCD4; }
.card-box[data-dept*="Science"] h5 i { color: #8BC34A; }
.card-box[data-dept*="Health"] h5 i { color: #F44336; }

/* Animation for cards */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card-box, .stat-card {
    animation: fadeInUp 0.6s ease-out forwards;
}

.card-box:nth-child(1) { animation-delay: 0.1s; }
.card-box:nth-child(2) { animation-delay: 0.2s; }
.card-box:nth-child(3) { animation-delay: 0.3s; }
.card-box:nth-child(4) { animation-delay: 0.4s; }
.card-box:nth-child(5) { animation-delay: 0.5s; }
.card-box:nth-child(6) { animation-delay: 0.6s; }

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }
.stat-card:nth-child(4) { animation-delay: 0.4s; }

/* Scrollbar */
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

.sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
}

/* Empty State */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
}

.empty-state i {
    font-size: 48px;
    color: #ccc;
    margin-bottom: 20px;
    display: block;
}

.empty-state h4 {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #999;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .sidebar {
        width: 220px;
    }
    
    .main {
        margin-left: 220px;
        padding: 20px;
    }
    
    .grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
    
    .stats-overview {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .grid, .stats-overview {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .card-box, .stat-card {
        padding: 25px;
    }
    
    .topbar {
        padding: 15px 20px;
    }
    
    .page-title {
        font-size: 20px;
    }
}

@media (max-width: 600px) {
    .topbar {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .topbar h5 {
        order: 2;
    }
    
    .topbar div {
        order: 3;
    }
    
    .menu-btn {
        order: 1;
        align-self: flex-start;
    }
}
</style>
</head>

<body>
<!-- Overlay for mobile -->
<div class="overlay" id="overlay"></div>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <h4><i class="fas fa-shield-alt"></i> CMS Admin</h4>

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

<!-- MAIN -->
<div class="main">
    <!-- TOP BAR -->
    <div class="topbar">
        <button class="menu-btn" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </button>
        <h5>
            <i class="fas fa-file-alt"></i> Complaint Management
        </h5>
        <div>
            <i class="fas fa-user-circle"></i>
            <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?>
        </div>
    </div>

    <!-- Page Title -->
    <h3 class="page-title">
        <i class="fas fa-chart-bar"></i> Complaint Statistics
    </h3>

    <!-- Stats Overview -->
    <div class="stats-overview">
        <div class="stat-card">
            <h6><i class="fas fa-inbox"></i> Total Complaints</h6>
            <div class="stat-value" id="totalComplaints"><?= $totalComplaints ?></div>
            <div class="stat-label">All time complaints received</div>
        </div>
        
        <div class="stat-card">
            <h6><i class="fas fa-clock"></i> Pending</h6>
            <div class="stat-value" id="pendingComplaints"><?= $pendingComplaints ?></div>
            <div class="stat-label">Awaiting resolution</div>
        </div>
        
        <div class="stat-card">
            <h6><i class="fas fa-check-circle"></i> Resolved</h6>
            <div class="stat-value" id="resolvedComplaints"><?= $resolvedComplaints ?></div>
            <div class="stat-label">Successfully closed</div>
        </div>
        
        <div class="stat-card">
            <h6><i class="fas fa-arrow-up"></i> Escalated</h6>
            <div class="stat-value" id="escalatedComplaints"><?= $escalatedComplaints ?></div>
            <div class="stat-label">Forwarded to higher authorities</div>
        </div>
    </div>

    <!-- Department Cards -->
    <h3 class="page-title" style="margin-top: 40px;">
        <i class="fas fa-building"></i> Complaints by Department
    </h3>

    <div class="grid" id="departmentsGrid">
        <?php if (mysqli_num_rows($departments) > 0): ?>
            <?php 
            $deptCount = 0;
            while ($row = mysqli_fetch_assoc($departments)): 
                $deptCount++;
            ?>
                <div class="card-box" data-dept="<?= htmlspecialchars($row['department']) ?>">
                    <h5>
                        <i class="fas 
                            <?= 
                            strpos($row['department'], 'Computer') !== false ? 'fa-laptop-code' :
                            (strpos($row['department'], 'Information') !== false ? 'fa-database' :
                            (strpos($row['department'], 'Electrical') !== false ? 'fa-bolt' :
                            (strpos($row['department'], 'Mechanical') !== false ? 'fa-cogs' :
                            (strpos($row['department'], 'Business') !== false ? 'fa-briefcase' :
                            (strpos($row['department'], 'Accounting') !== false ? 'fa-calculator' :
                            (strpos($row['department'], 'Science') !== false ? 'fa-flask' :
                            (strpos($row['department'], 'Health') !== false ? 'fa-heartbeat' : 'fa-university'))))))) 
                            ?>">
                        </i>
                        <?= htmlspecialchars($row['department']) ?>
                    </h5>

                    <div class="count" id="count<?= $deptCount ?>">
                        <?= $row['total'] ?>
                    </div>

                    <p>Total complaints submitted in this department with tracking and resolution management.</p>

                    <a href="admin_view_complaints.php?department=<?= urlencode($row['department']) ?>">
                        <i class="fas fa-eye"></i> View Complaints
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h4>No Complaints Found</h4>
                <p>There are currently no complaints in the system.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Toggle sidebar function
function toggleMenu() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const main = document.querySelector('.main');
    
    sidebar.classList.toggle('show');
    overlay.classList.toggle('active');
    
    if (sidebar.classList.contains('show')) {
        main.style.marginLeft = '0';
        document.body.style.overflow = 'hidden';
    } else {
        main.style.marginLeft = '240px';
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
    // Animate stats counters
    const stats = {
        totalComplaints: <?= $totalComplaints ?>,
        pendingComplaints: <?= $pendingComplaints ?>,
        resolvedComplaints: <?= $resolvedComplaints ?>,
        escalatedComplaints: <?= $escalatedComplaints ?>
    };
    
    Object.entries(stats).forEach(([id, value], index) => {
        const element = document.getElementById(id);
        if (element) {
            setTimeout(() => {
                animateCounter(element, value, 1500);
            }, index * 200);
        }
    });
    
    // Animate department counts
    const countElements = document.querySelectorAll('.card-box .count');
    countElements.forEach((element, index) => {
        const target = parseInt(element.textContent.replace(/,/g, ''));
        if (!isNaN(target)) {
            setTimeout(() => {
                animateCounter(element, target, 1200);
            }, (index + 4) * 100);
        }
    });
    
    // Update sidebar margin on window resize
    window.addEventListener('resize', function() {
        const sidebar = document.getElementById('sidebar');
        const main = document.querySelector('.main');
        
        if (window.innerWidth > 768 && !sidebar.classList.contains('show')) {
            main.style.marginLeft = '240px';
        } else {
            main.style.marginLeft = '0';
        }
    });
    
    // Initialize sidebar state based on screen size
    if (window.innerWidth <= 768) {
        document.querySelector('.main').style.marginLeft = '0';
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

// Card hover enhancement
document.querySelectorAll('.card-box').forEach(card => {
    card.addEventListener('mouseenter', function() {
        const countElement = this.querySelector('.count');
        if (countElement) {
            countElement.style.transform = 'scale(1.1)';
        }
    });
    
    card.addEventListener('mouseleave', function() {
        const countElement = this.querySelector('.count');
        if (countElement) {
            countElement.style.transform = 'scale(1)';
        }
    });
});
</script>
</body>
</html>