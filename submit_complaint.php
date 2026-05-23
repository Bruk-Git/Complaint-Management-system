<?php
session_start();

/* ---------- DATABASE CONNECTION ---------- */
$conn = new mysqli("localhost", "root", "", "cms");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* ---------- HANDLE ANONYMOUS SUBMISSION ---------- */
$is_anonymous = isset($_POST['is_anonymous']) ? $_POST['is_anonymous'] : '0';

if ($is_anonymous == '1') {
    // Anonymous submission
    $student_name = "Anonymous Student";
    $student_id = "ANONYMOUS-" . time();
    $email = "anonymous@university.edu";
    $phone = "N/A";
} else {
    // Regular submission
    $student_name = $_POST['student_name'] ?? '';
    $student_id   = $_POST['student_id'] ?? '';
    $email        = $_POST['email'] ?? '';
    $phone        = $_POST['phone'] ?? '';
}

/* ---------- OTHER FORM DATA ---------- */
$department     = $_POST['department'] ?? '';
$program        = $_POST['program'] ?? '';
$academic_year  = $_POST['academic_year'] ?? '';
$subject        = $_POST['subject'] ?? '';
$complaint_text = $_POST['complaint_text'] ?? '';

$status = "Pending";
$file_name = NULL;

/* ---------- VALIDATE REQUIRED FIELDS ---------- */
$required_fields = [
    'department' => 'Department',
    'academic_year' => 'Academic Year',
    'subject' => 'Subject',
    'complaint_text' => 'Complaint Text'
];

foreach ($required_fields as $field => $name) {
    if (empty($$field)) {
        echo "<script>
            alert('Please fill in the $name field.');
            window.history.back();
        </script>";
        exit();
    }
}

/* ---------- FILE UPLOAD VALIDATION ---------- */
if (!empty($_FILES['file_path']['name'])) {
    $allowed_types = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];
    
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
    $max_size = 5 * 1024 * 1024; // 5MB

    // Check file size
    if ($_FILES['file_path']['size'] > $max_size) {
        echo "<script>
            alert('File too large. Maximum size is 5MB.');
            window.history.back();
        </script>";
        exit();
    }

    // Check file type by MIME type
    $file_type = $_FILES['file_path']['type'];
    if (!in_array($file_type, $allowed_types)) {
        echo "<script>
            alert('Invalid file type. Only JPG, PNG, GIF, PDF, DOC, and DOCX are allowed.');
            window.history.back();
        </script>";
        exit();
    }

    // Also check by file extension
    $file_extension = strtolower(pathinfo($_FILES['file_path']['name'], PATHINFO_EXTENSION));
    if (!in_array($file_extension, $allowed_extensions)) {
        echo "<script>
            alert('Invalid file extension. Only JPG, PNG, GIF, PDF, DOC, and DOCX are allowed.');
            window.history.back();
        </script>";
        exit();
    }

    $upload_dir = "complaint_files/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Generate unique file name
    $file_name = time() . "_" . uniqid() . "." . $file_extension;
    $target_file = $upload_dir . $file_name;

    if (!move_uploaded_file($_FILES['file_path']['tmp_name'], $target_file)) {
        echo "<script>
            alert('File upload failed. Please try again.');
            window.history.back();
        </script>";
        exit();
    }
}

/* ---------- INSERT INTO DATABASE ---------- */
$stmt = $conn->prepare("
    INSERT INTO complain (
        student_name,
        student_id,
        department,
        program,
        academic_year,
        email,
        phone,
        subject,
        complaint_text,
        file_attachment,
        is_anonymous,
        status,
        created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
");

$stmt->bind_param(
    "ssssssssssss",
    $student_name,
    $student_id,
    $department,
    $program,
    $academic_year,
    $email,
    $phone,
    $subject,
    $complaint_text,
    $file_name,
    $is_anonymous,
    $status
);

if ($stmt->execute()) {
    $complaint_id = $stmt->insert_id;
    
    // Show success message with complaint ID
    if ($is_anonymous == '1') {
        echo "<script>
            alert('Complaint submitted anonymously successfully!\\\\nYour Complaint ID is: #$complaint_id\\\\nKeep this ID to track your complaint.');
            window.location.href = 'student_dashboard.php';
        </script>";
    } else {
        echo "<script>
            alert('Complaint submitted successfully!\\\\nYour Complaint ID is: #$complaint_id');
            window.location.href = 'student_dashboard.php';
        </script>";
    }
} else {
    // Delete uploaded file if database insertion failed
    if ($file_name && file_exists($upload_dir . $file_name)) {
        unlink($upload_dir . $file_name);
    }
    
    echo "<script>
        alert('Error: " . addslashes($conn->error) . "\\\\nPlease try again.');
        window.history.back();
    </script>";
}

$stmt->close();
$conn->close();
?>