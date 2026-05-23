<?php
session_start();
include "Connection.php";

/* Protect admin */
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

/* Search & filter */
$search = $_GET['search'] ?? '';
$department = $_GET['department'] ?? '';
$program = $_GET['program'] ?? '';
$study_mode = $_GET['study_mode'] ?? '';

$where = "WHERE 1";

if ($search != '') {
    $search = mysqli_real_escape_string($conn, $search);
    $where .= " AND (
        first_name LIKE '%$search%' OR 
        last_name LIKE '%$search%' OR 
        email LIKE '%$search%' OR
        student_id LIKE '%$search%'
    )";
}

if ($department != '') {
    $department = mysqli_real_escape_string($conn, $department);
    $where .= " AND department='$department'";
}

if ($program != '') {
    $program = mysqli_real_escape_string($conn, $program);
    $where .= " AND program='$program'";
}

if ($study_mode != '') {
    $study_mode = mysqli_real_escape_string($conn, $study_mode);
    $where .= " AND study_mode='$study_mode'";
}

/* Fetch students */
$students = mysqli_query($conn, "
    SELECT * FROM register_table
    $where
    ORDER BY program DESC, study_mode DESC, id DESC
");

/* Fetch departments for filter dropdown */
$depts = mysqli_query($conn, "
    SELECT DISTINCT department FROM register_table
    WHERE department IS NOT NULL AND department != ''
    ORDER BY department
");

/* Fetch programs for filter dropdown */
$programs = mysqli_query($conn, "
    SELECT DISTINCT program FROM register_table
    WHERE program IS NOT NULL AND program != ''
    ORDER BY 
        CASE program
            WHEN 'TVET' THEN 1
            WHEN 'DEGREE' THEN 2
            WHEN 'MASTERS' THEN 3
            ELSE 4
        END
");

/* Fetch study modes for filter dropdown */
$study_modes = mysqli_query($conn, "
    SELECT DISTINCT study_mode FROM register_table
    WHERE study_mode IS NOT NULL AND study_mode != ''
    ORDER BY 
        CASE study_mode
            WHEN 'REGULAR' THEN 1
            WHEN 'EXTENSION' THEN 2
            WHEN 'DISTANCE' THEN 3
            ELSE 4
        END
");

$totalStudents = mysqli_num_rows($students);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/Logos/AU Logo.png">
    <title>Manage Students | CMS Admin</title>
    
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

    /* Header */
    .header {
        background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
        color: white;
        padding: 25px 35px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 20px rgba(0, 59, 111, 0.3);
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .header h2 {
        font-size: 28px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 15px;
        letter-spacing: 0.5px;
    }

    .header h2 i {
        color: #ffc107;
        background: rgba(255, 255, 255, 0.1);
        padding: 12px;
        border-radius: 12px;
        font-size: 24px;
    }

    /* Container */
    .container {
        padding: 35px;
        max-width: 1800px;
        margin: 0 auto;
    }

    /* Box */
    .box {
        background: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        margin-top: 20px;
        border: 1px solid rgba(0, 0, 0, 0.03);
    }

    /* Box Title */
    .box h3 {
        color: #003b6f;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 3px solid rgba(0, 59, 111, 0.1);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .box h3 i {
        color: #ffc107;
    }

    /* Stats Bar */
    .stats-bar {
        background: linear-gradient(135deg, #f8fafc 0%, #e9f2ff 100%);
        padding: 20px;
        border-radius: 15px;
        margin-bottom: 30px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        border: 1px solid rgba(0, 59, 111, 0.1);
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .stat-icon {
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
        line-height: 1;
    }

    /* Program-specific stats colors */
    .stat-tvet .stat-icon { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }
    .stat-degree .stat-icon { background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%); }
    .stat-masters .stat-icon { background: linear-gradient(135deg, #6f42c1 0%, #8e44ad 100%); }
    .stat-tvet .stat-value { color: #28a745; }
    .stat-degree .stat-value { color: #003b6f; }
    .stat-masters .stat-value { color: #6f42c1; }

    /* Search & Filter */
    .search-bar {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 30px;
        background: #f8fafc;
        padding: 20px;
        border-radius: 15px;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .search-box {
        grid-column: 1 / -1;
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 15px 20px 15px 50px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: white;
    }

    .search-box input:focus {
        outline: none;
        border-color: #003b6f;
        box-shadow: 0 0 0 3px rgba(0, 59, 111, 0.1);
    }

    .search-box i {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #666;
        font-size: 18px;
    }

    .filter-box {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .filter-label {
        font-weight: 600;
        color: #666;
        font-size: 14px;
        padding-left: 5px;
    }

    .filter-box select {
        width: 100%;
        padding: 13px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 14px;
        background: white;
        color: #333;
        cursor: pointer;
    }

    .filter-box select:focus {
        outline: none;
        border-color: #003b6f;
        box-shadow: 0 0 0 3px rgba(0, 59, 111, 0.1);
    }

    /* Buttons */
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
        box-shadow: 0 8px 25px rgba(0, 95, 163, 0.3);
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn i {
        font-size: 14px;
    }

    /* Button Variations */
    .btn.view {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .btn.view:hover {
        background: linear-gradient(135deg, #20c997 0%, #198754 100%);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
    }

    .btn.edit {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    }

    .btn.edit:hover {
        background: linear-gradient(135deg, #fd7e14 0%, #e6a800 100%);
        box-shadow: 0 8px 25px rgba(255, 193, 7, 0.3);
    }

    .btn.toggle {
        background: linear-gradient(135deg, #17a2b8 0%, #0dcaf0 100%);
    }

    .btn.toggle:hover {
        background: linear-gradient(135deg, #0dcaf0 0%, #138496 100%);
        box-shadow: 0 8px 25px rgba(23, 162, 184, 0.3);
    }

    .btn.delete {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    }

    .btn.delete:hover {
        background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
        box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
    }

    /* Table */
    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-top: 20px;
    }

    thead {
        background: linear-gradient(90deg, #003b6f 0%, #005fa3 100%);
        border-radius: 12px 12px 0 0;
        overflow: hidden;
    }

    th {
        padding: 18px 15px;
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
        padding: 16px 15px;
        color: #555;
        font-size: 14px;
        font-weight: 500;
        vertical-align: middle;
    }

    /* Program Badges */
    .program-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: inline-block;
        min-width: 80px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        color: white;
    }

    .program-tvet {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .program-degree {
        background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
    }

    .program-masters {
        background: linear-gradient(135deg, #6f42c1 0%, #8e44ad 100%);
    }

    /* Study Mode Badges */
    .study-mode-badge {
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: inline-block;
        text-align: center;
        min-width: 90px;
    }

    .mode-regular {
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
        border: 1px solid rgba(40, 167, 69, 0.3);
    }

    .mode-extension {
        background: rgba(255, 193, 7, 0.1);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }

    .mode-distance {
        background: rgba(23, 162, 184, 0.1);
        color: #17a2b8;
        border: 1px solid rgba(23, 162, 184, 0.3);
    }

    /* Status Badges */
    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: inline-block;
        min-width: 80px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        color: white;
    }

    .status-active {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .status-inactive {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    }

    .status-pending {
        background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    }

    /* Action Buttons Container */
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .action-buttons .btn {
        padding: 8px 16px;
        font-size: 12px;
        border-radius: 8px;
    }

    /* Empty State */
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

    /* Scrollbar */
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

    /* Filter Buttons */
    .filter-buttons {
        display: flex;
        gap: 10px;
        grid-column: 1 / -1;
        margin-top: 10px;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .container {
            padding: 20px;
            max-width: 100%;
        }
        
        table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
    }

    @media (max-width: 768px) {
        .header {
            flex-direction: column;
            gap: 20px;
            text-align: center;
            padding: 20px;
        }
        
        .stats-bar {
            grid-template-columns: 1fr;
            text-align: center;
        }
        
        .stat-item {
            justify-content: center;
        }
        
        .search-bar {
            grid-template-columns: 1fr;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 5px;
        }
        
        .action-buttons .btn {
            width: 100%;
            justify-content: center;
        }
        
        .filter-buttons {
            flex-direction: column;
        }
    }

    @media (max-width: 480px) {
        th, td {
            padding: 12px 10px;
            font-size: 13px;
        }
        
        .btn {
            padding: 10px 20px;
            font-size: 13px;
        }
        
        .program-badge,
        .status-badge {
            padding: 6px 12px;
            font-size: 11px;
            min-width: 70px;
        }
        
        .study-mode-badge {
            padding: 4px 8px;
            font-size: 10px;
            min-width: 70px;
        }
    }

    /* Animation for table rows */
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
    </style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <h2>
        <i class="fas fa-user-graduate"></i> Manage Students
    </h2>
    <a href="user_management.php" class="btn">
        <i class="fas fa-arrow-left"></i> Back to Users
    </a>
</div>

<div class="container">

    <!-- Stats Bar -->
    <div class="stats-bar">
        <div class="stat-item">
            <div class="stat-icon" style="background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h4>Total Students</h4>
                <div class="stat-value" id="totalCount"><?= $totalStudents ?></div>
            </div>
        </div>
        
        <?php
        // Get counts for each program
        $tvet_count = mysqli_fetch_row(mysqli_query($conn, 
            "SELECT COUNT(*) FROM register_table WHERE program='TVET'"))[0];
        $degree_count = mysqli_fetch_row(mysqli_query($conn, 
            "SELECT COUNT(*) FROM register_table WHERE program='DEGREE'"))[0];
        $masters_count = mysqli_fetch_row(mysqli_query($conn, 
            "SELECT COUNT(*) FROM register_table WHERE program='MASTERS'"))[0];
        ?>
        
        <div class="stat-item stat-tvet">
            <div class="stat-icon">
                <i class="fas fa-certificate"></i>
            </div>
            <div class="stat-info">
                <h4>TVET Students</h4>
                <div class="stat-value"><?= $tvet_count ?></div>
            </div>
        </div>
        
        <div class="stat-item stat-degree">
            <div class="stat-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="stat-info">
                <h4>Degree Students</h4>
                <div class="stat-value"><?= $degree_count ?></div>
            </div>
        </div>
        
        <div class="stat-item stat-masters">
            <div class="stat-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="stat-info">
                <h4>Masters Students</h4>
                <div class="stat-value"><?= $masters_count ?></div>
            </div>
        </div>
        
        <div class="stat-item" style="grid-column: span 1;">
            <a href="register_student.php" class="btn" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); width: 100%;">
                <i class="fas fa-user-plus"></i> Add New Student
            </a>
        </div>
    </div>

    <div class="box">
        <h3>
            <i class="fas fa-list-alt"></i> Student List
        </h3>

        <!-- SEARCH & FILTER -->
        <form method="get" class="search-bar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" 
                       name="search" 
                       placeholder="Search by name, email, or student ID"
                       value="<?= htmlspecialchars($search) ?>"
                       id="searchInput">
            </div>
            
            <div class="filter-box">
                <label class="filter-label">Department</label>
                <select name="department" id="departmentFilter">
                    <option value="">All Departments</option>
                    <?php while ($dept = mysqli_fetch_assoc($depts)): ?>
                        <option value="<?= htmlspecialchars($dept['department']) ?>" 
                            <?= ($department == $dept['department']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($dept['department']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="filter-box">
                <label class="filter-label">Program Type</label>
                <select name="program" id="programFilter">
                    <option value="">All Programs</option>
                    <?php while ($prog = mysqli_fetch_assoc($programs)): ?>
                        <option value="<?= htmlspecialchars($prog['program']) ?>" 
                            <?= ($program == $prog['program']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($prog['program']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="filter-box">
                <label class="filter-label">Study Mode</label>
                <select name="study_mode" id="studyModeFilter">
                    <option value="">All Modes</option>
                    <?php while ($mode = mysqli_fetch_assoc($study_modes)): ?>
                        <option value="<?= htmlspecialchars($mode['study_mode']) ?>" 
                            <?= ($study_mode == $mode['study_mode']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($mode['study_mode']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="filter-buttons">
                <button type="submit" class="btn view" style="flex: 1;">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>
                
                <?php if ($search || $department || $program || $study_mode): ?>
                    <a href="manage_students.php" class="btn toggle" style="flex: 1;">
                        <i class="fas fa-redo"></i> Clear Filters
                    </a>
                <?php endif; ?>
            </div>
        </form>

        <table id="studentsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student Info</th>
                    <th>Program</th>
                    <th>Study Mode</th>
                    <th>Department</th>
                    <th>Academic Info</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($students) > 0): ?>
                    <?php 
                    // Reset the pointer to start
                    mysqli_data_seek($students, 0);
                    while ($s = mysqli_fetch_assoc($students)): 
                        // Determine program badge class
                        $program_class = '';
                        if ($s['program'] == 'TVET') {
                            $program_class = 'program-tvet';
                        } elseif ($s['program'] == 'DEGREE') {
                            $program_class = 'program-degree';
                        } elseif ($s['program'] == 'MASTERS') {
                            $program_class = 'program-masters';
                        }
                        
                        // Determine study mode badge class
                        $study_mode_class = '';
                        if ($s['study_mode'] == 'REGULAR') {
                            $study_mode_class = 'mode-regular';
                        } elseif ($s['study_mode'] == 'EXTENSION') {
                            $study_mode_class = 'mode-extension';
                        } elseif ($s['study_mode'] == 'DISTANCE') {
                            $study_mode_class = 'mode-distance';
                        }
                    ?>
                        <tr data-id="<?= $s['id'] ?>">
                            <td>
                                <strong style="color: #003b6f; font-family: monospace;"><?= htmlspecialchars($s['student_id']) ?></strong>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #333;"><?= htmlspecialchars($s['first_name'] . ' ' . $s['last_name']) ?></div>
                                <div style="font-size: 13px; color: #666;">
                                    <i class="fas fa-envelope"></i> <?= htmlspecialchars($s['email']) ?>
                                </div>
                                <div style="font-size: 12px; color: #888; margin-top: 3px;">
                                    <i class="fas fa-phone"></i> <?= htmlspecialchars($s['mobile_no'] ?? 'N/A') ?>
                                </div>
                            </td>
                            <td>
                                <span class="program-badge <?= $program_class ?>">
                                    <?= htmlspecialchars($s['program'] ?? 'N/A') ?>
                                </span>
                            </td>
                            <td>
                                <span class="study-mode-badge <?= $study_mode_class ?>">
                                    <?= htmlspecialchars($s['study_mode'] ?? 'N/A') ?>
                                </span>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #005fa3;">
                                    <?= htmlspecialchars($s['department'] ?? 'N/A') ?>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 13px;">
                                    <strong>Year:</strong> <?= htmlspecialchars($s['year_level'] ?? 'N/A') ?><br>
                                    <small style="color: #888;">
                                        <i class="fas fa-calendar"></i> 
                                        Created: <?= date('M d, Y', strtotime($s['created_at'] ?? 'now')) ?>
                                    </small>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge <?= ($s['status'] == 'active') ? 'status-active' : 'status-inactive' ?>">
                                    <?= ucfirst($s['status'] ?? 'active') ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a class="btn edit" href="admin_edit_student.php?id=<?= $s['id'] ?>">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a class="btn toggle" href="toggle_student_status.php?id=<?= $s['id'] ?>" 
                                       onclick="return confirm('Change student status?');">
                                        <i class="fas fa-exchange-alt"></i> Status
                                    </a>
                                    <a class="btn view" href="admin_student_complaints.php?student_id=<?= $s['student_id'] ?>">
                                        <i class="fas fa-comments"></i> View
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fas fa-user-slash"></i>
                                <h4>No Students Found</h4>
                                <p>Try adjusting your search or filter criteria</p>
                                <a href="manage_students.php" class="btn" style="margin-top: 20px;">
                                    <i class="fas fa-redo"></i> Reset Search
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
// Live search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#studentsTable tbody tr');
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

// Filter change handlers
function applyFilters() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const selectedDept = document.getElementById('departmentFilter').value.toLowerCase();
    const selectedProgram = document.getElementById('programFilter').value.toLowerCase();
    const selectedMode = document.getElementById('studyModeFilter').value.toLowerCase();
    
    const rows = document.querySelectorAll('#studentsTable tbody tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const rowText = row.textContent.toLowerCase();
        
        // Get specific cell values
        const programCell = row.querySelector('.program-badge');
        const modeCell = row.querySelector('.study-mode-badge');
        const deptCell = row.querySelector('td:nth-child(5)');
        
        const hasProgram = selectedProgram === '' || 
            (programCell && programCell.textContent.toLowerCase().includes(selectedProgram));
        const hasMode = selectedMode === '' || 
            (modeCell && modeCell.textContent.toLowerCase().includes(selectedMode));
        const hasDept = selectedDept === '' || 
            (deptCell && deptCell.textContent.toLowerCase().includes(selectedDept));
        const hasSearch = searchTerm === '' || rowText.includes(searchTerm);
        
        if (hasProgram && hasMode && hasDept && hasSearch) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    document.getElementById('totalCount').textContent = visibleCount;
}

// Add event listeners to filters
document.getElementById('departmentFilter').addEventListener('change', applyFilters);
document.getElementById('programFilter').addEventListener('change', applyFilters);
document.getElementById('studyModeFilter').addEventListener('change', applyFilters);

// Row hover effects
document.querySelectorAll('#studentsTable tbody tr').forEach(row => {
    row.addEventListener('mouseenter', function() {
        this.style.transform = 'translateX(8px)';
    });
    
    row.addEventListener('mouseleave', function() {
        this.style.transform = 'translateX(0)';
    });
});

// Export functionality
function exportToCSV() {
    const rows = document.querySelectorAll('#studentsTable tbody tr');
    let csvContent = "data:text/csv;charset=utf-8,";
    
    // Add headers
    const headers = ['Student ID', 'Name', 'Email', 'Phone', 'Program', 'Study Mode', 
                     'Department', 'Year Level', 'Status', 'Created Date'];
    csvContent += headers.join(",") + "\n";
    
    // Add rows
    rows.forEach(row => {
        if (row.style.display !== 'none') {
            const cells = row.querySelectorAll('td');
            const rowData = [];
            
            // Student ID
            const studentId = row.querySelector('td:nth-child(1) strong')?.textContent || '';
            rowData.push(`"${studentId}"`);
            
            // Name and email (from second td)
            const nameEmailCell = row.querySelector('td:nth-child(2)');
            const name = nameEmailCell?.querySelector('div:nth-child(1)')?.textContent || '';
            const email = nameEmailCell?.querySelector('div:nth-child(2)')?.textContent?.replace('📧 ', '') || '';
            const phone = nameEmailCell?.querySelector('div:nth-child(3)')?.textContent?.replace('📞 ', '') || '';
            
            rowData.push(`"${name}"`);
            rowData.push(`"${email}"`);
            rowData.push(`"${phone}"`);
            
            // Program
            const program = row.querySelector('.program-badge')?.textContent || '';
            rowData.push(`"${program}"`);
            
            // Study Mode
            const studyMode = row.querySelector('.study-mode-badge')?.textContent || '';
            rowData.push(`"${studyMode}"`);
            
            // Department
            const dept = row.querySelector('td:nth-child(5) div')?.textContent || '';
            rowData.push(`"${dept}"`);
            
            // Year Level
            const yearCell = row.querySelector('td:nth-child(6)');
            const yearText = yearCell?.querySelector('div')?.textContent || '';
            const yearLevel = yearText.replace('Year:', '').trim().split('\n')[0] || '';
            rowData.push(`"${yearLevel}"`);
            
            // Status
            const status = row.querySelector('.status-badge')?.textContent || '';
            rowData.push(`"${status}"`);
            
            // Created Date
            const createdDate = yearCell?.querySelector('small')?.textContent?.replace('Created:', '').trim() || '';
            rowData.push(`"${createdDate}"`);
            
            csvContent += rowData.join(",") + "\n";
        }
    });
    
    // Create download link
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "students_" + new Date().toISOString().slice(0,10) + ".csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Add export button to stats bar
document.addEventListener('DOMContentLoaded', function() {
    const statsBar = document.querySelector('.stats-bar');
    const exportBtn = document.createElement('a');
    exportBtn.className = 'btn';
    exportBtn.innerHTML = '<i class="fas fa-file-export"></i> Export CSV';
    exportBtn.style.background = 'linear-gradient(135deg, #6c757d 0%, #495057 100%)';
    exportBtn.onclick = exportToCSV;
    exportBtn.style.marginTop = '10px';
    statsBar.appendChild(exportBtn);
});

// Confirmation for delete actions
document.querySelectorAll('.btn.delete').forEach(btn => {
    btn.addEventListener('click', function(e) {
        if (!confirm('Are you sure you want to delete this student? This action cannot be undone.')) {
            e.preventDefault();
        }
    });
});

// Program type color coding for rows
document.querySelectorAll('#studentsTable tbody tr').forEach(row => {
    const programBadge = row.querySelector('.program-badge');
    if (programBadge) {
        if (programBadge.classList.contains('program-tvet')) {
            row.style.borderLeft = '4px solid #28a745';
        } else if (programBadge.classList.contains('program-degree')) {
            row.style.borderLeft = '4px solid #003b6f';
        } else if (programBadge.classList.contains('program-masters')) {
            row.style.borderLeft = '4px solid #6f42c1';
        }
    }
});
</script>
</body>
</html>