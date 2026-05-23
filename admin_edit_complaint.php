<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$id = intval($_GET['id']);
$q = mysqli_query($conn,"SELECT * FROM complain WHERE complaint_id='$id'");
$c = mysqli_fetch_assoc($q);

if (!$c) die("Complaint not found");

if ($_SERVER['REQUEST_METHOD']=='POST') {
    $status = $_POST['status'];
    $teacher = $_POST['teacher_name'];
    $dean = $_POST['dean_name'];

    mysqli_query($conn,"
        UPDATE complain SET
        status='$status',
        teacher_name='$teacher',
        dean_name='$dean'
        WHERE complaint_id='$id'
    ");

    header("Location: admin_view_complaint.php?id=$id");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="images/Logos/AU Logo.png">
    <title>Edit Complaint | Admin Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e9ecef 100%);
            min-height: 100vh;
            color: #333;
        }

        .admin-header {
            background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
            color: white;
            padding: 20px 30px;
            box-shadow: 0 4px 12px rgba(0, 59, 111, 0.2);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-icon {
            font-size: 24px;
            background: rgba(255, 255, 255, 0.1);
            padding: 10px;
            border-radius: 8px;
        }

        .header-left h1 {
            font-size: 22px;
            font-weight: 600;
        }

        .complaint-id {
            font-family: monospace;
            background: rgba(255, 255, 255, 0.15);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 14px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.15);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-1px);
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 30px;
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 30px;
        }

        .sidebar {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f4f8;
        }

        .sidebar-icon {
            color: #005fa3;
            font-size: 20px;
        }

        .sidebar h3 {
            color: #003b6f;
            font-size: 18px;
            font-weight: 600;
        }

        .id-display {
            background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 25px;
        }

        .id-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .id-value {
            font-family: monospace;
            font-size: 24px;
            font-weight: 700;
        }

        .info-section {
            margin-bottom: 25px;
        }

        .info-section h4 {
            color: #005fa3;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-section h4::before {
            content: "▶";
            font-size: 10px;
        }

        .info-item {
            margin-bottom: 12px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .info-item::before {
            content: "•";
            color: #6c757d;
            margin-top: 2px;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            color: #6c757d;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 14px;
            color: #495057;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-Pending { background: #fff3cd; color: #856404; }
        .status-Review { background: #cce5ff; color: #004085; }
        .status-Assigned { background: #d1ecf1; color: #0c5460; }
        .status-Responded { background: #d4edda; color: #155724; }
        .status-Resolved { background: #e2e3e5; color: #383d41; }

        .edit-form {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .form-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f4f8;
        }

        .form-header::before {
            content: "✏️";
            font-size: 28px;
        }

        .form-header h2 {
            color: #003b6f;
            font-size: 28px;
            font-weight: 700;
        }

        .form-header p {
            color: #6c757d;
            font-size: 14px;
            margin-top: 5px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #003b6f;
            font-size: 14px;
        }

        .form-group label::before {
            content: "↳ ";
            color: #005fa3;
        }

        select, input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            background: #f8fafc;
            transition: all 0.2s ease;
        }

        select:focus, input:focus {
            outline: none;
            border-color: #003b6f;
            background: white;
            box-shadow: 0 0 0 3px rgba(0, 59, 111, 0.1);
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23003b6f' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 16px;
            padding-right: 45px;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 40px;
            padding-top: 25px;
            border-top: 1px solid #e9ecef;
        }

        .btn {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #20c997 0%, #1abc9c 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #495057 0%, #343a40 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.2);
        }

        .btn:active {
            transform: translateY(0);
        }

        @media (max-width: 1024px) {
            .container {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .header-left {
                flex-direction: column;
                gap: 10px;
            }
            
            .edit-form {
                padding: 30px 20px;
            }
            
            .form-header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .form-header h2 {
                font-size: 24px;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .container {
                padding: 0 20px;
                margin: 20px auto;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 15px;
            }
            
            .sidebar, .edit-form {
                padding: 20px;
            }
            
            .id-value {
                font-size: 20px;
            }
            
            .btn {
                padding: 12px;
                font-size: 14px;
            }
        }

        /* Focus styles */
        select:focus, 
        input:focus,
        .btn:focus {
            outline: 2px solid #005fa3;
            outline-offset: 2px;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Print styles */
        @media print {
            .admin-header,
            .sidebar,
            .btn-secondary,
            .back-btn {
                display: none;
            }
            
            .edit-form {
                box-shadow: none;
                border: 1px solid #ddd;
            }
            
            .btn-primary {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="admin-header">
    <div class="header-content">
        <div class="header-left">
            <div class="header-icon">✏️</div>
            <div>
                <h1>Edit Complaint</h1>
                <div class="complaint-id">ID: #<?php echo $id; ?></div>
            </div>
        </div>
        <a href="admin_view_complaint.php?id=<?php echo $id; ?>" class="back-btn">
            ← Back to Complaint
        </a>
    </div>
</div>

<div class="container">
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-icon">📋</div>
            <h3>Complaint Details</h3>
        </div>
        
        <div class="id-display">
            <div class="id-label">Complaint ID</div>
            <div class="id-value">#<?php echo htmlspecialchars($c['complaint_id']); ?></div>
        </div>
        
        <div class="info-section">
            <h4>Student Information</h4>
            <div class="info-item">
                <div class="info-content">
                    <span class="info-label">Name</span>
                    <span class="info-value"><?php echo htmlspecialchars($c['student_name']); ?></span>
                </div>
            </div>
            <div class="info-item">
                <div class="info-content">
                    <span class="info-label">Student ID</span>
                    <span class="info-value"><?php echo htmlspecialchars($c['student_id']); ?></span>
                </div>
            </div>
            <div class="info-item">
                <div class="info-content">
                    <span class="info-label">Email</span>
                    <span class="info-value"><?php echo htmlspecialchars($c['email']); ?></span>
                </div>
            </div>
        </div>
        
        <div class="info-section">
            <h4>Academic Details</h4>
            <div class="info-item">
                <div class="info-content">
                    <span class="info-label">Department</span>
                    <span class="info-value"><?php echo htmlspecialchars($c['department']); ?></span>
                </div>
            </div>
            <div class="info-item">
                <div class="info-content">
                    <span class="info-label">Program</span>
                    <span class="info-value"><?php echo htmlspecialchars($c['program']); ?></span>
                </div>
            </div>
        </div>
        
        <div class="info-section">
            <h4>Timeline</h4>
            <div class="info-item">
                <div class="info-content">
                    <span class="info-label">Created</span>
                    <span class="info-value"><?php echo date('M d, Y', strtotime($c['created_at'])); ?></span>
                </div>
            </div>
            <div class="info-item">
                <div class="info-content">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        <?php 
                        $statusClass = 'status-' . str_replace(' ', '', $c['status']);
                        echo '<span class="status-badge ' . $statusClass . '">' . htmlspecialchars($c['status']) . '</span>';
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="edit-form">
        <div class="form-header">
            <div>
                <h2>Edit Complaint Information</h2>
                <p>Update the status and assignment details for this complaint</p>
            </div>
        </div>
        
        <form method="post">
            <div class="form-group">
                <label>Complaint Status</label>
                <select name="status">
                    <?php
                    $statuses = ['Pending','On Review','Assigned to Teacher','Assigned to Dean','Responded','Resolved'];
                    foreach($statuses as $s){
                        $sel = $c['status'] == $s ? 'selected' : '';
                        echo "<option value='$s' $sel>$s</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Teacher Name</label>
                <input type="text" 
                       name="teacher_name" 
                       value="<?php echo htmlspecialchars($c['teacher_name']); ?>"
                       placeholder="Enter assigned teacher's name">
            </div>
            
            <div class="form-group">
                <label>Dean Name</label>
                <input type="text" 
                       name="dean_name" 
                       value="<?php echo htmlspecialchars($c['dean_name']); ?>"
                       placeholder="Enter dean's name for escalation">
            </div>

            <div class="form-actions">
                <a href="admin_view_complaint.php?id=<?php echo $id; ?>" class="btn btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>