<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_name = $_SESSION['admin_name'] ?? 'Admin';

/* ---- STAT COUNTS ---- */
$students     = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM register_table"))[0];
$teachers     = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM teacher_login"))[0];
$departments  = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(DISTINCT department) FROM complain"))[0];
$complaints   = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM complain"))[0];

$pending      = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM complain WHERE status='Pending'"))[0];
$assignedT    = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM complain WHERE status='Assigned to Teacher'"))[0];
$assignedD    = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM complain WHERE status='Assigned to Dean'"))[0];
$resolved     = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM complain WHERE status='Resolved'"))[0];

$page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="icon" href="images/Logos/AU Logo.png">
<title>CMS Admin Dashboard</title>

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

.topbar b {
    font-size: 22px;
    font-weight: 700;
    color: #003b6f;
    background: linear-gradient(90deg, #003b6f, #005fa3);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.topbar span {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 15px;
    font-weight: 500;
    color: #555;
}

.topbar span i {
    color: #ffc107;
    font-size: 20px;
}

/* CARDS */
.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
}

.card h4 {
    color: #666;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 10px;
}

.card h2 {
    color: #003b6f;
    font-size: 36px;
    font-weight: 700;
    margin: 0;
}

/* Status Cards */
.status-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 30px 0;
}

.status-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.status-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
}

