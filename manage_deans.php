<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

/* Search functionality */
$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

/* Fetch deans with search */
$deans = mysqli_query($conn, "
    SELECT * FROM dean_login
    WHERE dean_name LIKE '%$search%'
       OR email LIKE '%$search%'
    ORDER BY id DESC
");

/* Count statistics */
$totalDeans = mysqli_num_rows($deans);

/* Check if table has status column, otherwise default to active */
$tableCheck = mysqli_query($conn, "SHOW COLUMNS FROM dean_login LIKE 'status'");
$hasStatusColumn = mysqli_num_rows($tableCheck) > 0;

if ($hasStatusColumn) {
    $activeDeansResult = mysqli_query($conn, "SELECT COUNT(*) as count FROM dean_login WHERE status='active'");
    $activeDeansRow = mysqli_fetch_assoc($activeDeansResult);
    $activeDeans = $activeDeansRow['count'] ?? $totalDeans;
} else {
    $activeDeans = $totalDeans;
}

/* Count assigned complaints - FIXED: using dean_name instead of dean_id */
$assignedDeansResult = mysqli_query($conn, "
    SELECT COUNT(DISTINCT dean_name) as assigned_count
    FROM complain
    WHERE dean_name IS NOT NULL
      AND dean_name != ''
      AND dean_name IN (SELECT dean_name FROM dean_login)
");
$assignedDeansRow = mysqli_fetch_assoc($assignedDeansResult);
$assignedDeans = $assignedDeansRow['assigned_count'] ?? 0;

$page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/Logos/AU Logo.png">
    <title>Manage Deans | CMS Admin</title>
    
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

    /* SEARCH BOX */
    .search-box {
        background: #f8fafc;
        padding: 25px;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }

    .search-form {
        flex: 1;
        min-width: 300px;
        position: relative;
    }

    .search-form input {
        width: 100%;
        padding: 15px 20px 15px 50px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: white;
    }

    .search-form input:focus {
        outline: none;
        border-color: #003b6f;
        box-shadow: 0 0 0 3px rgba(0, 59, 111, 0.1);
    }

    .search-form i {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #666;
        font-size: 18px;
    }

    .search-form button {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .search-form button:hover {
        background: linear-gradient(135deg, #005fa3 0%, #007acc 100%);
        transform: translateY(-50%) scale(1.05);
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
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        text-decoration: none;
        cursor: pointer;
        border: none;
        font-family: inherit;
    }

    .status-badge:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
    }

    .status-active { 
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .status-inactive { 
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }

    .status-default { 
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
    }

    /* STATS COUNTERS */
    .stats-counter {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: #f8f9fa;
        border-radius: 10px;
        font-weight: 600;
        color: #003b6f;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .stats-counter:hover {
        background: #e9f7fe;
        border-color: #005fa3;
        transform: translateY(-2px);
    }

    .stats-counter i {
        color: #005fa3;
        font-size: 14px;
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

    .btn.view {
        background: linear-gradient(135deg, #17a2b8 0%, #0dcaf0 100%);
    }

    .btn.delete {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    }

    .btn.toggle {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
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

    /* PASSWORD FIELD */
    .password-field {
        position: relative;
        padding-right: 40px;
    }

    .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #666;
        cursor: pointer;
        font-size: 14px;
        padding: 5px;
    }

    .toggle-password:hover {
        color: #003b6f;
    }

    .password-masked {
        color: #666;
        font-family: monospace;
        letter-spacing: 2px;
    }

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
        
        .search-form {
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
        }
        
        .stats-counter {
            padding: 6px 12px;
            font-size: 12px;
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
            <i class="fas fa-user-tie"></i> Manage Deans
        </h2>
        <div class="header-actions">
            <button class="menu-btn" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </button>
            <a href="user_management.php" class="btn back">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>
        </div>
    </div>

    <!-- STATS BAR -->
    <div class="stats-bar">
        <div class="stat-item">
            <div class="stat-icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="stat-info">
                <h4>Total Deans</h4>
                <div class="stat-value" id="totalCount"><?= $totalDeans ?></div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-info">
                <h4>Active</h4>
                <div class="stat-value" id="activeCount"><?= $activeDeans ?></div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon" style="background: linear-gradient(135deg, #17a2b8 0%, #0dcaf0 100%);">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="stat-info">
                <h4>Assigned</h4>
                <div class="stat-value" id="assignedCount"><?= $assignedDeans ?></div>
            </div>
        </div>
        <a href="dean_register.php" class="btn add">
            <i class="fas fa-plus-circle"></i> Add Dean
        </a>
    </div>

    <!-- SEARCH BOX -->
    <div class="search-box">
        <form method="get" class="search-form">
            <i class="fas fa-search"></i>
            <input type="text" 
                   name="search" 
                   placeholder="Search by dean name or email..."
                   value="<?= htmlspecialchars($search); ?>"
                   id="searchInput">
            <button type="submit">Search</button>
        </form>
        
        <?php if ($search): ?>
            <a href="manage_deans.php" class="btn" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%);">
                <i class="fas fa-redo"></i> Clear
            </a>
        <?php endif; ?>
    </div>

    <!-- TABLE CONTAINER -->
    <div class="table-container">
        <table id="deansTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    
                    <th>Status</th>
                    <th>Assigned</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                mysqli_data_seek($deans, 0);
                if (mysqli_num_rows($deans) > 0): 
                    while ($d = mysqli_fetch_assoc($deans)):
                        $did = $d['id'];
                        $deanName = $d['dean_name'];
                        
                        // Count assigned complaints - FIXED query
                        $assignedResult = mysqli_query($conn, "
                            SELECT COUNT(*) as count 
                            FROM complain 
                            WHERE dean_name = '" . mysqli_real_escape_string($conn, $deanName) . "'
                              AND status IN ('Assigned to Dean', 'On Review')
                        ");
                        $assignedRow = mysqli_fetch_assoc($assignedResult);
                        $assigned = $assignedRow['count'] ?? 0;
                        
                        // Determine status
                        if ($hasStatusColumn && isset($d['status'])) {
                            $status = $d['status'];
                            $statusClass = ($status == 'active') ? 'status-active' : 'status-inactive';
                            $statusText = ucfirst($status);
                        } else {
                            $status = 'active';
                            $statusClass = 'status-default';
                            $statusText = 'Active';
                        }
                ?>
                        <tr data-id="<?= $did ?>" data-name="<?= htmlspecialchars($deanName) ?>">
                            <td>
                                <strong style="color: #003b6f; font-size: 16px;">D<?= $did ?></strong>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #003b6f;">
                                    <i class="fas fa-user-tie"></i> <?= htmlspecialchars($deanName) ?>
                                </div>
                            </td>
                            <td>
                                <a href="mailto:<?= htmlspecialchars($d['email']) ?>" style="color: #005fa3; text-decoration: none;">
                                    <i class="fas fa-envelope"></i> <?= htmlspecialchars($d['email']) ?>
                                </a>
                            </td>
                        
                            <td>
                                <?php if ($hasStatusColumn): ?>
                                    <a href="toggle_dean_status.php?id=<?= $did ?>" 
                                       class="status-badge <?= $statusClass ?>"
                                       title="Click to toggle status"
                                       onclick="return confirm('Change dean activation status?')">
                                        <i class="fas <?= ($status == 'active') ? 'fa-check' : 'fa-times' ?>"></i>
                                        <?= $statusText ?>
                                    </a>
                                <?php else: ?>
                                    <span class="status-badge <?= $statusClass ?>" title="No status column in database">
                                        <i class="fas fa-info-circle"></i> <?= $statusText ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="stats-counter" title="Assigned Complaints">
                                    <i class="fas fa-inbox"></i>
                                    <span><?= $assigned ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="actions">
                                    <a class="btn edit" 
                                       href="admin_edit_dean.php?id=<?= $did ?>"
                                       title="Edit Dean Details">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a class="btn view" 
                                       href="admin_dean_complaints.php?id=<?= $did ?>"
                                       title="View Assigned Complaints">
                                        <i class="fas fa-comments"></i> View
                                    </a>
                                    <a class="btn delete" 
                                       onclick="return confirmDelete(<?= $did ?>, '<?= htmlspecialchars(addslashes($deanName)) ?>')"
                                       href="delete_dean.php?id=<?= $did ?>"
                                       title="Delete Dean">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fas fa-user-tie"></i>
                                <h4>No Deans Found</h4>
                                <p><?= $search ? 'Try adjusting your search criteria' : 'No deans have been registered yet' ?></p>
                                <?php if ($search): ?>
                                    <a href="manage_deans.php" class="btn" style="margin-top: 20px;">
                                        <i class="fas fa-redo"></i> Clear Search
                                    </a>
                                <?php else: ?>
                                    <a href="register_dean.php" class="btn add" style="margin-top: 20px;">
                                        <i class="fas fa-plus-circle"></i> Add First Dean
                                    </a>
                                <?php endif; ?>
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

// Toggle password visibility
function togglePassword(id) {
    const maskedSpan = document.querySelector(`#password-${id}`).previousElementSibling;
    const passwordSpan = document.getElementById(`password-${id}`);
    const eyeIcon = document.querySelector(`#password-${id}`).parentElement.querySelector('.toggle-password i');
    
    if (passwordSpan.style.display === 'none') {
        maskedSpan.style.display = 'none';
        passwordSpan.style.display = 'inline';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        maskedSpan.style.display = 'inline';
        passwordSpan.style.display = 'none';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}

// Confirmation for delete actions
function confirmDelete(id, deanName) {
    return confirm(`Are you sure you want to delete dean "${deanName}"?\n\nThis will permanently remove the dean account.`);
}

// Live search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#deansTable tbody tr');
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
    
    // Update counter
    document.getElementById('totalCount').textContent = visibleCount;
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
        totalCount: <?= $totalDeans ?>,
        activeCount: <?= $activeDeans ?>,
        assignedCount: <?= $assignedDeans ?>
    };
    
    Object.entries(stats).forEach(([id, value], index) => {
        const element = document.getElementById(id);
        if (element) {
            setTimeout(() => {
                animateCounter(element, value, 1500);
            }, index * 200);
        }
    });
    
    // Initialize sidebar state
    if (window.innerWidth <= 768) {
        document.querySelector('.container').style.marginLeft = '0';
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
</script>
</body>
</html>