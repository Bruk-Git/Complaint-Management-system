<?php
session_start();
include "Connection.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Get student ID from URL
$student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch student data
$student = null;
if ($student_id > 0) {
    $query = "SELECT * FROM register_table WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();
}

// If student not found, redirect
if (!$student) {
    header("Location: manage_students.php?error=notfound");
    exit();
}

// Fetch departments for dropdown
$departments = mysqli_query($conn, "
    SELECT DISTINCT department FROM register_table 
    WHERE department IS NOT NULL AND department != ''
    ORDER BY department
");

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic information
    $student_id_num = trim($_POST['student_id']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $mobile_no = trim($_POST['mobile_no']);
    
    // Academic information
    $department = trim($_POST['department']);
    $program = $_POST['program'];
    $study_mode = $_POST['study_mode'];
    $year_level = trim($_POST['year_level']);
    
    // Status
    $status = $_POST['status'];
    
    // Validate inputs
    $errors = [];
    
    // Required fields validation
    if (empty($student_id_num)) $errors[] = "Student ID is required";
    if (empty($first_name)) $errors[] = "First name is required";
    if (empty($last_name)) $errors[] = "Last name is required";
    if (empty($email)) $errors[] = "Email is required";
    if (empty($department)) $errors[] = "Department is required";
    if (empty($program)) $errors[] = "Program is required";
    if (empty($study_mode)) $errors[] = "Study mode is required";
    
    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    // If no errors, update student
    if (empty($errors)) {
        $update_query = "UPDATE register_table SET 
                student_id = ?,
                first_name = ?,
                last_name = ?,
                email = ?,
                mobile_no = ?,
                department = ?,
                program = ?,
                study_mode = ?,
                year_level = ?,
                status = ?
                WHERE id = ?";

        
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssssssssssi", 
            $student_id_num,
            $first_name,
            $last_name,
            $email,
            $mobile_no,
            $department,
            $program,
            $study_mode,
            $year_level,
            $status,
            $student_id
        );
        
        if ($stmt->execute()) {
            $success = "Student updated successfully!";
            // Refresh student data
            $refresh_query = "SELECT * FROM register_table WHERE id = ?";
            $stmt = $conn->prepare($refresh_query);
            $stmt->bind_param("i", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $student = $result->fetch_assoc();
            $stmt->close();
        } else {
            $errors[] = "Failed to update student. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/Logos/AU Logo.png">
    <title>Edit Student | CMS Admin</title>
    
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

        .header {
            background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
            color: white;
            padding: 20px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(0, 59, 111, 0.2);
        }

        .header h2 {
            font-size: 24px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header h2 i {
            color: #ffc107;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn.back {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        }

        .btn.back:hover {
            background: linear-gradient(135deg, #495057 0%, #343a40 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .edit-box {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 91, 163, 0.1);
        }

        .student-header {
            display: flex;
            align-items: center;
            gap: 25px;
            margin-bottom: 30px;
            padding-bottom: 25px;
            border-bottom: 2px solid #f0f4f8;
        }

        .student-icon {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 36px;
            box-shadow: 0 8px 25px rgba(0, 95, 163, 0.2);
        }

        .student-info h3 {
            color: #003b6f;
            font-size: 26px;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .student-info p {
            color: #666;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .student-info span {
            background: rgba(0, 91, 163, 0.1);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: #005fa3;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .form-section {
            margin-bottom: 35px;
        }

        .form-section h4 {
            color: #003b6f;
            font-size: 18px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9f2ff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-section h4 i {
            color: #005fa3;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #003b6f;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            height: 50px;
            padding: 0 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            color: #333;
            background: #f8fafc;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: #003b6f;
            background: white;
            box-shadow: 0 0 0 3px rgba(0, 59, 111, 0.1);
        }

        select.form-control {
            cursor: pointer;
        }

        .program-options {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }

        .program-option {
            flex: 1;
        }

        .program-option input[type="radio"] {
            display: none;
        }

        .program-option label {
            display: block;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        .program-option input[type="radio"]:checked + label {
            border-color: #003b6f;
            background: rgba(0, 59, 111, 0.1);
            color: #003b6f;
        }

        .btn-submit {
            width: 100%;
            height: 52px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #20c997 0%, #1abc9c 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        }

        .btn-submit:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-weight: 500;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert ul {
            margin: 10px 0 0 20px;
        }

        .alert li {
            margin-bottom: 5px;
        }

        .footer-links {
            display: flex;
            justify-content: space-between;
            margin-top: 35px;
            padding-top: 25px;
            border-top: 1px solid #e9ecef;
        }

        .footer-links a {
            color: #005fa3;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            background: rgba(0, 95, 163, 0.1);
            color: #003b6f;
        }

        .footer-links a i {
            font-size: 14px;
        }

        .delete-link {
            color: #dc3545 !important;
        }

        .delete-link:hover {
            background: rgba(220, 53, 69, 0.1) !important;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 15px;
            }
            
            .edit-box {
                padding: 25px;
            }
            
            .student-header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
                padding: 20px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .program-options {
                flex-direction: column;
            }
            
            .footer-links {
                flex-direction: column;
                gap: 15px;
            }
        }

        @media (max-width: 480px) {
            .edit-box {
                padding: 20px;
            }
            
            .student-icon {
                width: 70px;
                height: 70px;
                font-size: 28px;
            }
            
            .student-info h3 {
                font-size: 22px;
            }
            
            .form-control {
                height: 45px;
                font-size: 14px;
            }
            
            .btn-submit {
                height: 48px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>

<div class="header">
    <h2><i class="fas fa-user-edit"></i> Edit Student</h2>
    <a href="manage_students.php" class="btn back">
        <i class="fas fa-arrow-left"></i> Back to Students
    </a>
</div>

<div class="container">
    <div class="edit-box">
        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?= $success ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-error">
                <strong><i class="fas fa-exclamation-triangle"></i> Please fix the following errors:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="student-header">
            <div class="student-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="student-info">
                <h3><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></h3>
                <p>
                    <span>ID: <?= htmlspecialchars($student['student_id']) ?></span>
                    <span>Program: <?= htmlspecialchars($student['program']) ?></span>
                    <span>Created: <?= date('M d, Y', strtotime($student['created_at'])) ?></span>
                </p>
            </div>
        </div>

        <form method="POST" id="editStudentForm">
            <div class="form-section">
                <h4><i class="fas fa-user"></i> Basic Information</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="student_id">Student ID</label>
                        <input type="text" 
                               id="student_id" 
                               name="student_id" 
                               class="form-control" 
                               value="<?= htmlspecialchars($student['student_id']) ?>" 
                               required
                               >
                    </div>
                    
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" 
                               id="first_name" 
                               name="first_name" 
                               class="form-control" 
                               value="<?= htmlspecialchars($student['first_name']) ?>" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" 
                               id="last_name" 
                               name="last_name" 
                               class="form-control" 
                               value="<?= htmlspecialchars($student['last_name']) ?>" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-control" 
                               value="<?= htmlspecialchars($student['email']) ?>" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="mobile_no">Mobile Number</label>
                        <input type="tel" 
                               id="mobile_no" 
                               name="mobile_no" 
                               class="form-control" 
                               value="<?= htmlspecialchars($student['mobile_no']) ?>"
                               placeholder="+251 XXX XXX XXX">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h4><i class="fas fa-graduation-cap"></i> Academic Information</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select id="department" name="department" class="form-control" required>
                            <option value="">Select Department</option>
                            <?php mysqli_data_seek($departments, 0); ?>
                            <?php while ($dept = mysqli_fetch_assoc($departments)): ?>
                                <option value="<?= htmlspecialchars($dept['department']) ?>" 
                                    <?= ($student['department'] == $dept['department']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($dept['department']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="year_level">Year Level</label>
                        <input type="text" 
                               id="year_level" 
                               name="year_level" 
                               class="form-control" 
                               value="<?= htmlspecialchars($student['year_level']) ?>"
                               placeholder="e.g., 1st Year, 2nd Year">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Program Type</label>
                    <div class="program-options">
                        <div class="program-option">
                            <input type="radio" 
                                   id="program_tvet" 
                                   name="program" 
                                   value="TVET"
                                   <?= ($student['program'] == 'TVET') ? 'checked' : '' ?>>
                            <label for="program_tvet">TVET</label>
                        </div>
                        <div class="program-option">
                            <input type="radio" 
                                   id="program_degree" 
                                   name="program" 
                                   value="DEGREE"
                                   <?= ($student['program'] == 'DEGREE') ? 'checked' : '' ?>>
                            <label for="program_degree">DEGREE</label>
                        </div>
                        <div class="program-option">
                            <input type="radio" 
                                   id="program_masters" 
                                   name="program" 
                                   value="MASTERS"
                                   <?= ($student['program'] == 'MASTERS') ? 'checked' : '' ?>>
                            <label for="program_masters">MASTERS</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Study Mode</label>
                    <div class="program-options">
                        <div class="program-option">
                            <input type="radio" 
                                   id="mode_regular" 
                                   name="study_mode" 
                                   value="REGULAR"
                                   <?= ($student['study_mode'] == 'REGULAR') ? 'checked' : '' ?>>
                            <label for="mode_regular">REGULAR</label>
                        </div>
                        <div class="program-option">
                            <input type="radio" 
                                   id="mode_extension" 
                                   name="study_mode" 
                                   value="EXTENSION"
                                   <?= ($student['study_mode'] == 'EXTENSION') ? 'checked' : '' ?>>
                            <label for="mode_extension">EXTENSION</label>
                        </div>
                        <div class="program-option">
                            <input type="radio" 
                                   id="mode_distance" 
                                   name="study_mode" 
                                   value="DISTANCE"
                                   <?= ($student['study_mode'] == 'DISTANCE') ? 'checked' : '' ?>>
                            <label for="mode_distance">DISTANCE</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h4><i class="fas fa-cog"></i> Account Settings</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="status">Account Status</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="active" <?= ($student['status'] == 'active') ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= ($student['status'] == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                <i class="fas fa-save"></i> Update Student Information
            </button>
        </form>

        <div class="footer-links">
            <a href="manage_students.php">
                <i class="fas fa-undo"></i> Cancel Changes
            </a>
            <a href="delete_student.php?id=<?= $student['id'] ?>" 
               class="delete-link"
               onclick="return confirm('Are you sure you want to delete this student? This action cannot be undone.')">
                <i class="fas fa-trash"></i> Delete Student
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editStudentForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Program selection visual feedback
    const programOptions = document.querySelectorAll('input[name="program"]');
    const studyModeOptions = document.querySelectorAll('input[name="study_mode"]');
    
    // Add visual feedback when program is selected
    programOptions.forEach(option => {
        option.addEventListener('change', function() {
            programOptions.forEach(opt => {
                const label = opt.nextElementSibling;
                if (opt.checked) {
                    label.style.borderColor = '#003b6f';
                    label.style.background = 'rgba(0, 59, 111, 0.1)';
                    label.style.color = '#003b6f';
                } else {
                    label.style.borderColor = '#e0e0e0';
                    label.style.background = 'transparent';
                    label.style.color = '#333';
                }
            });
        });
    });
    
    // Add visual feedback when study mode is selected
    studyModeOptions.forEach(option => {
        option.addEventListener('change', function() {
            studyModeOptions.forEach(opt => {
                const label = opt.nextElementSibling;
                if (opt.checked) {
                    label.style.borderColor = '#003b6f';
                    label.style.background = 'rgba(0, 59, 111, 0.1)';
                    label.style.color = '#003b6f';
                } else {
                    label.style.borderColor = '#e0e0e0';
                    label.style.background = 'transparent';
                    label.style.color = '#333';
                }
            });
        });
    });
    
    // Initialize visual feedback
    programOptions.forEach(opt => {
        if (opt.checked) {
            const label = opt.nextElementSibling;
            label.style.borderColor = '#003b6f';
            label.style.background = 'rgba(0, 59, 111, 0.1)';
            label.style.color = '#003b6f';
        }
    });
    
    studyModeOptions.forEach(opt => {
        if (opt.checked) {
            const label = opt.nextElementSibling;
            label.style.borderColor = '#003b6f';
            label.style.background = 'rgba(0, 59, 111, 0.1)';
            label.style.color = '#003b6f';
        }
    });
    
    // Form validation
    form.addEventListener('submit', function(e) {
        // Validate student ID format
        const studentId = document.getElementById('student_id').value;
        
        
        if (!studentIdPattern.test(studentId)) {
            e.preventDefault();
            alert('Student ID can only contain letters and numbers (no spaces or special characters)');
            document.getElementById('student_id').focus();
            return;
        }
        
        // Validate email format
        const email = document.getElementById('email').value;
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!emailPattern.test(email)) {
            e.preventDefault();
            alert('Please enter a valid email address');
            document.getElementById('email').focus();
            return;
        }
        
        // Check that a program is selected
        const programSelected = document.querySelector('input[name="program"]:checked');
        if (!programSelected) {
            e.preventDefault();
            alert('Please select a program type');
            return;
        }
        
        // Check that a study mode is selected
        const studyModeSelected = document.querySelector('input[name="study_mode"]:checked');
        if (!studyModeSelected) {
            e.preventDefault();
            alert('Please select a study mode');
            return;
        }
        
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        submitBtn.disabled = true;
    });
    
    // Real-time validation for student ID
    const studentIdInput = document.getElementById('student_id');
    studentIdInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
    
    // Real-time validation for names (capitalize first letter)
    const firstNameInput = document.getElementById('first_name');
    const lastNameInput = document.getElementById('last_name');
    
    function capitalizeName(input) {
        if (input.value.length > 0) {
            input.value = input.value.charAt(0).toUpperCase() + input.value.slice(1).toLowerCase();
        }
    }
    
    firstNameInput.addEventListener('blur', () => capitalizeName(firstNameInput));
    lastNameInput.addEventListener('blur', () => capitalizeName(lastNameInput));
});
</script>
</body>
</html>