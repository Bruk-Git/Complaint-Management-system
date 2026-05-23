<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$departments = mysqli_query($conn,"SELECT * FROM department_login ORDER BY id DESC");

/* Fetch stats */
$totalDepts = mysqli_num_rows($departments);
$activeDepts = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM department_login WHERE status='active'"))[0];
$inactiveDepts = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM department_login WHERE status='inactive'"))[0];

$page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/Logos/AU Logo.png">
    <title>Manage Departments | CMS Admin</title>
    
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

    /* STATS BAR */
    .stats-bar {
        background: white;
        padding: 25px 30px;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        border: 1px solid rgba(0, 0, 0, 0.03);
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .stat-icon {
        background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .stat-info h4 {
        color: #666;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 800;
        color: #003b6f;
        line-height: 1;
    }

    /* SEARCH CONTAINER */
    .search-container {
        background: #f8fafc;
        padding: 25px;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .search-box {
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }

    .search-input {
        flex: 1;
        min-width: 300px;
        position: relative;
    }

    .search-input input {
        width: 100%;
        padding: 15px 20px 15px 50px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: white;
    }

    .search-input input:focus {
        outline: none;
        border-color: #003b6f;
        box-shadow: 0 0 0 3px rgba(0, 59, 111, 0.1);
    }

    .search-input i {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #666;
        font-size: 18px;
    }

    /* TABLE CONTAINER */
    .table-container {
        background: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.03);
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
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
        transform: translateX(8px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    }

    td {
        padding: 20px;
        color: #555;
        font-size: 14px;
        font-weight: 500;
        vertical-align: middle;
    }

    /* STATUS BADGES */
    .status-badge {
        display: inline-block;
        padding: 10px 18px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        min-width: 100px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .status-active { 
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .status-inactive { 
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }

    .status-pending { 
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        color: white;
    }

    /* BUTTONS */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        color: white;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
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
        font-size: 12px;
    }

    .btn.edit {
        background: linear-gradient(135deg, #0078d4 0%, #005fa3 100%);
    }

    .btn.toggle {
        background: linear-gradient(135deg, #ff9800 0%, #fd7e14 100%);
    }

    .btn.delete {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    }

    .btn.add {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .btn.back {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    }

    /* ACTION BUTTONS */
    .actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .actions .btn {
        padding: 8px 16px;
        font-size: 12px;
    }

    /* EMPTY STATE */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #666;
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
    @keyframes fadeInRow {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    tbody tr {
        animation: fadeInRow 0.5s ease-out forwards;
    }

    tbody tr:nth-child(1) { animation-delay: 0.1s; }
    tbody tr:nth-child(2) { animation-delay: 0.2s; }
    tbody tr:nth-child(3) { animation-delay: 0.3s; }
    tbody tr:nth-child(4) { animation-delay: 0.4s; }
    tbody tr:nth-child(5) { animation-delay: 0.5s; }

    /* RESPONSIVE DESIGN */
    @media (max-width: 1024px) {
        .sidebar {
            width: 220px;
        }
        
        .container {
            margin-left: 220px;
            padding: 20px;
        }
    }

    @media (max-width: 768px) {
        .stats-bar {
            flex-direction: column;
            text-align: center;
            gap: 25px;
        }
        
        .search-box {
            flex-direction: column;
        }
        
        .search-input {
            min-width: 100%;
        }
        
        .actions {
            flex-direction: column;
            gap: 5px;
        }
        
        .actions .btn {
            width: 100%;
            justify-content: center;
        }
        
        table {
            display: block;
            overflow-x: auto;
        }
        
        th, td {
            padding: 15px;
            font-size: 13px;
        }
    }

    @media (max-width: 480px) {
        .header {
            flex-direction: column;
            text-align: center;
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
        
        .status-badge {
            padding: 8px 12px;
            font-size: 11px;
            min-width: 80px;
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
    <a href="user_management.php" class="active"><i class="fas fa-users"></i> User Management</a>
    <a href="complaint_management.php"><i class="fas fa-file-alt"></i> Complaint Management</a>
    <a href="reports.php"><i class="fas fa-chart-line"></i> Reports & Logs</a>

    <a href="admin_login.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- MAIN CONTAINER -->
<div class="container">
    <!-- HEADER -->
    <div class="header">
        <h2>
            <i class="fas fa-building"></i> Manage Departments
        </h2>
        <div class="header-actions">
            <button class="menu-btn" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </button>
            <a href="user_management.php" class="btn back">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>
            <a href="admin_add_department.php" class="btn add">
                <i class="fas fa-plus-circle"></i> Add Department
            </a>
        </div>
    </div>

    <!-- STATS BAR -->
    <div class="stats-bar">
        <div class="stat-item">
            <div class="stat-icon">
                <i class="fas fa-building"></i>
            </div>
            <div class="stat-info">
                <h4>Total Departments</h4>
                <div class="stat-value" id="totalCount"><?= $totalDepts ?></div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h4>Active</h4>
                <div class="stat-value" id="activeCount"><?= $activeDepts ?></div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-info">
                <h4>Inactive</h4>
                <div class="stat-value" id="inactiveCount"><?= $inactiveDepts ?></div>
            </div>
        </div>
        <a href="export_departments.php" class="btn" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%);">
            <i class="fas fa-file-export"></i> Export CSV
        </a>
    </div>

    <!-- SEARCH CONTAINER -->
    <div class="search-container">
        <div class="search-box">
            <div class="search-input">
                <i class="fas fa-search"></i>
                <input type="text" 
                       id="searchInput"
                       placeholder="Search departments by name, username, or email...">
            </div>
            <button type="button" class="btn" onclick="clearSearch()">
                <i class="fas fa-redo"></i> Clear Search
            </button>
        </div>
    </div>

    <!-- TABLE CONTAINER -->
    <div class="table-container">
        <table id="departmentsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Department</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Reset pointer to beginning
                mysqli_data_seek($departments, 0);
                if (mysqli_num_rows($departments) > 0): 
                    $rowCount = 0;
                    while ($d = mysqli_fetch_assoc($departments)): 
                        $rowCount++;
                ?>
                        <tr data-id="<?= $d['id'] ?>" data-dept="<?= htmlspecialchars($d['department_name']) ?>">
                            <td>
                                <strong style="color: #003b6f; font-size: 16px;"><?= $d['id'] ?></strong>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #003b6f;">
                                    <i class="fas fa-university"></i> <?= htmlspecialchars($d['department_name']) ?>
                                </div>
                                <small style="color: #666; font-size: 12px;">
                                    Created: <?= date('M d, Y', strtotime($d['created_at'] ?? 'now')) ?>
                                </small>
                            </td>
                            <td>
                                <code style="background: #f8f9fa; padding: 4px 8px; border-radius: 4px; font-size: 13px;">
                                    <?= htmlspecialchars($d['username']) ?>
                                </code>
                            </td>
                            <td>
                                <a href="mailto:<?= htmlspecialchars($d['email']) ?>" style="color: #005fa3;">
                                    <i class="fas fa-envelope"></i> <?= htmlspecialchars($d['email']) ?>
                                </a>
                            </td>
                            <td>
                                <span class="status-badge <?= ($d['status'] == 'active') ? 'status-active' : 'status-inactive' ?>">
                                    <i class="fas <?= ($d['status'] == 'active') ? 'fa-check' : 'fa-times' ?>"></i>
                                    <?= ucfirst($d['status']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a class="btn edit" 
                                       href="admin_edit_department.php?id=<?= $d['id'] ?>"
                                       title="Edit Department">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a class="btn toggle" 
                                       href="toggle_department_status.php?id=<?= $d['id'] ?>"
                                       title="Toggle Status"
                                       onclick="return confirm('Change department status?');">
                                        <i class="fas fa-exchange-alt"></i> Toggle
                                    </a>
                                    <a class="btn delete" 
                                       onclick="return confirmDelete(<?= $d['id'] ?>, '<?= htmlspecialchars(addslashes($d['department_name'])) ?>')"
                                       href="delete_department.php?id=<?= $d['id'] ?>"
                                       title="Delete Department">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-building"></i>
                                <h4>No Departments Found</h4>
                                <p>No departments have been created yet.</p>
                                <a href="admin_add_department.php" class="btn add" style="margin-top: 20px;">
                                    <i class="fas fa-plus-circle"></i> Add First Department
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
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

// Live search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#departmentsTable tbody tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const rowText = row.textContent.toLowerCase();
        if (rowText.includes(searchTerm)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Update counters
    document.getElementById('totalCount').textContent = visibleCount;
});

// Clear search function
function clearSearch() {
    document.getElementById('searchInput').value = '';
    const rows = document.querySelectorAll('#departmentsTable tbody tr');
    rows.forEach(row => {
        row.style.display = '';
    });
    document.getElementById('totalCount').textContent = <?= $totalDepts ?>;
}

// Confirmation for delete actions
function confirmDelete(id, deptName) {
    return confirm(`Are you sure you want to delete the department "${deptName}"?\n\nThis will permanently remove the department and all associated data.`);
}

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
        totalCount: <?= $totalDepts ?>,
        activeCount: <?= $activeDepts ?>,
        inactiveCount: <?= $inactiveDepts ?>
    };
    
    Object.entries(stats).forEach(([id, value], index) => {
        const element = document.getElementById(id);
        if (element) {
            setTimeout(() => {
                animateCounter(element, value, 1500);
            }, index * 200);
        }
    });
    
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

// Row hover effects
document.querySelectorAll('#departmentsTable tbody tr').forEach(row => {
    row.addEventListener('mouseenter', function() {
        this.style.transform = 'translateX(8px)';
    });
    
    row.addEventListener('mouseleave', function() {
        this.style.transform = 'translateX(0)';
    });
});

// Export functionality
function exportToCSV() {
    const rows = document.querySelectorAll('#departmentsTable tbody tr');
    let csvContent = "data:text/csv;charset=utf-8,";
    
    // Add headers
    const headers = ['ID', 'Department Name', 'Username', 'Email', 'Status', 'Created Date'];
    csvContent += headers.join(",") + "\n";
    
    // Add rows
    rows.forEach(row => {
        if (row.style.display !== 'none') {
            const cells = row.querySelectorAll('td');
            const rowData = [];
            
            cells.forEach((cell, index) => {
                let text = cell.textContent.trim();
                // Clean up the text (remove extra spaces and newlines)
                text = text.replace(/\n/g, ' ').replace(/\s+/g, ' ');
                // Remove action buttons text
                if (index === 5) text = '';
                rowData.push(`"${text}"`);
            });
            
            csvContent += rowData.join(",") + "\n";
        }
    });
    
    // Create download link
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "departments_" + new Date().toISOString().slice(0,10) + ".csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
</body>
</html>