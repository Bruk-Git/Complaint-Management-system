<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['dean_name'])) {
    header("Location: dean_login.php");
    exit();
}

$dean_name = $_SESSION['dean_name'];

/* SEARCH */
$search = "";
$whereSearch = "";

if (isset($_GET['search']) && $_GET['search'] !== "") {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $whereSearch = "
        AND (
            student_name LIKE '%$search%' OR
            student_id LIKE '%$search%' OR
            subject LIKE '%$search%'
        )
    ";
}

/* ACTIVE COMPLAINTS (Assigned to Dean) */
$active = mysqli_query($conn, "
    SELECT * FROM complain
    WHERE status='Assigned to Dean'
    $whereSearch
    ORDER BY assigned_date DESC
");

/* HISTORY (Responded / Resolved) */
$history = mysqli_query($conn, "
    SELECT * FROM complain
    WHERE status IN ('Responded','Resolved')
    AND responder_name='$dean_name'
    ORDER BY response_date DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="images/Logos/AU Logo.png">
<title>Dean Dashboard</title>

<style>
/* Reset and Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
    min-height: 100vh;
    color: #333;
    line-height: 1.6;
}

/* Header */
.header {
    background: linear-gradient(135deg, #141e30 0%, #243b55 100%);
    color: white;
    padding: 20px 40px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    position: sticky;
    top: 0;
    z-index: 1000;
    animation: headerSlide 0.5s ease-out;
}

@keyframes headerSlide {
    from {
        transform: translateY(-100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.log-img {
    height: 50px;
    width: auto;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
    transition: transform 0.3s ease;
}

.log-img:hover {
    transform: scale(1.05);
}

.header h2 {
    font-size: 1.8rem;
    font-weight: 600;
    text-align: center;
    flex-grow: 1;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    letter-spacing: 0.5px;
    color: #fff;
}

.header > div {
    display: flex;
    align-items: center;
    gap: 15px;
    font-weight: 500;
    color: #e0e0e0;
}

/* Buttons */
.btn {
    padding: 10px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.logout {
    background: #e74c3c;
    color: white;
    box-shadow: 0 4px 6px rgba(231, 76, 60, 0.3);
}

.logout:hover {
    background: #c0392b;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(231, 76, 60, 0.4);
}

.view {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    color: white;
    font-size: 0.9rem;
    padding: 8px 16px;
    box-shadow: 0 4px 6px rgba(52, 152, 219, 0.3);
}

.view:hover {
    background: linear-gradient(135deg, #2980b9 0%, #1f6396 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(52, 152, 219, 0.4);
}

/* Container */
.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 30px;
    animation: fadeIn 0.8s ease-out;
}

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

/* Box Containers */
.box {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.box:nth-child(2) {
    animation-delay: 0.1s;
}

.box:nth-child(3) {
    animation-delay: 0.2s;
}

/* Headings */
h3 {
    color: #2c3e50;
    margin-bottom: 25px;
    font-size: 1.5rem;
    font-weight: 600;
    position: relative;
    padding-bottom: 12px;
    border-bottom: 2px solid #f0f0f0;
}

h3::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 100px;
    height: 2px;
    background: linear-gradient(90deg, #3498db, #2c3e50);
    border-radius: 1px;
}

/* Search Box */
.search-box {
    display: flex;
    gap: 15px;
    margin-bottom: 10px;
}

.search-box input {
    flex-grow: 1;
    padding: 14px 20px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
    color: #333;
}

.search-box input:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

.search-box button {
    background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
    color: white;
    border: none;
    padding: 14px 30px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 120px;
}

.search-box button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(52, 152, 219, 0.3);
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-top: 20px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

th {
    background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
    color: white;
    padding: 18px 15px;
    text-align: left;
    font-weight: 600;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: relative;
    overflow: hidden;
}

th::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
}

td {
    padding: 16px 15px;
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.3s ease;
}

tr:last-child td {
    border-bottom: none;
}

tr:hover td {
    background: #f8f9fa;
    transform: scale(1.002);
}

/* Status Cells */
td.status {
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.85rem;
    padding: 8px 16px;
    border-radius: 20px;
    display: inline-block;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Status Colors */
.status[data-status="Pending"] {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.status[data-status="On Review"],
.status[data-status="Under Review"] {
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.status[data-status="Assigned to Teacher"],
.status[data-status="Assigned to Dean"] {
    background: #cce5ff;
    color: #004085;
    border: 1px solid #b8daff;
}

.status[data-status="Dean Responded"],
.status[data-status="Responded"] {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.status[data-status="Resolved"] {
    background: #d6d8d9;
    color: #383d41;
    border: 1px solid #c6c8ca;
}

/* Student Info */
small {
    color: #666;
    font-size: 0.85rem;
    display: block;
    margin-top: 4px;
    opacity: 0.8;
}

/* Empty State */
td[colspan] {
    text-align: center;
    color: #95a5a6;
    padding: 40px !important;
    font-style: italic;
    background: #f8f9fa;
}

/* Action Column */
td:last-child {
    text-align: center;
}

/* Responsive Design */
@media (max-width: 768px) {
    .header {
        flex-direction: column;
        gap: 15px;
        padding: 20px;
        text-align: center;
    }
    
    .log-img {
        height: 40px;
    }
    
    .header h2 {
        font-size: 1.5rem;
        order: -1;
    }
    
    .container {
        padding: 15px;
    }
    
    .box {
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .search-box {
        flex-direction: column;
    }
    
    .search-box input,
    .search-box button {
        width: 100%;
    }
    
    table {
        display: block;
        overflow-x: auto;
    }
    
    th, td {
        white-space: nowrap;
        min-width: 120px;
        padding: 12px 10px;
    }
    
    .btn {
        padding: 8px 16px;
        font-size: 0.9rem;
    }
}

/* Tablet Styles */
@media (min-width: 769px) and (max-width: 1024px) {
    .container {
        padding: 20px;
    }
    
    .box {
        padding: 25px;
    }
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
    body {
        background: linear-gradient(135deg, #0a1929 0%, #14202e 50%, #1a2837 100%);
    }
    
    .header {
        background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%);
    }
    
    .box {
        background: rgba(26, 32, 44, 0.95);
        color: #e0e0e0;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    h3 {
        color: #e0e0e0;
        border-color: #2d3748;
    }
    
    h3::after {
        background: linear-gradient(90deg, #3498db, #e0e0e0);
    }
    
    .search-box input {
        background: #2d3748;
        border-color: #4a5568;
        color: #e0e0e0;
    }
    
    .search-box input:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
    }
    
    table {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }
    
    th {
        background: linear-gradient(135deg, #1e272e 0%, #2d3436 100%);
    }
    
    td {
        border-color: #2d3748;
        color: #e0e0e0;
    }
    
    tr:hover td {
        background: #2d3748;
    }
    
    small {
        color: #b0b0b0;
    }
    
    td[colspan] {
        background: #2d3748;
        color: #95a5a6;
    }
    
    .status[data-status="Pending"] {
        background: #856404;
        color: #fff3cd;
        border-color: #ffeaa7;
    }
    
    .status[data-status="On Review"],
    .status[data-status="Under Review"] {
        background: #0c5460;
        color: #d1ecf1;
        border-color: #bee5eb;
    }
    
    .status[data-status="Assigned to Teacher"],
    .status[data-status="Assigned to Dean"] {
        background: #004085;
        color: #cce5ff;
        border-color: #b8daff;
    }
    
    .status[data-status="Dean Responded"],
    .status[data-status="Responded"] {
        background: #155724;
        color: #d4edda;
        border-color: #c3e6cb;
    }
    
    .status[data-status="Resolved"] {
        background: #383d41;
        color: #d6d8d9;
        border-color: #c6c8ca;
    }
}

/* Accessibility */
.btn:focus,
.search-box input:focus,
.search-box button:focus {
    outline: 3px solid #3498db;
    outline-offset: 2px;
}

/* Reduced Motion */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
    
    .btn:hover,
    tr:hover td,
    .search-box button:hover {
        transform: none;
    }
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 10px;
    height: 10px;
}

::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 5px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
    border-radius: 5px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
}

/* Print Styles */
@media print {
    body {
        background: white !important;
        color: black !important;
    }
    
    .header,
    .search-box {
        display: none;
    }
    
    .box {
        box-shadow: none;
        border: 1px solid #ddd;
        margin: 10px 0;
    }
    
    table {
        box-shadow: none;
    }
}

/* Loading Animation */
@keyframes shimmer {
    0% {
        background-position: -468px 0;
    }
    100% {
        background-position: 468px 0;
    }
}

.loading {
    background: linear-gradient(to right, #f6f7f8 0%, #edeef1 20%, #f6f7f8 40%, #f6f7f8 100%);
    background-size: 800px 104px;
    animation-duration: 1s;
    animation-fill-mode: forwards;
    animation-iteration-count: infinite;
    animation-name: shimmer;
    animation-timing-function: linear;
}

@media (prefers-color-scheme: dark) {
    .loading {
        background: linear-gradient(to right, #2d3748 0%, #4a5568 20%, #2d3748 40%, #2d3748 100%);
    }
}
</style>
</head>

<body>

<div class="header">
    <img class="log-img" src="images/logos/AU Logo.png">
    <h2>Dean Dashboard</h2>
    <div>
        <?= htmlspecialchars($dean_name) ?> |
        <a href="dean_login.php" class="btn logout">Logout</a>
    </div>
</div>

<div class="container">

<!-- SEARCH -->
<div class="box">
<form method="get" class="search-box">
    <input type="text" name="search" placeholder="Search complaint..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
</form>
</div>

<!-- ACTIVE COMPLAINTS -->
<div class="box">
<h3>Assigned Complaints</h3>

<table>
<tr>
    <th>ID</th>
    <th>Student</th>
    <th>Department</th>
    <th>Subject</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php if (mysqli_num_rows($active) > 0) {
    while ($c = mysqli_fetch_assoc($active)) { ?>
<tr>
    <td>#<?= $c['complaint_id'] ?></td>
    <td>
        <?= htmlspecialchars($c['student_name']) ?><br>
        <small>ID: <?= $c['student_id'] ?></small>
    </td>
    <td><?= htmlspecialchars($c['department']) ?></td>
    <td><?= htmlspecialchars($c['subject']) ?></td>
    <td class="status"><?= $c['status'] ?></td>
    <td>
        <a class="btn view"
           href="dean_view_response.php?id=<?= $c['complaint_id'] ?>">
           View & Respond
        </a>
    </td>
</tr>
<?php } } else { ?>
<tr>
    <td colspan="6" style="text-align:center;color:gray;">
        No complaints assigned to Dean.
    </td>
</tr>
<?php } ?>
</table>
</div>

<!-- HISTORY -->
<div class="box">
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
    <td>#<?= $h['complaint_id'] ?></td>
    <td><?= htmlspecialchars($h['student_name']) ?></td>
    <td><?= htmlspecialchars($h['subject']) ?></td>
    <td class="status"><?= $h['status'] ?></td>
    <td><?= date("d M Y", strtotime($h['response_date'])) ?></td>
    <td>
        <a class="btn view"
           href="view_response.php?id=<?= $h['complaint_id'] ?>">
           View Response
        </a>
    </td>
</tr>
<?php } } else { ?>
<tr>
    <td colspan="6" style="text-align:center;color:gray;">
        No response history yet.
    </td>
</tr>
<?php } ?>
</table>
</div>

</div>
</body>
</html>
