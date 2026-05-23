<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$search = $_GET['search'] ?? '';
$search = mysqli_real_escape_string($conn, $search);

/* Fetch teachers */
$teachers = mysqli_query($conn, "
    SELECT *
    FROM teacher_login
    WHERE teacher_name LIKE '%$search%'
       OR email LIKE '%$search%'
    ORDER BY id DESC
");

/* Stats */
$totalTeachers = mysqli_fetch_row(
    mysqli_query($conn,"SELECT COUNT(*) FROM teacher_login")
)[0];

$activeTeachers = mysqli_fetch_row(
    mysqli_query($conn,"SELECT COUNT(*) FROM teacher_login WHERE status='active'")
)[0];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Manage Teachers | CMS Admin</title>
<link rel="icon" href="images/Logos/AU Logo.png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
    background: white;
    padding: 25px 30px;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
}

.header h2 {
    font-size: 28px;
    font-weight: 700;
    color: #003b6f;
    display: flex;
    align-items: center;
    gap: 15px;
    margin: 0;
}

.header h2 i {
    color: #ffc107;
    background: rgba(255, 193, 7, 0.1);
    padding: 12px;
    border-radius: 12px;
    font-size: 24px;
}

/* STATS SECTION */
.stats {
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

.stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    flex: 1;
    min-width: 200px;
    padding: 20px;
    background: #f8fafd;
    border-radius: 12px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.stat:hover {
    border-color: #003b6f;
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 59, 111, 0.1);
}

.stat h4 {
    color: #666;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
}

.stat h2 {
    font-size: 36px;
    font-weight: 800;
    color: #003b6f;
    margin: 0;
    line-height: 1;
}

.stat .actions.add {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 15px 25px;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 15px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    white-space: nowrap;
}

.stat .actions.add:hover {
    background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
}

/* SEARCH BOX */
.search-box {
    background: white;
    padding: 25px 30px;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    margin-bottom: 30px;
    border: 1px solid rgba(0, 0, 0, 0.05);
    display: flex;
    gap: 15px;
    align-items: center;
}

.search-box input {
    flex: 1;
    padding: 16px 20px;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
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
    padding: 16px 35px;
    background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 15px;
}

.search-box button:hover {
    background: linear-gradient(135deg, #005fa3 0%, #007acc 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 95, 163, 0.3);
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
    color: #003b6f;
    font-weight: 600;
    text-align: left;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: relative;
    border-bottom: none;
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
    padding: 20px;
    color: #555;
    font-size: 14px;
    font-weight: 500;
    vertical-align: middle;
}

/* STATUS BADGES */
.status {
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
}

.status:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
}

.status.active { 
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.status.inactive { 
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
}

/* ACTION BUTTONS */
.actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.actions a {
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
    white-space: nowrap;
}

.actions a:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
}

