<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$teacher_id = $_GET['id'];

$teacher = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT teacher_name FROM teacher_login WHERE id='$teacher_id'")
);

$complaints = mysqli_query($conn, "
    SELECT 
        complaint_id,
        student_name,
        subject,
        complaint_text,
        status,
        assigned_date,
        response_date,
        response_text,
        created_at
    FROM complain
    WHERE teacher_id = '$teacher_id'
    ORDER BY 
        CASE 
            WHEN status = 'Pending' THEN 1
            WHEN status = 'On Review' THEN 2
            WHEN status = 'Assigned to Teacher' THEN 3
            WHEN status = 'Responded' THEN 4
            WHEN status = 'Resolved' THEN 5
            ELSE 6
        END,
        assigned_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="images/Logos/AU Logo.png">
<title>Teacher Complaints</title>
<style>
/* Reset and Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #e6f0ff 0%, #f0f7ff 100%);
    min-height: 100vh;
    color: #2c3e50;
    line-height: 1.6;
    padding: 40px 20px;
}

/* Animated Background */
body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: 
        radial-gradient(circle at 20% 80%, rgba(77, 166, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 152, 0, 0.08) 0%, transparent 50%);
    z-index: -1;
    animation: floatBackground 20s ease-in-out infinite;
}

@keyframes floatBackground {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(30px, -20px) scale(1.05); }
    66% { transform: translate(-20px, 30px) scale(0.95); }
}

/* Main Container */
.container {
    max-width: 1200px;
    margin: 0 auto;
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 20px 60px rgba(77, 166, 255, 0.15);
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(77, 166, 255, 0.1);
    animation: containerAppear 0.6s ease-out;
}

@keyframes containerAppear {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Top Gradient Border */
.container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: linear-gradient(90deg, #ff9800, #ff8c00, #ffa500);
    animation: gradientMove 3s ease infinite;
}

@keyframes gradientMove {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Teacher Emoji Decoration */
.container::after {
    content: '👨‍🏫';
    position: absolute;
    top: 25px;
    right: 25px;
    font-size: 60px;
    opacity: 0.1;
    z-index: 0;
    animation: teacherFloat 6s ease-in-out infinite;
}

@keyframes teacherFloat {
    0%, 100% { transform: translate(0, 0) rotate(0deg); }
    33% { transform: translate(5px, -10px) rotate(5deg); }
    66% { transform: translate(-5px, 5px) rotate(-3deg); }
}

/* Heading */
h2 {
    color: #003b6f;
    font-size: 32px;
    margin-bottom: 35px;
    position: relative;
    padding-bottom: 15px;
    font-weight: 700;
    text-align: center;
    letter-spacing: 0.5px;
    animation: headingSlide 0.6s ease-out 0.2s both;
}

@keyframes headingSlide {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 150px;
    height: 4px;
    background: linear-gradient(90deg, #ff9800, #ff8c00);
    border-radius: 2px;
    animation: lineExpand 1s ease-out 0.5s both;
}

@keyframes lineExpand {
    0% { width: 0; }
    100% { width: 150px; }
}

/* Teacher Name Highlight */
h2 span {
    color: #ff9800;
    font-weight: 800;
    text-shadow: 2px 2px 4px rgba(255, 152, 0, 0.1);
    position: relative;
    display: inline-block;
    animation: namePulse 2s infinite;
}

@keyframes namePulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.02); }
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 15px 40px rgba(255, 152, 0, 0.1);
    margin-bottom: 35px;
    animation: tableSlide 0.6s ease-out 0.4s both;
}

@keyframes tableSlide {
    from { opacity: 0; transform: translateX(-30px); }
    to { opacity: 1; transform: translateX(0); }
}

/* Table Header */
th {
    background: linear-gradient(135deg, #00c3ffff, #00c3ffff);
    color: white;
    padding: 20px 15px;
    text-align: left;
    font-weight: 600;
    font-size: 16px;
    text-transform: uppercase;
    letter-spacing: 1px;
    position: relative;
    overflow: hidden;
    border-bottom: 2px solid #ffa500;
}

th::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    animation: shine 3s infinite;
}

@keyframes shine {
    0% { left: -100%; }
    100% { left: 100%; }
}

/* Table Cells */
td {
    padding: 18px 15px;
    border-bottom: 1px solid rgba(255, 152, 0, 0.1);
    transition: all 0.3s ease;
    font-size: 16px;
    color: #2c3e50;
}

tr:last-child td {
    border-bottom: none;
}

/* Row Hover Effect */
tr:hover td {
    background: rgba(255, 152, 0, 0.05);
    transform: translateX(10px);
    box-shadow: inset 10px 0 0 rgba(255, 152, 0, 0.2);
}

/* Complaint ID Styling */
td:nth-child(1) {
    font-weight: 600;
    color: #003b6f;
    font-family: 'Courier New', monospace;
    font-size: 15px;
}

