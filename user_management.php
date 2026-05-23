<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_name = $_SESSION['admin_name'] ?? 'Admin';
$page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/Logos/AU Logo.png">
    <title>User Management | CMS Admin</title>
    
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
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-top: 20px;
    }

    .card {
        background: white;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0, 0, 0, 0.03);
        position: relative;
        overflow: hidden;
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, 
            #003b6f, 
            #005fa3, 
            #007acc);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .card:hover::before {
        opacity: 1;
    }

    .card h3 {
        color: #003b6f;
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card h3 i {
        color: #ffc107;
        background: rgba(255, 193, 7, 0.1);
        padding: 10px;
        border-radius: 10px;
        font-size: 20px;
    }

    .card p {
        color: #666;
        font-size: 15px;
        line-height: 1.6;
        margin-bottom: 20px;
        min-height: 48px;
    }

    .card a {
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

    .card a::before {
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

    .card a:hover {
        background: linear-gradient(135deg, #005fa3 0%, #007acc 100%);
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 95, 163, 0.3);
    }

    .card a:hover::before {
        left: 100%;
    }

    .card a i {
        font-size: 14px;
    }

    /* Card-specific colors */
    .card:nth-child(1) h3 i { color: #4CAF50; }
    .card:nth-child(2) h3 i { color: #2196F3; }
    .card:nth-child(3) h3 i { color: #9C27B0; }
    .card:nth-child(4) h3 i { color: #FF9800; }
    .card:nth-child(5) h3 i { color: #F44336; }

    /* Stats indicators */
    .stats-indicator {
        display: flex;
        gap: 20px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    .stat {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #003b6f;
    }

    .stat-label {
        font-size: 12px;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

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

    .card {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    .card:nth-child(1) { animation-delay: 0.1s; }
    .card:nth-child(2) { animation-delay: 0.2s; }
    .card:nth-child(3) { animation-delay: 0.3s; }
    .card:nth-child(4) { animation-delay: 0.4s; }
    .card:nth-child(5) { animation-delay: 0.5s; }

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

    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .sidebar {
            width: 220px;
        }
        
        .main {
            margin-left: 220px;
            padding: 20px;
        }
        
        .cards {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .cards {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .card {
            padding: 25px;
        }
        
        .topbar {
            padding: 15px 20px;
        }
        
        .card h3 {
            font-size: 20px;
        }
    }

    @media (max-width: 600px) {
        .cards {
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
    <h3><i class="fa fa-shield-alt"></i> CMS Admin</h3>
    
    <a class="<?= $page == 'admin_dashboard.php' ? 'active' : '' ?>" href="admin_dashboard.php">
        <i class="fa fa-chart-pie"></i> Overview
    </a>
    <a class="<?= $page == 'user_management.php' ? 'active' : '' ?>" href="user_management.php">
        <i class="fa fa-users"></i> User Management
    </a>
    <a class="<?= $page == 'complaint_management.php' ? 'active' : '' ?>" href="complaint_management.php">
        <i class="fa fa-file-alt"></i> Complaint Management
    </a>
    <a class="<?= $page == 'reports.php' ? 'active' : '' ?>" href="reports.php">
        <i class="fa fa-chart-line"></i> Reports & Logs
    </a>
    <a href="admin_login.php">
        <i class="fa fa-sign-out-alt"></i> Logout
    </a>
</div>

<!-- MAIN -->
<div class="main">
    <!-- TOP BAR -->
    <div class="topbar">
        <button class="menu-btn" onclick="toggleMenu()">
            <i class="fa fa-bars"></i>
        </button>
        <b>User Management</b>
        <span>
            <i class="fa fa-user-circle"></i>
            <?= htmlspecialchars($admin_name) ?>
        </span>
    </div>

    <!-- CONTENT -->
    <div class="cards">
        <!-- Students Card -->
        <div class="card">
            <h3><i class="fa fa-graduation-cap"></i> Students</h3>
            <p>Manage student accounts, view complaint history, and track academic progress.</p>
            <div class="stats-indicator">            
            </div>
            <a href="manage_students.php">
                <i class="fa fa-cog"></i> Manage Students
            </a>
        </div>

        <!-- Teachers Card -->
        <div class="card">
            <h3><i class="fa fa-chalkboard-teacher"></i> Teachers</h3>
            <p>Edit, activate or deactivate teacher accounts. Assign complaints and manage responses.</p>
            <a href="manage_teachers.php">
                <i class="fa fa-cog"></i> Manage Teachers
            </a>
        </div>

        <!-- Departments Card -->
        <div class="card">
            <h3><i class="fa fa-building"></i> Departments</h3>
            <p>Control department access, manage staff assignments, and configure department settings.</p>
            <a href="manage_departments.php">
                <i class="fa fa-cog"></i> Manage Departments
            </a>
        </div>

        <!-- Dean Office Card -->
        <div class="card">
            <h3><i class="fa fa-user-tie"></i> Dean Office</h3>
            <p>Manage dean accounts, set permissions, and oversee escalated complaints.</p>
            <a href="manage_deans.php">
                <i class="fa fa-cog"></i> Manage Deans
            </a>
        </div>

        <!-- Admins Card -->
        <div class="card">
            <h3><i class="fa fa-user-shield"></i> System Admins</h3>
            <p>Manage system administrators, configure access levels, and monitor system activities.</p>
            <a href="manage_admins.php">
                <i class="fa fa-cog"></i> Manage Admins
            </a>
        </div>
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
    // Simulated data - replace with actual data from your database
    const userStats = {
        studentCount: 245,
        activeStudents: 230,
        teacherCount: 45,
        assignedTeachers: 38,
        deptCount: 8,
        deptStaff: 32,
        deanCount: 3,
        escalatedCases: 12,
        adminCount: 5,
        activeAdmins: 4
    };
    
    // Animate each counter
    Object.entries(userStats).forEach(([id, value], index) => {
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