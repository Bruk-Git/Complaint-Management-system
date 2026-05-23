<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['dean_id'])) {
    header("Location: dean_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Invalid complaint");
}

$complaint_id = intval($_GET['id']);

$c = mysqli_query($conn,"
    SELECT * FROM complain
    WHERE complaint_id='$complaint_id'
");

$data = mysqli_fetch_assoc($c);
if (!$data) die("Complaint not found");
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="images/Logos/AU Logo.png">
    <title>Dean Review | CMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

        h3 {
            color: #5a2d82;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            border-bottom: 3px solid #5a2d82;
            padding-bottom: 15px;
        }

        h4 {
            color: #5a2d82;
            margin: 20px 0 15px;
            font-size: 22px;
        }

        p {
            margin: 12px 0;
            color: #444;
            line-height: 1.6;
        }

        b {
            color: #333;
            font-weight: 600;
        }

        hr {
            border: none;
            height: 1px;
            background: linear-gradient(to right, transparent, #5a2d82, transparent);
            margin: 30px 0;
        }

        .file {
            background: #f8f9ff;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #0078d4;
            margin: 15px 0;
        }

        .file a {
            color: #0078d4;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            background: white;
            border: 1px solid #0078d4;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .file a:hover {
            background: #0078d4;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 120, 212, 0.3);
        }

        .file a::before {
            content: "📎";
            font-size: 16px;
        }

        form {
            background: #f9f9ff;
            padding: 25px;
            border-radius: 10px;
            margin-top: 30px;
            border: 1px solid #e0e0e0;
        }

        label {
            display: block;
            margin: 15px 0 8px;
            color: #5a2d82;
            font-weight: 600;
            font-size: 16px;
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
            border-color: #5a2d82;
            box-shadow: 0 0 0 3px rgba(90, 45, 130, 0.1);
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
            background: #f0f0f0;
            color: #333;
            padding: 12px 24px;
            border: 2px dashed #ccc;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .file-input-btn:hover {
            background: #e6e6ff;
            border-color: #5a2d82;
        }

        .file-info {
            margin-top: 10px;
            padding: 10px;
            background: #e8f4ff;
            border-radius: 6px;
            font-size: 14px;
            color: #0078d4;
            display: none;
        }

        button {
            padding: 14px 30px;
            background: linear-gradient(135deg, #5a2d82 0%, #7b4397 100%);
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

        button:hover {
            background: linear-gradient(135deg, #4a2570 0%, #6a3887 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(90, 45, 130, 0.3);
        }

        button:disabled {
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
        }

        .complaint-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-item {
            background: #f8f9ff;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #5a2d82;
        }

        @media (max-width: 768px) {
            .box {
                padding: 25px;
                margin: 15px;
                width: 95%;
            }
            
            h3 {
                font-size: 24px;
            }
            
            .complaint-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <div class="box">
        <img class="log-img" src="images/logos/AU Logo.png" alt="AU Logo">
        
        <h3>Complaint Review</h3>
        
        <div class="complaint-info">
            <div class="info-item">
                <p><b>Student:</b> <?= $data['student_name']; ?></p>
            </div>
            <div class="info-item">
                <p><b>Department:</b> <?= $data['department']; ?></p>
            </div>
            <div class="info-item">
                <p><b>Email:</b> <?= $data['email']; ?></p>
            </div>
            <div class="info-item">
                <p><b>Phone:</b> <?= $data['phone']; ?></p>
            </div>
        </div>
        
        <p><b>Subject:</b> <?= htmlspecialchars($data['subject']); ?></p>
        
        <p><b>Complaint:</b><br>
        <?= nl2br(htmlspecialchars($data['complaint_text'])); ?>
        </p>
        
        <?php if ($data['file_attachment']) { ?>
        <div class="file">
            <b>Attachment:</b><br>
            <a href="<?= $data['file_attachment']; ?>" download target="_blank">Download Complaint Attachment</a>
        </div>
        <?php } ?>
        
        <hr>
        
        <?php if ($data['response_text']) { ?>
        <h4>Previous Response</h4>
        <div style="background: #f0f7ff; padding: 20px; border-radius: 8px; margin: 15px 0;">
            <p><?= nl2br(htmlspecialchars($data['response_text'])); ?></p>
            <small>
                Responded by <?= $data['responder_name']; ?> on <?= $data['response_date']; ?>
            </small>
            
            <?php if ($data['response_file']) { ?>
            <div class="file" style="margin-top: 15px;">
                <a href="uploads/responses/<?= $data['response_file']; ?>" download target="_blank">
                    Download Previous Response File
                </a>
            </div>
            <?php } ?>
        </div>
        <hr>
        <?php } ?>
        
        <form method="post" action="dean_response_process.php" enctype="multipart/form-data" id="responseForm" onsubmit="return validateForm()">
            <input type="hidden" name="complaint_id" value="<?= $complaint_id; ?>">
            
            <label><b>Dean Response</b> <span style="color: #e74c3c;">*</span></label>
            <textarea name="response_text" id="response_text" required placeholder="Enter your response here..."></textarea>
            
            <label><b>Attach Response File (Optional)</b></label>
            <div class="file-upload-container">
                <div class="file-input-wrapper">
                    <div class="file-input-btn" onclick="document.getElementById('response_file').click()">
                        <span>📄</span> Choose PDF File (max 2MB)
                    </div>
                    <input type="file" name="response_file" id="response_file" accept=".pdf" onchange="validateFile(this)">
                </div>
                <div id="fileInfo" class="file-info">
                    Selected file: <span id="fileName"></span> (<span id="fileSize"></span>)
                </div>
                <div id="fileError" class="error-message"></div>
            </div>
            
            <div id="formError" class="error-message"></div>
            
            <button type="submit" id="submitBtn">Submit Response</button>
        </form>
    </div>

    <script>
        // File validation function
        function validateFile(input) {
            const fileError = document.getElementById('fileError');
            const fileInfo = document.getElementById('fileInfo');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const submitBtn = document.getElementById('submitBtn');
            
            fileError.style.display = 'none';
            fileInfo.style.display = 'none';
            
            if (input.files.length > 0) {
                const file = input.files[0];
                const fileSizeMB = file.size / (1024 * 1024); // Convert to MB
                const fileType = file.type;
                
                // Validate file type
                if (fileType !== 'application/pdf') {
                    fileError.textContent = 'Only PDF files are allowed. Please select a PDF file.';
                    fileError.style.display = 'block';
                    input.value = ''; // Clear the file input
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
            
            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Submitting...';
            
            return true;
        }
        
        // Character counter for textarea
        document.getElementById('response_text').addEventListener('input', function() {
            const charCount = this.value.length;
            const counter = document.getElementById('charCounter') || (() => {
                const div = document.createElement('div');
                div.id = 'charCounter';
                div.style.fontSize = '12px';
                div.style.color = '#666';
                div.style.textAlign = 'right';
                div.style.marginTop = '5px';
                this.parentNode.insertBefore(div, this.nextSibling);
                return div;
            })();
            
            counter.textContent = charCount + ' characters';
            counter.style.color = charCount < 10 ? '#e74c3c' : charCount > 1000 ? '#f39c12' : '#27ae60';
        });
        
        // Show/hide file upload area
        function toggleFileUpload() {
            const uploadArea = document.getElementById('uploadArea');
            const toggleBtn = document.getElementById('toggleUploadBtn');
            
            if (uploadArea.style.display === 'none') {
                uploadArea.style.display = 'block';
                toggleBtn.textContent = '✖ Remove Attachment';
            } else {
                uploadArea.style.display = 'none';
                document.getElementById('response_file').value = '';
                document.getElementById('fileInfo').style.display = 'none';
                toggleBtn.textContent = '+ Add Attachment (PDF only)';
            }
        }
        
        // Initialize the form
        document.addEventListener('DOMContentLoaded', function() {
            // Add file size info
            const fileInput = document.getElementById('response_file');
            fileInput.insertAdjacentHTML('afterend', 
                '<div style="font-size: 12px; color: #666; margin-top: 5px;">Maximum file size: 2MB • Allowed format: PDF only</div>'
            );
            
            // Add toggle button for file upload
            const uploadLabel = document.querySelector('label[for="response_file"]');
            uploadLabel.insertAdjacentHTML('afterend',
                '<button type="button" id="toggleUploadBtn" style="background: none; border: none; color: #0078d4; cursor: pointer; font-size: 14px; padding: 5px 0; margin-left: 10px;">+ Add Attachment (PDF only)</button>'
            );
            
            document.getElementById('toggleUploadBtn').addEventListener('click', toggleFileUpload);
            
            // Hide upload area by default
            const uploadArea = document.querySelector('.file-upload-container');
            uploadArea.id = 'uploadArea';
            uploadArea.style.display = 'none';
        });
    </script>

</body>
</html>