/* Subject Styling */
td:nth-child(2) {
    font-weight: 500;
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Status Styling */
td:nth-child(3) {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 14px;
    letter-spacing: 0.5px;
}

/* Badge Styles */
.badge {
    padding: 8px 18px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-block;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    animation: badgeAppear 0.5s ease-out;
}

@keyframes badgeAppear {
    from { transform: scale(0.8); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

/* Responded Badge */
.badge.done {
    background: linear-gradient(135deg, #28a745, #1e7e34);
    color: white;
    animation: pulseGreen 2s infinite;
}

@keyframes pulseGreen {
    0%, 100% { box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3); }
    50% { box-shadow: 0 4px 20px rgba(40, 167, 69, 0.5); }
}

.badge.done:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
}

/* No Response Badge */
.badge.wait {
    background: linear-gradient(135deg, #ff4d4d, #ff3333);
    color: white;
    animation: pulseRed 2s infinite;
}

@keyframes pulseRed {
    0%, 100% { box-shadow: 0 4px 15px rgba(255, 77, 77, 0.3); }
    50% { box-shadow: 0 4px 20px rgba(255, 77, 77, 0.5); }
}

.badge.wait:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 25px rgba(255, 77, 77, 0.4);
}

/* Badge Shine Effect */
.badge::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.6s ease;
}

.badge:hover::before {
    left: 100%;
}

/* Empty State */
tr:only-child td[colspan="4"] {
    text-align: center;
    color: #5a6c7d;
    font-style: italic;
    padding: 60px !important;
    font-size: 18px;
    background: rgba(255, 152, 0, 0.05);
    animation: fadeIn 1s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Back Link */
a[href="manage_teachers.php"] {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    color: #ff9800;
    text-decoration: none;
    font-weight: 600;
    font-size: 17px;
    padding: 14px 32px;
    border-radius: 25px;
    background: rgba(255, 152, 0, 0.1);
    transition: all 0.3s ease;
    border: 2px solid rgba(255, 152, 0, 0.2);
    animation: backAppear 0.6s ease-out 0.6s both;
    position: relative;
    overflow: hidden;
}

@keyframes backAppear {
    from { opacity: 0; transform: translateX(-30px); }
    to { opacity: 1; transform: translateX(0); }
}

a[href="manage_teachers.php"]:hover {
    color: white;
    background: linear-gradient(135deg, #ff9800, #ff8c00);
    transform: translateX(-8px);
    box-shadow: 0 10px 25px rgba(255, 152, 0, 0.3);
    border-color: rgba(255, 255, 255, 0.3);
}

a[href="manage_teachers.php"]:active {
    transform: translateX(-5px);
}

a[href="manage_teachers.php"]::before {
    content: '←';
    font-size: 20px;
    font-weight: bold;
}

a[href="manage_teachers.php"]::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.6s ease;
}

a[href="manage_teachers.php"]:hover::after {
    left: 100%;
}

/* Responsive Design */
@media (max-width: 992px) {
    .container {
        padding: 30px;
    }
    
    h2 {
        font-size: 28px;
    }
    
    table {
        display: block;
        overflow-x: auto;
    }
}

@media (max-width: 768px) {
    body {
        padding: 20px 15px;
    }
    
    .container {
        padding: 25px 20px;
        border-radius: 18px;
    }
    
    h2 {
        font-size: 24px;
        margin-bottom: 25px;
    }
    
    h2::after {
        width: 120px;
    }
    
    th, td {
        padding: 15px 12px;
        font-size: 15px;
    }
    
    .badge {
        padding: 6px 14px;
        font-size: 13px;
    }
    
    a[href="manage_teachers.php"] {
        padding: 12px 25px;
        font-size: 16px;
    }
    
    .container::after {
        font-size: 50px;
        top: 20px;
        right: 20px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 20px 15px;
    }
    
    h2 {
        font-size: 20px;
    }
    
    th {
        font-size: 14px;
        padding: 12px 10px;
    }
    
    td {
        padding: 12px 10px;
        font-size: 14px;
    }
    
    .badge {
        padding: 5px 12px;
        font-size: 12px;
    }
    
    a[href="manage_teachers.php"] {
        width: 100%;
        justify-content: center;
        text-align: center;
    }
    
    .container::after {
        display: none;
    }
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 10px;
    height: 10px;
}

::-webkit-scrollbar-track {
    background: #e6f0ff;
    border-radius: 5px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #ff9800, #ff8c00);
    border-radius: 5px;
    border: 2px solid #e6f0ff;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #ff8c00, #ff9800);
}

/* Status Text Colors */
td:nth-child(3):contains("pending"),
td:nth-child(3):contains("Pending") {
    color: #ff9800;
    font-weight: 600;
}

td:nth-child(3):contains("resolved"),
td:nth-child(3):contains("Resolved") {
    color: #28a745;
    font-weight: 600;
}

td:nth-child(3):contains("assigned"),
td:nth-child(3):contains("Assigned") {
    color: #4da6ff;
    font-weight: 600;
}

/* Focus States for Accessibility */
a:focus-visible,
.badge:focus-visible {
    outline: 3px solid #ff9800;
    outline-offset: 2px;
}

/* Loading Animation for AJAX */
.loading {
    position: relative;
    color: transparent !important;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255, 152, 0, 0.3);
    border-top-color: #ff9800;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    transform: translate(-50%, -50%);
}

@keyframes spin {
    to { transform: translate(-50%, -50%) rotate(360deg); }
}
</style>
</head>

<body>

<h2>Complaints Assigned to <?= $teacher['teacher_name']; ?></h2>

<table>
<tr>
    <th>ID</th>
    <th>Subject</th>
    <th>Status</th>
    <th>Response</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($complaints)) { ?>
<tr>
    <td><?= $row['complaint_id']; ?></td>
    <td><?= $row['subject']; ?></td>
    <td><?= $row['status']; ?></td>
    <td>
        <?php if ($row['response_text']) { ?>
            <span class="badge done">Responded</span>
        <?php } else { ?>
            <span class="badge wait">No Response</span>
        <?php } ?>
    </td>
</tr>
<?php } ?>

</table>

<br>
<a href="manage_teachers.php">← Back</a>

</body>
</html>