.status-card:nth-child(1)::before { background: #ffc107; }
.status-card:nth-child(2)::before { background: #17a2b8; }
.status-card:nth-child(3)::before { background: #6f42c1; }
.status-card:nth-child(4)::before { background: #28a745; }

.status-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

/* TABLE */
.box {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    margin-top: 30px;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.box h4 {
    color: #003b6f;
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid rgba(0, 59, 111, 0.1);
}

table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

thead {
    background: linear-gradient(90deg, #003b6f 0%, #005fa3 100%);
}

th {
    padding: 15px;
    color: white;
    font-weight: 600;
    text-align: left;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

th:first-child {
    border-top-left-radius: 8px;
}

th:last-child {
    border-top-right-radius: 8px;
}

td {
    padding: 15px;
    color: #555;
    font-size: 14px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.08);
}

tbody tr {
    transition: all 0.3s ease;
}

tbody tr:hover {
    background: #f8fafc;
}

/* BADGE */
.badge {
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    display: inline-block;
    min-width: 100px;
    text-align: center;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
}

.badge.pending {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
    color: white;
}

.badge.resolved {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.badge.assigned {
    background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
    color: white;
}

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

/* Animation for cards */
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

.card, .box {
    animation: fadeIn 0.6s ease-out forwards;
}

.card:nth-child(1) { animation-delay: 0.1s; }
.card:nth-child(2) { animation-delay: 0.2s; }
.card:nth-child(3) { animation-delay: 0.3s; }
.card:nth-child(4) { animation-delay: 0.4s; }

/* Responsive adjustments */
@media (max-width: 1024px) {
    .sidebar {
        width: 220px;
    }
    
    .main {
        margin-left: 220px;
        padding: 20px;
    }
    
    .cards, .status-cards {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }
}

@media (max-width: 768px) {
    .cards, .status-cards {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .card, .status-card {
        padding: 20px;
    }
    
    .box {
        padding: 20px;
        margin-top: 20px;
    }
    
    .topbar {
        padding: 15px 20px;
    }
}

@media (max-width: 480px) {
    .cards, .status-cards {
        grid-template-columns: 1fr;
    }
    
    .topbar {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .topbar b {
        order: 2;
    }
    
    .topbar span {
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
    <h3><i class="fa fa-shield"></i> CMS Admin</h3>
    
    <a class="<?= $page=='admin_dashboard.php'?'active':'' ?>" href="admin_dashboard.php">
        <i class="fa fa-chart-pie"></i> Overview
    </a>
    <a class="<?= $page=='user_management.php'?'active':'' ?>" href="user_management.php">
        <i class="fa fa-users"></i> User Management
    </a>
    <a class="<?= $page=='complaint_management.php'?'active':'' ?>" href="complaint_management.php">
        <i class="fa fa-file-alt"></i> Complaint Management
    </a>
    <a class="<?= $page=='reports.php'?'active':'' ?>" href="reports.php">
        <i class="fa fa-chart-line"></i> Reports & Logs
    </a>
   
    <a href="admin_login.php">
        <i class="fa fa-sign-out-alt"></i> Logout
    </a>
</div>

<!-- MAIN -->
<div class="main">
    <div class="topbar">
        <button class="menu-btn" onclick="toggleMenu()">
            <i class="fa fa-bars"></i>
        </button>
        <b>Dashboard</b>
        <span><i class="fa fa-user-circle"></i> <?= htmlspecialchars($admin_name) ?></span>
    </div>

    <!-- STATS -->
    <div class="cards">
        <div class="card">
            <h4>Students</h4>
            <h2 id="studentCount"><?= $students ?></h2>
        </div>
        <div class="card">
            <h4>Teachers</h4>
            <h2 id="teacherCount"><?= $teachers ?></h2>
        </div>
        <div class="card">
            <h4>Departments</h4>
            <h2 id="deptCount"><?= $departments ?></h2>
        </div>
        <div class="card">
            <h4>Total Complaints</h4>
            <h2 id="complaintCount"><?= $complaints ?></h2>
        </div>
    </div>

    <div class="cards status-cards">
        <div class="card status-card">
            <h4>Pending</h4>
            <h2 id="pendingCount"><?= $pending ?></h2>
        </div>
        <div class="card status-card">
            <h4>Assigned to Teacher</h4>
            <h2 id="assignedTCount"><?= $assignedT ?></h2>
        </div>
        <div class="card status-card">
            <h4>Assigned to Dean</h4>
            <h2 id="assignedDCount"><?= $assignedD ?></h2>
        </div>
        <div class="card status-card">
            <h4>Resolved</h4>
            <h2 id="resolvedCount"><?= $resolved ?></h2>
        </div>
    </div>

    <!-- RECENT -->
    <div class="box">
        <h4><i class="fa fa-history"></i> Recent Complaints</h4>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $r = mysqli_query($conn,"SELECT complaint_id,student_name,status FROM complain ORDER BY created_at DESC LIMIT 5");
                while($c = mysqli_fetch_assoc($r)): 
                    $statusClass = strtolower(str_replace(' ', '', $c['status']));
                ?>
                <tr>
                    <td>#<?= $c['complaint_id'] ?></td>
                    <td><?= htmlspecialchars($c['student_name']) ?></td>
                    <td>
                        <span class="badge <?= $c['status'] == 'Pending' ? 'pending' : 
                                            ($c['status'] == 'Resolved' ? 'resolved' : 'assigned') ?>">
                            <?= $c['status'] ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
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
        document.body.style.overflow = 'hidden'; // Prevent scrolling when sidebar is open
    } else {
        main.style.marginLeft = '240px';
        document.body.style.overflow = 'auto'; // Restore scrolling
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

// Animate number counting
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
    // Get all counter values
    const counters = {
        studentCount: <?= $students ?>,
        teacherCount: <?= $teachers ?>,
        deptCount: <?= $departments ?>,
        complaintCount: <?= $complaints ?>,
        pendingCount: <?= $pending ?>,
        assignedTCount: <?= $assignedT ?>,
        assignedDCount: <?= $assignedD ?>,
        resolvedCount: <?= $resolved ?>
    };
    
    // Animate each counter
    Object.entries(counters).forEach(([id, value], index) => {
        const element = document.getElementById(id);
        if (element) {
            setTimeout(() => {
                animateCounter(element, value, 1500);
            }, index * 100);
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
</script>
</body>
</html>