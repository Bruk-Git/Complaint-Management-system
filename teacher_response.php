<?php
session_start();
include "Connection.php";

/* AUTH */
if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php");
    exit();
}

$teacher_id   = $_SESSION['teacher_id'];
$teacher_name = $_SESSION['teacher_name'];

/* VALIDATE ID */
if (!isset($_GET['id'])) {
    die("Invalid complaint");
}

$complaint_id = intval($_GET['id']);

/* FETCH COMPLAINT */
$q = mysqli_query($conn,"
    SELECT * FROM complain
    WHERE complaint_id = '$complaint_id'
");

$data = mysqli_fetch_assoc($q);
if (!$data) {
    die("Complaint not found");
}

/* HANDLE RESPONSE SUBMIT */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $response_text = trim($_POST['response_text']);
    $response_file = null;

    /* FILE UPLOAD VALIDATION */
    if (!empty($_FILES['response_file']['name'])) {

        $allowed = ['pdf','jpg','jpeg','png'];
        $maxSize = 2 * 1024 * 1024;

        $ext = strtolower(pathinfo($_FILES['response_file']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            echo "<script>alert('Only PDF, JPG, JPEG, PNG allowed');</script>";
        }
        elseif ($_FILES['response_file']['size'] > $maxSize) {
            echo "<script>alert('File must be under 2MB');</script>";
        }
        else {
            $dir = "response_files/";
            if (!is_dir($dir)) mkdir($dir, 0777, true);

            $response_file = time().'_'.$complaint_id.'.'.$ext;
            move_uploaded_file($_FILES['response_file']['tmp_name'], $dir.$response_file);
        }
    }

    /* UPDATE COMPLAINT (NO EXTRA TABLES) */
    mysqli_query($conn,"
        UPDATE complain SET
            responder_id   = '$teacher_id',
            responder_name = '$teacher_name',
            response_text  = '".mysqli_real_escape_string($conn,$response_text)."',
            response_file  = '$response_file',
            response_date  = NOW(),
            status         = 'Resolved'
        WHERE complaint_id = '$complaint_id'
    ");

    echo "<script>
        alert('Response submitted. Complaint marked as Resolved.');
        window.location='teacher_dashboard.php';
    </script>";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="images/Logos/AU Logo.png">
    <title>Teacher Review & Response | CMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .log-img {
            display: block;
            width: 80px;
            margin: 0 auto 20px;
            height: auto;
        }

        .box {
            width: 90%;
            max-width: 800px;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            margin: 30px auto;
        }

        h2 {
            color: #f5576c;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            border-bottom: 3px solid #f5576c;
            padding-bottom: 15px;
        }

        .section {
            margin: 18px 0;
            padding: 15px;
            background: #f9f9ff;
            border-radius: 8px;
            border-left: 4px solid #f5576c;
            transition: all 0.3s ease;
        }

        .section:hover {
            background: #ffeaea;
            transform: translateX(5px);
        }

        .label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 16px;
        }

        .value {
            color: #444;
            line-height: 1.6;
            padding: 10px;
            background: white;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
        }

        hr {
            border: none;
            height: 2px;
            background: linear-gradient(to right, transparent, #f5576c, transparent);
            margin: 30px 0;
        }

        .file-link {
            color: #f5576c;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            background: white;
            border: 1px solid #f5576c;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .file-link:hover {
            background: #f5576c;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 87, 108, 0.3);
        }

        .file-link::before {
            content: "📎";
            font-size: 16px;
        }

        form {
            background: #fff5f7;
            padding: 25px;
            border-radius: 10px;
            margin-top: 30px;
            border: 1px solid #ffd6dd;
        }

        textarea {
            width: 100%;
            height: 150px;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            resize: vertical;
            transition: border-color 0.3s ease;
        }

        textarea:focus {
            outline: none;
            border-color: #f5576c;
            box-shadow: 0 0 0 3px rgba(245, 87, 108, 0.1);
        }

        .file-upload-container {
            margin: 20px 0;
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            margin-top: 10px;
        }

        .file-input-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
        }

        .file-input-btn {
            background: #fff0f2;
            color: #333;
            padding: 12px 24px;
            border: 2px dashed #f8a5b1;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .file-input-btn:hover {
            background: #ffe6ea;
            border-color: #f5576c;
        }

        .file-info {
            margin-top: 10px;
            padding: 10px;
            background: #ffeef0;
            border-radius: 6px;
            font-size: 14px;
            color: #f5576c;
            display: none;
        }

        .btn {
            padding: 14px 30px;
            background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: block;
            margin: 25px auto 0;
            width: 200px;
        }

        .btn:hover {
            background: linear-gradient(135deg, #e74c3c 0%, #d66fd8 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 87, 108, 0.3);
        }

        .btn:disabled {
            background: #cccccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .error-message {
            color: #e74c3c;
            background: #ffeaea;
            padding: 12px;
            border-radius: 6px;
            margin: 10px 0;
            border-left: 4px solid #e74c3c;
            display: none;
        }

        .success-message {
            color: #27ae60;
            background: #eaffea;
            padding: 12px;
            border-radius: 6px;
            margin: 10px 0;
            border-left: 4px solid #27ae60;
        }

        small {
            color: #666;
            font-size: 14px;
            display: block;
            margin-top: 5px;
        }

        .back {
            display: inline-block;
            margin-top: 25px;
            padding: 10px 20px;
            color: #f5576c;
            text-decoration: none;
            font-weight: 500;
            border: 1px solid #f5576c;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .back:hover {
            background: #f5576c;
            color: white;
            transform: translateX(-5px);
        }

        .status {
            padding: 8px 16px;
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            border-radius: 20px;
            display: inline-block;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        .complaint-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .box {
                padding: 25px;
                margin: 15px;
                width: 95%;
            }
            
            h2 {
                font-size: 24px;
            }
            
            .complaint-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <div class="box">
        <img class="log-img" src="images/logos/AU Logo.png" alt="AU Logo">
        
        <h2>Complaint Review & Response</h2>
        
        <div class="complaint-grid">
            <div class="section">
                <span class="label">Student</span>
                <div class="value">
                    <?= htmlspecialchars($data['student_name']) ?>  
                    (ID: <?= htmlspecialchars($data['student_id']) ?>)
                </div>
            </div>

            <div class="section">
                <span class="label">Email</span>
                <div class="value"><?= htmlspecialchars($data['email']) ?></div>
            </div>

            <div class="section">
                <span class="label">Phone</span>
                <div class="value"><?= htmlspecialchars($data['phone']) ?></div>
            </div>

            <div class="section">
                <span class="label">Current Status</span>
                <span class="status"><?= $data['status'] ?></span>
            </div>
        </div>

        <div class="section">
            <span class="label">Subject</span>
            <div class="value"><?= htmlspecialchars($data['subject']) ?></div>
        </div>

        <div class="section">
            <span class="label">Complaint</span>
            <div class="value"><?= nl2br(htmlspecialchars($data['complaint_text'])) ?></div>
        </div>

        <?php if (!empty($data['file_attachment'])): ?>
        <div class="section">
            <span class="label">Student Attachment</span>
            <a class="file-link" href="complaint_files/<?= $data['file_attachment'] ?>" target="_blank">
                Download Complaint Attachment
            </a>
        </div>
        <?php endif; ?>

        <hr>

        <?php if (!empty($data['response_text'])): ?>
        <!-- ALREADY RESPONDED -->
        <div class="section" style="background: #f0fff4;">
            <span class="label">Your Previous Response</span>
            <div class="value"><?= nl2br(htmlspecialchars($data['response_text'])) ?></div>
        </div>

        <?php if (!empty($data['response_file'])): ?>
        <div class="section">
            <span class="label">Response Attachment</span>
            <a class="file-link" href="response_files/<?= $data['response_file'] ?>" target="_blank">
                Download Response File
            </a>
        </div>
        <?php endif; ?>

        <div class="section">
            <span class="label">Response Status</span>
            <div class="value">
                ✅ This complaint has already been responded to and marked as <strong>Resolved</strong>.
                <br><br>
                <em>If you need to update your response, please contact an administrator.</em>
            </div>
        </div>

        <?php else: ?>
        <!-- RESPONSE FORM -->
        <form method="post" enctype="multipart/form-data" id="responseForm" onsubmit="return validateForm()">
            <input type="hidden" name="complaint_id" value="<?= $complaint_id; ?>">
            
            <div class="section">
                <span class="label">Your Response <span style="color: #e74c3c;">*</span></span>
                <textarea name="response_text" id="response_text" required 
                          placeholder="Enter your detailed response here..."></textarea>
                <div id="charCounter" style="font-size: 12px; color: #666; text-align: right; margin-top: 5px;">
                    0 characters
                </div>
            </div>

            <div class="section">
                <span class="label">Attach Response File (Optional)</span>
                <div class="file-upload-container">
                    <div class="file-input-wrapper">
                        <div class="file-input-btn" onclick="document.getElementById('response_file').click()">
                            <span>📄</span> Choose File (PDF/JPG/PNG - max 2MB)
                        </div>
                        <input type="file" name="response_file" id="response_file" 
                               accept=".pdf,.jpg,.jpeg,.png" onchange="validateFile(this)">
                    </div>
                    <div id="fileInfo" class="file-info">
                        Selected file: <span id="fileName"></span> (<span id="fileSize"></span>)
                    </div>
                    <div id="fileError" class="error-message"></div>
                </div>
                <small>Allowed formats: PDF, JPG, JPEG, PNG • Maximum size: 2MB</small>
            </div>

            <div id="formError" class="error-message"></div>

            <button class="btn" type="submit" id="submitBtn">Submit Response</button>
        </form>
        <?php endif; ?>

        <a class="back" href="teacher_dashboard.php">← Back to Dashboard</a>
    </div>

    <script>
        // File validation function
        function validateFile(input) {
            const fileError = document.getElementById('fileError');
            const fileInfo = document.getElementById('fileInfo');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            
            fileError.style.display = 'none';
            fileInfo.style.display = 'none';
            
            if (input.files.length > 0) {
                const file = input.files[0];
                const fileSizeMB = file.size / (1024 * 1024); // Convert to MB
                const fileType = file.type;
                const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                
                // Validate file type
                if (!allowedTypes.includes(fileType)) {
                    fileError.textContent = 'Only PDF, JPG, JPEG, PNG files are allowed.';
                    fileError.style.display = 'block';
                    input.value = '';
                    return false;
                }
                
                // Validate file size (max 2MB)
                if (fileSizeMB > 2) {
                    fileError.textContent = 'File size exceeds 2MB limit. Please select a smaller file.';
                    fileError.style.display = 'block';
                    input.value = '';
                    return false;
                }
                
                // Show file info
                fileName.textContent = file.name;
                fileSize.textContent = fileSizeMB.toFixed(2) + ' MB';
                fileInfo.style.display = 'block';
            }
            
            return true;
        }
        
        // Form validation
        function validateForm() {
            const responseText = document.getElementById('response_text').value.trim();
            const fileInput = document.getElementById('response_file');
            const formError = document.getElementById('formError');
            const submitBtn = document.getElementById('submitBtn');
            
            formError.style.display = 'none';
            
            // Validate response text
            if (responseText.length < 10) {
                formError.textContent = 'Please provide a meaningful response (at least 10 characters).';
                formError.style.display = 'block';
                return false;
            }
            
            // Validate file if selected
            if (fileInput.files.length > 0) {
                if (!validateFile(fileInput)) {
                    return false;
                }
            }
            
            // Confirm submission
            if (!confirm('Submit response? This will mark the complaint as RESOLVED.')) {
                return false;
            }
            
            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Submitting...';
            
            return true;
        }
        
        // Character counter for textarea
        document.getElementById('response_text').addEventListener('input', function() {
            const charCount = this.value.length;
            const charCounter = document.getElementById('charCounter');
            
            if (charCounter) {
                charCounter.textContent = charCount + ' characters';
                charCounter.style.color = charCount < 10 ? '#e74c3c' : charCount > 1000 ? '#f39c12' : '#27ae60';
            }
        });
        
        // Initialize character counter on page load
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('response_text');
            if (textarea) {
                const charCount = textarea.value.length;
                const charCounter = document.getElementById('charCounter');
                if (charCounter) {
                    charCounter.textContent = charCount + ' characters';
                    charCounter.style.color = charCount < 10 ? '#e74c3c' : charCount > 1000 ? '#f39c12' : '#27ae60';
                }
            }
        });
    </script>
</body>
</html>