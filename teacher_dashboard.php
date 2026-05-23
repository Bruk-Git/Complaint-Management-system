<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php");
    exit();
}

$teacher_id   = $_SESSION['teacher_id'];
$teacher_name = $_SESSION['teacher_name'];

/* SEARCH */
$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

/* RECENT ASSIGNED (ACTIVE) */
$recent = mysqli_query($conn,"
    SELECT *
    FROM complain
    WHERE teacher_id='$teacher_id'
      AND status IN ('Assigned to Teacher','On Review')
      AND (
        subject LIKE '%$search%' OR
        student_name LIKE '%$search%' OR
        complaint_id LIKE '%$search%'
      )
    ORDER BY assigned_date DESC
");

/* HISTORY (RESPONDED / RESOLVED) */
$history = mysqli_query($conn,"
    SELECT *
    FROM complain
    WHERE teacher_id='$teacher_id'
      AND status IN ('Responded','Resolved')
    ORDER BY response_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="images/Logos/AU Logo.png">
<title>Teacher Dashboard</title>

<style>
/* Reset and Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: linear-gradient(135deg, #eef3f7 0%, #f8fafc 100%);
    color: #333;
    line-height: 1.6;
    padding-top: 80px;
    min-height: 100vh;
}

/* Fixed Header */
.header {
    background: linear-gradient(135deg, #003b6f 0%, #00509e 100%);
    color: white;
    padding: 15px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    box-shadow: 0 4px 20px rgba(0, 58, 111, 0.2);
    height: 80px;
}

.header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, #4da6ff, #80c1ff, #4da6ff);
}

.log-img {
    width: 50px;
    height: auto;
    filter: brightness(0) invert(1);
    transition: transform 0.3s ease;
}

.log-img:hover {
    transform: scale(1.05);
}

.header h2 {
    font-size: 1.8rem;
    font-weight: 600;
    color: white;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
}

.header > div {
    display: flex;
    align-items: center;
    gap: 20px;
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.9);
}

/* Main Container */
.container {
    padding: 30px;
    max-width: 1400px;
    margin: 0 auto;
}

/* Sections */
.section {
    background: white;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 30px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid #e1e8f0;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.section:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.section h3 {
    color: #003b6f;
    font-size: 1.5rem;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e1e8f0;
    position: relative;
}

.section h3::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 100px;
    height: 2px;
    background: #00509e;
}

/* Search Box */
.search-box {
    margin-bottom: 25px;
    display: flex;
    gap: 10px;
}

.search-box input {
    padding: 12px 20px;
    width: 300px;
    border: 2px solid #e1e8f0;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f8fafc;
}

.search-box input:focus {
    outline: none;
    border-color: #00509e;
    box-shadow: 0 0 0 3px rgba(0, 80, 158, 0.1);
    background: white;
}