.actions a.edit {
    background: linear-gradient(135deg, #0078d4 0%, #005fa3 100%);
}

.actions a.view {
    background: linear-gradient(135deg, #17a2b8 0%, #0dcaf0 100%);
}

.actions a.delete {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
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

/* RESPONSIVE DESIGN FOR MAIN CONTENT */
@media (max-width: 1024px) {
    .container {
        margin-left: 220px;
        padding: 20px;
    }
    
    .stat {
        min-width: 180px;
    }
    
    .stat h2 {
        font-size: 32px;
    }
}

@media (max-width: 768px) {
    .container {
        margin-left: 0;
        padding: 15px;
    }
    
    .header {
        padding: 20px;
        flex-direction: column;
        text-align: center;
    }
    
    .stats {
        flex-direction: column;
        gap: 15px;
    }
    
    .stat {
        width: 100%;
        min-width: unset;
    }
    
    .search-box {
        flex-direction: column;
    }
    
    .search-box input {
        width: 100%;
    }
    
    .search-box button {
        width: 100%;
    }
    
    .actions {
        flex-direction: column;
        gap: 5px;
    }
    
    .actions a {
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
    .header h2 {
        font-size: 24px;
    }
    
    .header h2 i {
        padding: 10px;
        font-size: 20px;
    }
    
    .stat h4 {
        font-size: 12px;
    }
    
    .stat h2 {
        font-size: 28px;
    }
    
    .stat .actions.add {
        padding: 12px 20px;
        font-size: 14px;
    }
    
    .search-box {
        padding: 20px;
    }
    
    .search-box input {
        padding: 14px;
    }
    
    .search-box button {
        padding: 14px 25px;
    }
    
    .status {
        padding: 8px 12px;
        font-size: 11px;
    }
    
    .actions a {
        padding: 12px 20px;
        font-size: 14px;
    }
    
    .table-container {
        padding: 20px;
    }
}
</style>
</head>

<body>

<div class="sidebar" id="sidebar">
    <h3><i class="fas fa-shield-alt"></i> CMS Admin</h3>
    <a href="admin_dashboard.php"><i class="fas fa-chart-pie"></i> Overview</a>
    <a href="user_management.php" class="active"><i class="fas fa-users"></i> User Management</a>
    <a href="complaint_management.php"><i class="fas fa-file-alt"></i> Complaint Management</a>
    <a href="reports.php"><i class="fas fa-chart-line"></i> Reports & Logs</a>
    <a href="admin_login.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="container">

<div class="header">
    <h2><i class="fas fa-chalkboard-teacher"></i> Manage Teachers</h2>
    <button class="menu-btn" onclick="toggleMenu()"><i class="fas fa-bars"></i></button>
</div>

<div class="stats">
    <div class="stat">
        <h4>Total Teachers</h4>
        <h2><?= $totalTeachers ?></h2>
    </div>
    <div class="stat">
        <h4>Active Teachers</h4>
        <h2><?= $activeTeachers ?></h2>
    </div>
    <div class="stat">
        <a href="teacher_register.php" class="actions add" style="background:#28a745;">+ Add Teacher</a>
    </div>
</div>

<form class="search-box" method="get">
    <input type="text" name="search" placeholder="Search name or email" value="<?= htmlspecialchars($search) ?>">
    <button>Search</button>
</form>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
<th>Phone</th>
<th>Department</th>
<th>Status</th>
    <th>Assigned</th>
    <th>Responded</th>
    <th>Action</th>
</tr>

<?php while($t = mysqli_fetch_assoc($teachers)):

    $tid = $t['id'];

    $assigned = mysqli_fetch_row(mysqli_query($conn,"
        SELECT COUNT(*) FROM complain
        WHERE teacher_id='$tid'
          AND status IN ('Assigned to Teacher','On Review')
    "))[0];

    $responded = mysqli_fetch_row(mysqli_query($conn,"
        SELECT COUNT(*) FROM complain
        WHERE teacher_id='$tid'
          AND status IN ('Responded','Resolved')
    "))[0];
?>

<tr>
    <td><?= $tid ?></td>
    <td><?= htmlspecialchars($t['teacher_name']) ?></td>
    <td><?= htmlspecialchars($t['email']) ?></td>
<td><?= htmlspecialchars($t['phone']) ?></td>
<td><?= htmlspecialchars($t['department']) ?></td>

   <td>
    <a 
        href="toggle_teacher_status.php?id=<?= $tid ?>"
        class="status <?= $t['status']=='active'?'active':'inactive' ?>"
        onclick="return confirm('Change account status for this teacher?')"
    >
        <?= $t['status']=='active' ? 'Active (Click to Deactivate)' : 'Inactive (Click to Activate)' ?>
    </a>
</td>


    <td><?= $assigned ?></td>
    <td><?= $responded ?></td>
    <td class="actions">
        <a class="edit" href="admin_edit_teacher.php?id=<?= $tid ?>">Edit</a>
        <a class="view" href="admin_teacher_complaints.php?id=<?= $tid ?>">Complaints</a>
        <a class="delete"
           onclick="return confirm('Delete this teacher?')"
           href="delete_teacher.php?id=<?= $tid ?>">Delete</a>
    </td>
</tr>

<?php endwhile; ?>

</table>

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
    const rows = document.querySelectorAll('#teachersTable tbody tr');
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

// Confirmation for delete actions
function confirmDelete(id, teacherName) {
    return confirm(`Are you sure you want to delete teacher "${teacherName}"?\n\nThis will permanently remove the teacher and all associated data.`);
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
        totalCount: <?= $totalTeachers ?>,
        activeCount: <?= $activeTeachers ?>,
        assignedCount: <?= $assignedTeachers ?>
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
document.querySelectorAll('#teachersTable tbody tr').forEach(row => {
    row.addEventListener('mouseenter', function() {
        this.style.transform = 'translateX(8px)';
    });
    
    row.addEventListener('mouseleave', function() {
        this.style.transform = 'translateX(0)';
    });
});

// Stats counter hover effects
document.querySelectorAll('.stats-counter').forEach(counter => {
    counter.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px)';
        this.style.boxShadow = '0 4px 15px rgba(0, 95, 163, 0.2)';
    });
    
    counter.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = 'none';
    });
});
</script>


</body>
</html>
