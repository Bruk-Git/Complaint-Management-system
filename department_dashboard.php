<?php
session_start();
include "Connection.php";

/* Protect page */
if (!isset($_SESSION['department_name'])) {
    header("Location: department_login.php");
    exit();
}

$department = $_SESSION['department_name'];
$username   = $_SESSION['username'];

/* Search values */
$searchActive  = $_GET['search_active']  ?? '';
$searchHistory = $_GET['search_history'] ?? '';

$searchActive  = mysqli_real_escape_string($conn, $searchActive);
$searchHistory = mysqli_real_escape_string($conn, $searchHistory);

/* Active complaints with program info */
$activeComplaints = mysqli_query($conn,"
    SELECT * FROM complain
    WHERE department='$department'
      AND status NOT IN ('Responded','Resolved')
      AND (student_name LIKE '%$searchActive%'
           OR student_id LIKE '%$searchActive%'
           OR subject LIKE '%$searchActive%')
    ORDER BY created_at DESC
");

/* History complaints */
$historyComplaints = mysqli_query($conn,"
    SELECT * FROM complain
    WHERE department='$department'
      AND status IN ('Responded','Resolved')
      AND (student_name LIKE '%$searchHistory%'
           OR student_id LIKE '%$searchHistory%'
           OR subject LIKE '%$searchHistory%')
    ORDER BY response_date DESC
");

/* Teachers */
$teachers = mysqli_query($conn,"
    SELECT id, teacher_name
    FROM teacher_login
    WHERE department='$department'
    AND status = 'active'
");

/* Get statistics */
$stats = mysqli_query($conn, "
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'On Review' THEN 1 ELSE 0 END) as on_review,
        SUM(CASE WHEN status = 'Assigned to Teacher' THEN 1 ELSE 0 END) as assigned_teacher,
        SUM(CASE WHEN status = 'Assigned to Dean' THEN 1 ELSE 0 END) as assigned_dean,
        SUM(CASE WHEN status = 'Responded' THEN 1 ELSE 0 END) as responded,
        SUM(CASE WHEN status = 'Resolved' THEN 1 ELSE 0 END) as resolved,
        SUM(CASE WHEN is_anonymous = '1' THEN 1 ELSE 0 END) as anonymous
    FROM complain 
    WHERE department='$department'
");
$stats_data = mysqli_fetch_assoc($stats);

/* Recent anonymous complaints */
$recentAnonymous = mysqli_query($conn,"
    SELECT * FROM complain
    WHERE department='$department'
    AND is_anonymous = '1'
    ORDER BY created_at DESC
    LIMIT 3
");
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="images/Logos/AU Logo.png">
<title><?= htmlspecialchars($department) ?> | Department Dashboard</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
:root {
    --primary: #003b6f;
    --secondary: #005fa3;
    --success: #28a745;
    --warning: #ff9800;
    --danger: #dc3545;
    --info: #17a2b8;
    --light: #f8f9fa;
    --dark: #343a40;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
    color: #333;
    min-height: 100vh;
}

/* Header */
.header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    color: #fff;
    padding: 25px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 20px rgba(0, 59, 111, 0.2);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, #ffc107, #ff9800);
}

.header h2 {
    font-size: 28px;
    font-weight: 700;
    flex: 1;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
}

.header > div {
    display: flex;
    align-items: center;
    gap: 20px;
    font-size: 16px;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.95);
}

/* Main Container */
.container {
    padding: 30px 40px;
    max-width: 1400px;
    margin: 0 auto;
}

/* Stats Cards */
.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    display: flex;
    align-items: center;
    gap: 20px;
    transition: transform 0.3s ease;
    border-left: 5px solid var(--primary);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.stat-icon.total { background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); }
.stat-icon.pending { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); }
.stat-icon.review { background: linear-gradient(135deg, var(--info) 0%, #0dcaf0 100%); }
.stat-icon.teacher { background: linear-gradient(135deg, #0078d4 0%, #005fa3 100%); }
.stat-icon.dean { background: linear-gradient(135deg, var(--warning) 0%, #ffc107 100%); }
.stat-icon.responded { background: linear-gradient(135deg, var(--success) 0%, #20c997 100%); }
.stat-icon.resolved { background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); }
.stat-icon.anonymous { background: linear-gradient(135deg, #6f42c1 0%, #8a63d2 100%); }

.stat-info h3 {
    font-size: 32px;
    color: var(--dark);
    margin-bottom: 5px;
}

.stat-info p {
    color: #6c757d;
    font-size: 14px;
    font-weight: 500;
}

/* Section */
.section {
    background: white;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.03);
    margin-bottom: 30px;
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.section h3 {
    color: var(--primary);
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #eef3f7;
    position: relative;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section h3::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 80px;
    height: 2px;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
}

/* Search Box */
.search-box {
    margin-bottom: 25px;
    display: flex;
    gap: 12px;
    align-items: center;
    background: var(--light);
    padding: 20px;
    border-radius: 12px;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.search-box input {
    flex: 1;
    padding: 14px 20px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 15px;
    transition: all 0.3s ease;
    background: white;
    min-width: 300px;
}

.search-box input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(0, 59, 111, 0.1);
}

.search-box button {
    padding: 14px 28px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 8px;
}

.search-box button:hover {
    background: linear-gradient(135deg, var(--secondary) 0%, #007acc 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 95, 163, 0.3);
}

/* Table */
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

thead {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
}

th {
    padding: 18px 20px;
    color: white;
    font-weight: 600;
    text-align: left;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: none;
}

th:first-child {
    border-top-left-radius: 12px;
}

th:last-child {
    border-top-right-radius: 12px;
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
    padding: 20px;
    color: #555;
    font-size: 14px;
    font-weight: 500;
    vertical-align: middle;
    background: white;
}

/* Student Info */
.student-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.student-name {
    color: var(--primary);
    font-weight: 600;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.student-name.anonymous {
    color: #6f42c1;
}

.student-details {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    font-size: 12px;
    color: #6c757d;
}

.student-details span {
    background: #f8f9fa;
    padding: 4px 10px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Badges */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    min-width: 140px;
    justify-content: center;
}

.badge:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
}

.pending { 
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
}

.review { 
    background: linear-gradient(135deg, var(--info) 0%, #0dcaf0 100%);
    color: white;
}

.teacher { 
    background: linear-gradient(135deg, #0078d4 0%, #005fa3 100%);
    color: white;
}

.dean { 
    background: linear-gradient(135deg, var(--warning) 0%, #ffc107 100%);
    color: #003b6f;
}

.responded { 
    background: linear-gradient(135deg, var(--success) 0%, #20c997 100%);
    color: white;
}

.resolved { 
    background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
    color: white;
}

/* Buttons */
.btn {
    padding: 10px 18px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    color: white;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    min-width: 100px;
    margin: 3px;
    text-align: center;
    position: relative;
    overflow: hidden;
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

.btn:hover::before {
    left: 100%;
}

.view { 
    background: linear-gradient(135deg, var(--success) 0%, #20c997 100%);
}

.view:hover {
    background: linear-gradient(135deg, #20c997 0%, var(--success) 100%);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
}

.assign { 
    background: linear-gradient(135deg, #0078d4 0%, #005fa3 100%);
    padding: 10px 20px;
}

.assign:hover {
    background: linear-gradient(135deg, #005fa3 0%, #0078d4 100%);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 120, 212, 0.3);
}

.dean-btn { 
    background: linear-gradient(135deg, var(--warning) 0%, #ffc107 100%);
    color: #003b6f;
}

.dean-btn:hover {
    background: linear-gradient(135deg, #ffc107 0%, var(--warning) 100%);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(255, 152, 0, 0.3);
}

.reply { 
    background: linear-gradient(135deg, #6f42c1 0%, #8a63d2 100%);
}

.reply:hover {
    background: linear-gradient(135deg, #8a63d2 0%, #6f42c1 100%);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(111, 66, 193, 0.3);
}

.wait { 
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    cursor: not-allowed;
    opacity: 0.7;
}

.logout { 
    background: linear-gradient(135deg, var(--danger) 0%, #c82333 100%);
    padding: 12px 28px;
    font-size: 15px;
    min-width: 120px;
}

.logout:hover {
    background: linear-gradient(135deg, #c82333 0%, var(--danger) 100%);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
}

/* Form Elements */
form {
    display: inline-flex;
    gap: 10px;
    align-items: center;
    background: #f8fafc;
    padding: 12px;
    border-radius: 10px;
    margin: 5px 0;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

select {
    padding: 12px 16px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    background: white;
    color: var(--primary);
    font-weight: 500;
    transition: all 0.3s ease;
    min-width: 220px;
    cursor: pointer;
}

select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(0, 59, 111, 0.1);
}

/* Anonymous Icon */
.anonymous-icon {
    color: #6f42c1;
    background: #f3f0ff;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    margin-left: 5px;
}

/* Program Badge */
.program-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    background: #e7f3ff;
    color: #0066cc;
    border: 1px solid #b3d9ff;
}

/* Action Cells */
.action-cell {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 40px;
    color: #6c757d;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 15px;
    color: #dee2e6;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .container {
        padding: 20px 30px;
    }
    
    .stats-container {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }
}

@media (max-width: 992px) {
    table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
    
    th, td {
        min-width: 150px;
    }
    
    .header {
        padding: 20px 30px;
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .header h2 {
        font-size: 22px;
    }
    
    .search-box {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-box input {
        min-width: auto;
        width: 100%;
    }
    
    .action-cell {
        flex-direction: column;
        align-items: flex-start;
    }
}

@media (max-width: 768px) {
    .container {
        padding: 15px 20px;
    }
    
    .section {
        padding: 20px;
    }
    
    .section h3 {
        font-size: 20px;
    }
    
    .header {
        padding: 15px 20px;
    }
    
    .btn {
        padding: 8px 12px;
        font-size: 12px;
        min-width: 80px;
    }
    
    .badge {
        padding: 6px 12px;
        font-size: 11px;
        min-width: 120px;
    }
    
    select {
        padding: 10px 12px;
        min-width: 180px;
        font-size: 13px;
    }
    
    form {
        flex-direction: column;
        align-items: stretch;
    }
    
    td, th {
        padding: 15px 10px;
        font-size: 13px;
    }
    
    .stat-card {
        padding: 20px;
    }
    
    .stat-info h3 {
        font-size: 24px;
    }
}

@media (max-width: 480px) {
    .header {
        padding: 12px 15px;
    }
    
    .header h2 {
        font-size: 18px;
    }
    
    .container {
        padding: 10px 15px;
    }
    
    .section {
        padding: 15px;
    }
    
    .btn {
        padding: 6px 10px;
        font-size: 11px;
        min-width: 70px;
    }
    
    .badge {
        padding: 5px 10px;
        font-size: 10px;
        min-width: 100px;
    }
    
    select {
        padding: 8px 10px;
        min-width: 150px;
        font-size: 12px;
    }
    
    .search-box input {
        padding: 12px;
    }
    
    .search-box button {
        padding: 12px;
    }
}

/* Scrollbar Styling */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
}
</style>
</head>

<body>

<div class="header">
    <h2><?= htmlspecialchars($department) ?> Department Dashboard</h2>
    <div>
        <?= htmlspecialchars($username) ?> |
        <a class="btn logout" href="department_login.php">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<div class="container">

<!-- ================= STATISTICS ================= -->
<div class="stats-container">
    <div class="stat-card">
        <div class="stat-icon total">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-info">
            <h3><?= $stats_data['total'] ?></h3>
            <p>Total Complaints</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon pending">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-info">
            <h3><?= $stats_data['pending'] + $stats_data['on_review'] ?></h3>
            <p>Active Complaints</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon teacher">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <div class="stat-info">
            <h3><?= $stats_data['assigned_teacher'] ?></h3>
            <p>Assigned to Teachers</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon anonymous">
            <i class="fas fa-user-secret"></i>
        </div>
        <div class="stat-info">
            <h3><?= $stats_data['anonymous'] ?></h3>
            <p>Anonymous Complaints</p>
        </div>
    </div>
</div>

<!-- ================= ACTIVE COMPLAINTS ================= -->
<div class="section">
<h3><i class="fas fa-exclamation-circle"></i> Active Complaints</h3>

<form class="search-box" method="get">
    <input type="text" name="search_active"
           placeholder="Search by student name, ID, or subject"
           value="<?= htmlspecialchars($searchActive) ?>">
    <button type="submit">
        <i class="fas fa-search"></i> Search
    </button>
</form>

<?php if (mysqli_num_rows($activeComplaints) > 0): ?>
<table>
<tr>
    <th>ID</th>
    <th>Student Information</th>
    <th>Subject</th>
    <th>Status</th>
    <th>Program</th>
    <th>Actions</th>
</tr>

<?php while ($c = mysqli_fetch_assoc($activeComplaints)):
    $cid = $c['complaint_id'];
    $is_anonymous = $c['is_anonymous'] == '1';
    
    /* Auto move Pending → On Review */
    if ($c['status'] === 'Pending') {
        mysqli_query($conn,"
            UPDATE complain SET status='On Review'
            WHERE complaint_id='$cid'
        ");
        $c['status'] = 'On Review';
    }
    
    $badge = match($c['status']) {
        'On Review' => 'review',
        'Assigned to Teacher' => 'teacher',
        'Assigned to Dean' => 'dean',
        default => 'pending'
    };
?>
<tr>
<td>#<?= $cid ?></td>

<td>
    <div class="student-info">
        <div class="student-name <?= $is_anonymous ? 'anonymous' : '' ?>">
            <?= htmlspecialchars($c['student_name']) ?>
            <?php if($is_anonymous): ?>
                <span class="anonymous-icon" title="Anonymous Submission">
                    <i class="fas fa-user-secret"></i> Anonymous
                </span>
            <?php endif; ?>
        </div>
        <div class="student-details">
            <span><i class="fas fa-id-card"></i> <?= htmlspecialchars($c['student_id']) ?></span>
            <?php if(!$is_anonymous && $c['email'] != 'anonymous@university.edu'): ?>
                <span><i class="fas fa-envelope"></i> <?= htmlspecialchars($c['email']) ?></span>
            <?php endif; ?>
            <?php if(!$is_anonymous && $c['phone'] != 'N/A'): ?>
                <span><i class="fas fa-phone"></i> <?= htmlspecialchars($c['phone']) ?></span>
            <?php endif; ?>
        </div>
    </div>
</td>

<td><?= htmlspecialchars($c['subject']) ?></td>

<td><span class="badge <?= $badge ?>"><?= $c['status'] ?></span></td>

<td>
    <?php if($c['program']): ?>
        <span class="program-badge"><?= htmlspecialchars($c['program']) ?></span>
    <?php else: ?>
        <span style="color: #6c757d; font-size: 12px;">N/A</span>
    <?php endif; ?>
</td>

<td>
    <div class="action-cell">
        <a class="btn view" href="department_view_complaint.php?id=<?= $cid ?>">
            <i class="fas fa-eye"></i> View
        </a>
        <a class="btn reply" href="department_reply.php?id=<?= $cid ?>">
            <i class="fas fa-reply"></i> Respond
        </a>
        
        <?php if ($c['status'] === 'On Review'): ?>
        <form method="post" action="assign_teacher_process.php">
            <input type="hidden" name="complaint_id" value="<?= $cid ?>">
            <select name="teacher_id" required>
                <option value="">Select Teacher</option>
                <?php mysqli_data_seek($teachers,0);
                while ($t = mysqli_fetch_assoc($teachers)): ?>
                    <option value="<?= $t['id'] ?>">
                        <?= htmlspecialchars($t['teacher_name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button class="btn assign">
                <i class="fas fa-user-tag"></i> Assign
            </button>
        </form>
        
        <a class="btn dean-btn"
           onclick="return confirm('Forward this complaint to Dean Office?')"
           href="send_to_dean_process.php?id=<?= $cid ?>">
            <i class="fas fa-share"></i> Dean
        </a>
        <?php else: ?>
        <span class="btn wait">
            <i class="fas fa-clock"></i> Action Taken
        </span>
        <?php endif; ?>
    </div>
</td>
</tr>
<?php endwhile; ?>
</table>
<?php else: ?>
<div class="empty-state">
    <i class="fas fa-check-circle"></i>
    <h3>No Active Complaints</h3>
    <p>All complaints have been addressed or no new complaints have been submitted.</p>
</div>
<?php endif; ?>
</div>

<!-- ================= HISTORY ================= -->
<div class="section">
<h3><i class="fas fa-history"></i> Response History</h3>

<form class="search-box" method="get">
    <input type="text" name="search_history"
           placeholder="Search by student name, ID, or subject"
           value="<?= htmlspecialchars($searchHistory) ?>">
    <button type="submit">
        <i class="fas fa-search"></i> Search
    </button>
</form>

<?php if (mysqli_num_rows($historyComplaints) > 0): ?>
<table>
<tr>
    <th>ID</th>
    <th>Student</th>
    <th>Subject</th>
    <th>Status</th>
    <th>Responded By</th>
    <th>Date</th>
    <th>Action</th>
</tr>

<?php while ($h = mysqli_fetch_assoc($historyComplaints)): ?>
<tr>
<td>#<?= $h['complaint_id'] ?></td>
<td>
    <?= htmlspecialchars($h['student_name']) ?>
    <?php if($h['is_anonymous'] == '1'): ?>
        <span class="anonymous-icon" title="Anonymous Submission">
            <i class="fas fa-user-secret"></i>
        </span>
    <?php endif; ?>
</td>
<td><?= htmlspecialchars($h['subject']) ?></td>
<td><span class="badge responded"><?= $h['status'] ?></span></td>
<td><?= htmlspecialchars($h['responder_name'] ?? 'N/A') ?></td>
<td><?= date("d M Y", strtotime($h['response_date'])) ?></td>
<td>
    <a class="btn view"
       href="department_view_complaint.php?id=<?= $h['complaint_id'] ?>">
        <i class="fas fa-eye"></i> View
    </a>
</td>
</tr>
<?php endwhile; ?>
</table>
<?php else: ?>
<div class="empty-state">
    <i class="fas fa-clock"></i>
    <h3>No Response History</h3>
    <p>No complaints have been responded to yet.</p>
</div>
<?php endif; ?>
</div>

</div>
</body>
</html>