.search-box button {
    padding: 12px 24px;
    background: linear-gradient(135deg, #00509e 0%, #003b6f 100%);
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.search-box button:hover {
    background: linear-gradient(135deg, #0066cc 0%, #004a99 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 80, 158, 0.3);
}

/* Tables */
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

th {
    background: linear-gradient(135deg, #003b6f 0%, #00509e 100%);
    color: white;
    padding: 16px 12px;
    font-weight: 600;
    text-align: left;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

td {
    padding: 16px 12px;
    border-bottom: 1px solid #e1e8f0;
    background: white;
    transition: background 0.2s ease;
}

tr:last-child td {
    border-bottom: none;
}

tr:hover td {
    background: #f8fafc;
}

/* Status Colors */
td:nth-child(4) { /* Status column */
    font-weight: 600;
}

td:nth-child(4):contains('Assigned to Teacher'),
td:nth-child(4):contains('On Review') {
    color: #e67e22;
}

td:nth-child(4):contains('Responded') {
    color: #3498db;
}

td:nth-child(4):contains('Resolved') {
    color: #27ae60;
}

/* Buttons */
.btn {
    padding: 10px 20px;
    border-radius: 6px;
    color: white;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 600;
    display: inline-block;
    text-align: center;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    min-width: 140px;
}

.view {
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
    box-shadow: 0 2px 8px rgba(39, 174, 96, 0.2);
}

.view:hover {
    background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
}

.logout {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    padding: 10px 24px;
    box-shadow: 0 2px 8px rgba(231, 76, 60, 0.2);
    position: relative;
    overflow: hidden;
}

.logout:hover {
    background: linear-gradient(135deg, #ff4d4d 0%, #e74c3c 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
}

/* Disable browser navigation on logout click */
.logout {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    -webkit-tap-highlight-color: transparent;
}

.logout:active,
.logout:focus {
    outline: none;
    -webkit-tap-highlight-color: transparent;
}

/* Empty states */
tr td[colspan] {
    text-align: center;
    color: #7f8c8d;
    padding: 40px;
    font-style: italic;
    background: #f8fafc;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .container {
        padding: 20px;
    }
    
    .search-box input {
        width: 250px;
    }
}

@media (max-width: 768px) {
    body {
        padding-top: 70px;
    }
    
    .header {
        padding: 12px 20px;
        height: 70px;
    }
    
    .header h2 {
        font-size: 1.4rem;
    }
    
    .log-img {
        width: 40px;
    }
    
    .container {
        padding: 15px;
    }
    
    .section {
        padding: 20px;
    }
    
    .search-box {
        flex-direction: column;
    }
    
    .search-box input {
        width: 100%;
    }
    
    table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
    
    th, td {
        min-width: 120px;
    }
    
    .btn {
        min-width: 120px;
        padding: 8px 16px;
    }
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.section {
    animation: fadeIn 0.5s ease-out;
}

/* Loading skeleton for better UX */
.skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
    border-radius: 4px;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Scrollbar styling */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #00509e;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #003b6f;
}
</style>
</head>

<body>

<div class="header">
    <img class="log-img" src="images/logos/AU Logo.png" alt="AU Logo">
    <h2>Teacher Dashboard</h2>
    <div>
        <?= htmlspecialchars($teacher_name); ?> |
        <!-- In your header section, replace the logout link with: -->
<form method="POST" action="teacher_logout.php" style="display: inline;">
    <button type="submit" class="btn logout" onclick="return confirm('Are you sure you want to logout?')">
        Logout
    </button>
</form>
    </div>
</div>

<div class="container">

<!-- ================= RECENT ASSIGNED ================= -->
<div class="section">
<h3>Assigned Complaints</h3>

<form method="get" class="search-box">
    <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
</form>

<table>
<tr>
    <th>ID</th>
    <th>Student</th>
    <th>Subject</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php if (mysqli_num_rows($recent) > 0) {
while ($r = mysqli_fetch_assoc($recent)) { ?>
<tr>
<td>#<?= $r['complaint_id']; ?></td>

<td>
<?= htmlspecialchars($r['student_name']); ?><br>
<small>ID: <?= $r['student_id']; ?></small>
</td>

<td><?= htmlspecialchars($r['subject']); ?></td>
<td><?= $r['status']; ?></td>

<td>
<a class="btn view"
   href="teacher_response.php?id=<?= $r['complaint_id']; ?>">
   View & Respond
</a>
</td>
</tr>
<?php }} else { ?>
<tr><td colspan="5" style="text-align:center;">No assigned complaints</td></tr>
<?php } ?>
</table>
</div>

<!-- ================= HISTORY ================= -->
<div class="section">
<h3>Response History</h3>

<table>
<tr>
    <th>ID</th>
    <th>Student</th>
    <th>Subject</th>
    <th>Status</th>
    <th>Response Date</th>
    <th>Action</th>
</tr>

<?php if (mysqli_num_rows($history) > 0) {
while ($h = mysqli_fetch_assoc($history)) { ?>
<tr>
<td>#<?= $h['complaint_id']; ?></td>

<td><?= htmlspecialchars($h['student_name']); ?></td>
<td><?= htmlspecialchars($h['subject']); ?></td>
<td><?= $h['status']; ?></td>
<td><?= date("d M Y", strtotime($h['response_date'])); ?></td>

<td>
<a class="btn view"
   href="view_response.php?id=<?= $h['complaint_id']; ?>">
   View Response
</a>
</td>
</tr>
<?php }} else { ?>
<tr><td colspan="6" style="text-align:center;">No history yet</td></tr>
<?php } ?>
</table>
</div>

</div>
</body>
</html>
