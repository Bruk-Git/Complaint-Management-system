<?php
session_start();
include "Connection.php";

/* Protect page */
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

/* Fetch student counts by department */
$departments = mysqli_query($conn,"
    SELECT department, COUNT(*) AS total_students
    FROM register_table
    GROUP BY department
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/Logos/AU Logo.png">
    <title>Student Management | CMS Admin</title>
    
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

    .header-actions {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    /* Container */
    .container {
        padding: 35px;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Section Title */
    .section-title {
        margin-bottom: 30px;
        color: #003b6f;
        font-size: 24px;
        font-weight: 700;
        padding-bottom: 15px;
        border-bottom: 3px solid rgba(0, 59, 111, 0.1);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-title i {
        color: #ffc107;
    }

    /* Grid */
    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
        margin-top: 20px;
    }

    /* Card */
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
        background: linear-gradient(90deg, #003b6f, #005fa3, #007acc);
        opacity: 0.8;
    }

    .card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .card h3 {
        margin: 0 0 15px 0;
        color: #003b6f;
        font-size: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card h3 i {
        color: #ffc107;
        background: rgba(255, 193, 7, 0.1);
        padding: 8px;
        border-radius: 8px;
        font-size: 18px;
    }

    /* Count */
    .count {
        font-size: 48px;
        font-weight: 800;
        margin: 15px 0;
        color: #003b6f;
        background: linear-gradient(90deg, #003b6f, #005fa3);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1;
        transition: all 0.3s ease;
    }

    .card:hover .count {
        transform: scale(1.1);
    }

    .card p {
        color: #666;
        font-size: 15px;
        line-height: 1.6;
        margin-bottom: 25px;
        min-height: 48px;
        padding-left: 5px;
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
        background: linear-gradient(135deg, #005fa3 0%, #007acc 100%);
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 95, 163, 0.3);
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn i {
        font-size: 14px;
    }

    /* Special Button Styles */
    .add-btn {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .add-btn:hover {
        background: linear-gradient(135deg, #20c997 0%, #198754 100%);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
    }

    .back-btn {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    }

    .back-btn:hover {
        background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
        box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
    }

    /* Stats Overview */
    .stats-overview {
        background: white;
        padding: 25px 30px;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        border: 1px solid rgba(0, 0, 0, 0.03);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .total-students {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .total-students-icon {
        background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
        color: white;
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .total-students-info h4 {
        color: #666;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .total-students-info .total-count {
        font-size: 36px;
        font-weight: 800;
        color: #003b6f;
        line-height: 1;
    }

    /* Search and Filter */
    .search-filter {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .search-box {
        flex: 1;
        min-width: 300px;
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 15px 20px 15px 50px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
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

    /* Department-specific colors for icons */
    .card[data-dept*="Computer"] h3 i { color: #4CAF50; }
    .card[data-dept*="Information"] h3 i { color: #2196F3; }
    .card[data-dept*="Electrical"] h3 i { color: #FF9800; }
    .card[data-dept*="Mechanical"] h3 i { color: #9C27B0; }
    .card[data-dept*="Business"] h3 i { color: #E91E63; }
    .card[data-dept*="Accounting"] h3 i { color: #00BCD4; }
    .card[data-dept*="Science"] h3 i { color: #8BC34A; }
    .card[data-dept*="Health"] h3 i { color: #F44336; }

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

    /* Responsive Design */
    @media (max-width: 768px) {
        .header {
            flex-direction: column;
            gap: 20px;
            text-align: center;
            padding: 20px;
        }
        
        .header-actions {
            flex-direction: column;
            width: 100%;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
        
        .container {
            padding: 20px;
        }
        
        .grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .stats-overview {
            flex-direction: column;
            text-align: center;
            gap: 25px;
        }
        
        .search-filter {
            flex-direction: column;
        }
        
        .search-box {
            min-width: 100%;
        }
    }

    @media (max-width: 480px) {
        .grid {
            grid-template-columns: 1fr;
        }
        
        .card {
            padding: 25px;
        }
        
        .count {
            font-size: 40px;
        }
        
        .header h2 {
            font-size: 24px;
        }
    }
    </style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <h2>
        <i class="fas fa-user-graduate"></i> Student Management
    </h2>
    <div class="header-actions">
        <a class="btn add-btn" href="register_student.php">
            <i class="fas fa-user-plus"></i> Add New Student
        </a>
        <a class="btn back-btn" href="user_management.php">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>
</div>

<div class="container">

    <!-- Search and Filter -->
    <div class="search-filter">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Search departments or students...">
        </div>
        <select class="btn" style="padding: 15px 20px; background: white; color: #333; border: 2px solid #e0e0e0;">
            <option value="">All Departments</option>
            <option value="cs">Computer Science</option>
            <option value="it">Information Technology</option>
            <option value="ee">Electrical Engineering</option>
            <option value="me">Mechanical Engineering</option>
        </select>
    </div>

    <!-- Stats Overview -->
    <?php
    $totalStudents = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM register_table"))[0];
    $activeStudents = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM register_table WHERE status='active'"))[0];
    ?>
    <div class="stats-overview">
        <div class="total-students">
            <div class="total-students-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="total-students-info">
                <h4>Total Students</h4>
                <div class="total-count" id="totalStudents"><?= $totalStudents ?></div>
            </div>
        </div>
        <div>
            <h4>Active Students</h4>
            <div style="font-size: 28px; font-weight: 700; color: #28a745;">
                <?= $activeStudents ?> <span style="font-size: 14px; color: #666;">/ <?= $totalStudents ?></span>
            </div>
        </div>
        <div>
            <a class="btn" href="export_students.php" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%);">
                <i class="fas fa-file-export"></i> Export Data
            </a>
        </div>
    </div>

    <h3 class="section-title">
        <i class="fas fa-building"></i> Students by Department
    </h3>

    <div class="grid" id="departmentsGrid">

    <?php 
    $deptCount = 0;
    while ($row = mysqli_fetch_assoc($departments)) { 
        $deptCount++;
        $deptClass = strtolower(str_replace(' ', '-', $row['department']));
    ?>

        <div class="card" data-dept="<?= htmlspecialchars($row['department']) ?>">
            <h3>
                <i class="fas 
                    <?= 
                    strpos($row['department'], 'Computer') !== false ? 'fa-laptop-code' :
                    (strpos($row['department'], 'Information') !== false ? 'fa-database' :
                    (strpos($row['department'], 'Electrical') !== false ? 'fa-bolt' :
                    (strpos($row['department'], 'Mechanical') !== false ? 'fa-cogs' :
                    (strpos($row['department'], 'Business') !== false ? 'fa-briefcase' :
                    (strpos($row['department'], 'Accounting') !== false ? 'fa-calculator' :
                    (strpos($row['department'], 'Science') !== false ? 'fa-flask' :
                    (strpos($row['department'], 'Health') !== false ? 'fa-heartbeat' : 'fa-university'))))))) 
                    ?>">
                </i>
                <?= htmlspecialchars($row['department']) ?>
            </h3>

            <div class="count" id="count<?= $deptCount ?>">
                <?= $row['total_students'] ?>
            </div>

            <p>Registered students in this department with complaint tracking and academic support.</p>

            <a class="btn" href="admin_view_students.php?department=<?= urlencode($row['department']) ?>">
                <i class="fas fa-eye"></i> View Students
            </a>
        </div>

    <?php } ?>

    </div>

</div>

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const cards = document.querySelectorAll('.card');
    
    cards.forEach(card => {
        const deptName = card.querySelector('h3').textContent.toLowerCase();
        const count = card.querySelector('.count').textContent;
        const description = card.querySelector('p').textContent.toLowerCase();
        
        if (deptName.includes(searchTerm) || 
            count.includes(searchTerm) || 
            description.includes(searchTerm)) {
            card.style.display = 'block';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 50);
        } else {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.display = 'none';
            }, 300);
        }
    });
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

// Animate total students counter
document.addEventListener('DOMContentLoaded', function() {
    const totalStudents = document.getElementById('totalStudents');
    if (totalStudents) {
        const target = parseInt(totalStudents.textContent.replace(/,/g, ''));
        if (!isNaN(target)) {
            animateCounter(totalStudents, target, 1500);
        }
    }
    
    // Animate department counts
    const countElements = document.querySelectorAll('.count');
    countElements.forEach((element, index) => {
        const target = parseInt(element.textContent.replace(/,/g, ''));
        if (!isNaN(target)) {
            setTimeout(() => {
                animateCounter(element, target, 1200);
            }, index * 200);
        }
    });
});

// Card hover enhancement
document.querySelectorAll('.card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        const countElement = this.querySelector('.count');
        countElement.style.transform = 'scale(1.1)';
    });
    
    card.addEventListener('mouseleave', function() {
        const countElement = this.querySelector('.count');
        countElement.style.transform = 'scale(1)';
    });
});
</script>
</body>
</html>