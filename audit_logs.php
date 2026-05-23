<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$search = "";
$where  = "";

if (!empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where = "
        AND (
            student_name LIKE '%$search%' OR
            student_id LIKE '%$search%' OR
            subject LIKE '%$search%' OR
            department LIKE '%$search%' OR
            responder_name LIKE '%$search%' OR
            teacher_name LIKE '%$search%'
        )
    ";
}

/*
 Build unified audit stream from complain table
*/
$logs = mysqli_query($conn,"
    SELECT
        complaint_id AS ref_id,
        'Complaint' AS entity,
        student_name AS actor,
        'Student' AS role,
        'Submitted complaint' AS action,
        created_at AS log_date
    FROM complain

    UNION ALL

    SELECT
        complaint_id,
        'Complaint',
        teacher_name,
        'Teacher',
        'Assigned complaint',
        assigned_date
    FROM complain
    WHERE teacher_name IS NOT NULL

    UNION ALL

    SELECT
        complaint_id,
        'Complaint',
        dean_name,
        'Dean',
        'Assigned to Dean',
        assigned_date
    FROM complain
    WHERE dean_name IS NOT NULL

    UNION ALL

    SELECT
        complaint_id,
        'Complaint',
        responder_name,
        'Responder',
        CONCAT('Responded (', status, ')'),
        response_date
    FROM complain
    WHERE response_text IS NOT NULL

    ORDER BY log_date DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Audit Logs | CMS Admin</title>

<link rel="icon" href="images/Logos/AU Logo.png">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
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

.container {
    padding: 30px 40px;
    max-width: 1400px;
    margin: 0 auto;
}

/* Header */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 25px 30px;
    background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0, 59, 111, 0.15);
    color: white;
}

.header h2 {
    font-size: 28px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 15px;
    margin: 0;
}

.header h2 i {
    color: #ffc107;
    background: rgba(255, 255, 255, 0.1);
    padding: 12px;
    border-radius: 12px;
    font-size: 24px;
}

.header a {
    color: white;
    text-decoration: none;
    font-weight: 600;
    padding: 12px 24px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 8px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.header a:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 255, 255, 0.1);
}

.header a::before {
    content: '←';
    font-weight: bold;
}

/* Search Box */
.search-box {
    background: white;
    padding: 25px 30px;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    margin-bottom: 30px;
    border: 1px solid rgba(0, 0, 0, 0.05);
    display: flex;
    gap: 20px;
    align-items: center;
    flex-wrap: wrap;
}

.search-box form {
    display: flex;
    gap: 12px;
    flex: 1;
    min-width: 300px;
}

.search-box input {
    flex: 1;
    padding: 14px 20px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 15px;
    transition: all 0.3s ease;
    background: #f8fafc;
}

.search-box input:focus {
    outline: none;
    border-color: #003b6f;
    box-shadow: 0 0 0 3px rgba(0, 59, 111, 0.1);
    background: white;
}

.search-box button {
    padding: 14px 28px;
    background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.search-box button:hover {
    background: linear-gradient(135deg, #005fa3 0%, #007acc 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 95, 163, 0.3);
}

/* Export Button */
.btn-export {
    padding: 14px 28px;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.btn-export:hover {
    background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
}

/* Table Container */
.table-box {
    background: white;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
    overflow: hidden;
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
    background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
}

th {
    padding: 18px 20px;
    color:black;
    font-weight: 600;
    text-align: left;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: none;
    background: #005fa3;
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

/* Role Badges */
.role {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    min-width: 100px;
}

.role:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
}

.role.Student { 
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
}

.role.Teacher { 
    background: linear-gradient(135deg, #0078d4 0%, #005fa3 100%);
    color: white;
}

.role.Dean { 
    background: linear-gradient(135deg, #6f42c1 0%, #8a63d2 100%);
    color: white;
}

.role.Responder { 
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

/* Reference styling */
td:nth-child(5) {
    color: #003b6f;
    font-weight: 600;
}

/* Date styling */
td:nth-child(6) {
    color: #666;
    font-family: 'Courier New', monospace;
    font-size: 13px;
    background: #f8fafc;
    border-radius: 6px;
    padding: 10px 15px;
}

/* Empty state */
td[colspan] {
    text-align: center;
    padding: 40px;
    color: #666;
    font-style: italic;
    background: #f8fafc;
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

/* Responsive Design */
@media (max-width: 1024px) {
    .container {
        padding: 20px 30px;
    }
    
    .search-box {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-box form {
        min-width: auto;
    }
    
    .btn-export {
        align-self: flex-start;
    }
}

@media (max-width: 768px) {
    .container {
        padding: 15px 20px;
    }
    
    .header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
        padding: 20px;
    }
    
    .header h2 {
        font-size: 24px;
    }
    
    .table-box {
        padding: 20px;
    }
    
    table {
        display: block;
        overflow-x: auto;
    }
    
    th, td {
        padding: 15px 10px;
        font-size: 13px;
        min-width: 120px;
    }
    
    .role {
        padding: 6px 12px;
        font-size: 11px;
        min-width: 80px;
    }
    
    .search-box {
        padding: 20px;
    }
    
    .search-box input,
    .search-box button,
    .btn-export {
        padding: 12px 20px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 10px 15px;
    }
    
    .header h2 {
        font-size: 20px;
    }
    
    .header a {
        padding: 10px 20px;
        font-size: 14px;
    }
    
    .search-box {
        padding: 15px;
    }
    
    th, td {
        padding: 12px 8px;
        font-size: 12px;
    }
    
    .role {
        padding: 5px 10px;
        font-size: 10px;
        min-width: 70px;
    }
    
    td:nth-child(6) {
        font-size: 11px;
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
    background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #005fa3 0%, #003b6f 100%);
}
</style>
</head>

<body>

<div class="container">

<div class="header">
    <link rel="icon" href="images/Logos/AU Logo.png">
    <h2><i class="fas fa-clipboard-list"></i> System Audit Logs</h2>
    <a href="admin_dashboard.php">← Back</a>
</div>

<div class="search-box">
    <form method="get">
        <input type="text"
               name="search"
               placeholder="Search student, teacher, department..."
               value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
        <a href="export_audit_logs.php" class="btn-export">
    📥 Export Logs
</a>

    </form>
</div>

<div class="table-box">
<table>
<tr>
    <th>#</th>
    <th>Actor</th>
    <th>Role</th>
    <th>Action</th>
    <th>Reference</th>
    <th>Date</th>
</tr>

<?php if (mysqli_num_rows($logs) > 0): ?>
<?php while ($log = mysqli_fetch_assoc($logs)): ?>
<tr>
    <td><?= $log['ref_id'] ?></td>

    <td><?= htmlspecialchars($log['actor']) ?></td>

    <td class="role <?= $log['role'] ?>">
        <?= $log['role'] ?>
    </td>

    <td><?= htmlspecialchars($log['action']) ?></td>

    <td>
        <?= $log['entity'] ?> #<?= $log['ref_id'] ?>
    </td>

    <td>
        <?= $log['log_date']
            ? date("d M Y h:i A", strtotime($log['log_date']))
            : '—'
        ?>
    </td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="6" style="text-align:center;color:gray;">
        No activity found
    </td>
</tr>
<?php endif; ?>

</table>
</div>

</div>

</body>
</html>
