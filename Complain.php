<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Submit Complaint</title>
<link rel="icon" href="images/Logos/AU Logo.png">
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background: linear-gradient(135deg, #2f4059ff 0%, #e4edf5 100%);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.form-container {
    background: white;
    width: 100%;
    max-width: 900px;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 20px 50px rgba(0, 59, 111, 0.15);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.log-img {
    display: block;
    margin: 0 auto 25px;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 4px solid #003b6f;
    padding: 5px;
    background: white;
    box-shadow: 0 8px 25px rgba(0, 59, 111, 0.2);
}

h2 {
    text-align: center;
    color: #003b6f;
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 35px;
    position: relative;
    padding-bottom: 15px;
}

h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, #003b6f, #ffc107);
    border-radius: 2px;
}

form {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 25px;
}

.input-group {
    display: flex;
    flex-direction: column;
}

.input-group:nth-child(7), /* Program */
.input-group:nth-child(8), /* Complaint Subject */
.input-group:nth-child(9), /* Complaint Text */
.input-group:nth-child(10) { /* File Upload */
    grid-column: span 2;
}

label {
    color: #003b6f;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.required::after {
    content: '*';
    color: #dc3545;
    margin-left: 3px;
}

.input-group:nth-child(10) label::after {
    content: '';
}

input, select, textarea {
    padding: 15px 18px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 15px;
    transition: all 0.3s ease;
    background: #f8fafc;
    color: #333;
}

input:focus, select:focus, textarea:focus {
    outline: none;
    border-color: #003b6f;
    box-shadow: 0 0 0 3px rgba(0, 59, 111, 0.1);
    background: white;
}

input::placeholder, textarea::placeholder {
    color: #999;
}

textarea {
    min-height: 150px;
    resize: vertical;
    line-height: 1.6;
    font-family: inherit;
}

small {
    color: #666;
    font-size: 12px;
    margin-top: 6px;
    display: block;
    line-height: 1.5;
}

input[type="file"] {
    padding: 15px;
    border: 2px dashed #e0e0e0;
    background: #f8fafc;
    cursor: pointer;
}

input[type="file"]:hover {
    border-color: #003b6f;
    background: #e9f7fe;
}

input[type="file"]::file-selector-button {
    padding: 10px 20px;
    background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    margin-right: 15px;
    transition: all 0.3s ease;
}

input[type="file"]::file-selector-button:hover {
    background: linear-gradient(135deg, #005fa3 0%, #007acc 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 95, 163, 0.2);
}

/* Anonymous Checkbox Styling */
.anonymous-section {
    grid-column: span 2;
    background: #f8fafc;
    padding: 25px;
    border-radius: 12px;
    border: 2px solid #e0e0e0;
    margin-bottom: 15px;
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.checkbox-group input[type="checkbox"] {
    width: 22px;
    height: 22px;
    cursor: pointer;
}

.checkbox-group label {
    color: #003b6f;
    font-weight: 600;
    font-size: 16px;
    margin: 0;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
}

.checkbox-group label::after {
    content: '👤';
    font-size: 18px;
}

.anonymous-note {
    background: #fff3cd;
    color: #856404;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #ffc107;
    font-size: 14px;
    line-height: 1.6;
}

.anonymous-note i {
    color: #dc3545;
    font-weight: 600;
}

/* Disabled fields styling */
input:disabled, select:disabled {
    background: #f0f0f0;
    color: #999;
    cursor: not-allowed;
    border-color: #ddd;
}

/* Hidden fields */
.hidden {
    opacity: 0.6;
}

.btn-submit {
    grid-column: span 2;
    padding: 18px;
    background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 18px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 10px;
    position: relative;
    overflow: hidden;
}

.btn-submit:hover {
    background: linear-gradient(135deg, #005fa3 0%, #007acc 100%);
    transform: translateY(-3px);
    box-shadow: 0 15px 30px rgba(0, 95, 163, 0.3);
}

.btn-submit:active {
    transform: translateY(-1px);
}

.btn-submit::before {
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

.btn-submit:hover::before {
    left: 100%;
}

/* Validation Styling */
.input-group input:valid:not(:placeholder-shown),
.input-group select:valid,
.input-group textarea:valid:not(:placeholder-shown) {
    border-color: #28a745;
    background: #f8fff9;
}

.input-group input:invalid:not(:placeholder-shown),
.input-group select:invalid,
.input-group textarea:invalid:not(:placeholder-shown) {
    border-color: #dc3545;
    background: #fff8f8;
}

.error-message {
    color: #dc3545;
    font-size: 12px;
    margin-top: 5px;
    display: none;
}

/* Program Options Styling */
.program-options {
    display: flex;
    gap: 15px;
    margin-top: 10px;
}

.program-option {
    flex: 1;
    text-align: center;
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8fafc;
}

.program-option:hover {
    border-color: #003b6f;
    background: #e9f7fe;
}

.program-option.selected {
    border-color: #003b6f;
    background: #003b6f;
    color: white;
    font-weight: 600;
}

.program-option input {
    display: none;
}

.program-option label {
    display: block;
    cursor: pointer;
    font-weight: 600;
    color: inherit;
    margin: 0;
}

.program-option label::after {
    content: '';
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-container {
        padding: 30px 25px;
        margin: 20px;
    }
    
    h2 {
        font-size: 26px;
    }
    
    form {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .input-group:nth-child(7),
    .input-group:nth-child(8),
    .input-group:nth-child(9),
    .input-group:nth-child(10) {
        grid-column: span 1;
    }
    
    .anonymous-section {
        grid-column: span 1;
    }
    
    .btn-submit {
        grid-column: span 1;
        padding: 16px;
        font-size: 16px;
    }
    
    .log-img {
        width: 80px;
        height: 80px;
    }
    
    .program-options {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .form-container {
        padding: 25px 20px;
    }
    
    h2 {
        font-size: 22px;
        margin-bottom: 25px;
    }
    
    input, select, textarea {
        padding: 12px 15px;
        font-size: 14px;
    }
    
    .btn-submit {
        padding: 14px;
        font-size: 15px;
    }
    
    .log-img {
        width: 70px;
        height: 70px;
    }
    
    .anonymous-section {
        padding: 20px 15px;
    }
}

/* Animation for form elements */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.input-group {
    animation: fadeInUp 0.5s ease-out forwards;
    opacity: 0;
}

.input-group:nth-child(1) { animation-delay: 0.1s; }
.input-group:nth-child(2) { animation-delay: 0.2s; }
.input-group:nth-child(3) { animation-delay: 0.3s; }
.input-group:nth-child(4) { animation-delay: 0.4s; }
.input-group:nth-child(5) { animation-delay: 0.5s; }
.input-group:nth-child(6) { animation-delay: 0.6s; }
.input-group:nth-child(7) { animation-delay: 0.7s; }
.input-group:nth-child(8) { animation-delay: 0.8s; }
.input-group:nth-child(9) { animation-delay: 0.9s; }
.anonymous-section { animation-delay: 1s; }
.input-group:nth-child(10) { animation-delay: 1.1s; }
.btn-submit { animation-delay: 1.2s; }

/* Warning animation for anonymous checkbox */
@keyframes pulseWarning {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.anonymous-section.pulse {
    animation: pulseWarning 0.5s ease-in-out;
}
</style>
</head>
<body>

<div class="form-container">
    <img class="log-img" src="images/logos/AU Logo.png" alt="AU Logo">
    <h2>Submit Your Complaint</h2>

    <form action="submit_complaint.php"
          method="POST"
          enctype="multipart/form-data"
          onsubmit="return validateForm();"
          id="complaintForm">

        <!-- Anonymous Option Section -->
        <div class="anonymous-section" id="anonymousSection">
            <div class="checkbox-group">
                <input type="checkbox" 
                       id="anonymous" 
                       name="anonymous"
                       onchange="toggleAnonymous(this.checked)">
                <label for="anonymous">Submit Anonymously</label>
            </div>
            <div class="anonymous-note" id="anonymousNote">
                <p><strong>⚠️ Note:</strong> If you choose to submit anonymously:</p>
                <ul style="margin-left: 20px; margin-top: 8px;">
                    <li>Your name, ID, email, and phone number will not be recorded</li>
                    <li>The department will only see "Anonymous Student"</li>
                    <li>You will not receive email/SMS updates about your complaint</li>
                    <li>You need to check status manually using the complaint ID</li>
                </ul>
            </div>
        </div>

        <!-- Student Information (Will be disabled when anonymous) -->
        <div class="input-group" id="nameGroup">
            <label class="required">Full Name</label>
            <input type="text" 
                   name="student_name" 
                   id="student_name" 
                   placeholder="Enter your full name"
                   required>
        </div>

        <div class="input-group" id="idGroup">
            <label class="required">Student ID</label>
            <input type="text" 
                   name="student_id" 
                   id="student_id" 
                   placeholder="Enter your student ID"
                   required>
        </div>

        <div class="input-group">
            <label class="required">Department</label>
            <select name="department" required>
                <option value="">Select Department</option>
                <option value="Computer Science">Computer Science</option>
                <option value="Accounting">Accounting</option>
                <option value="Marketing Management">Marketing Management</option>
                <option value="Business Management">Business Management</option>
                <option value="Business Management">IT</option>                   
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="input-group">
            <label class="required">Program</label>
            <div class="program-options">
                <div class="program-option" onclick="selectProgram('TVET')">
                    <input type="radio" id="program_tvet" name="program" value="TVET" required>
                    <label for="program_tvet">TVET</label>
                </div>
                <div class="program-option" onclick="selectProgram('Degree')">
                    <input type="radio" id="program_degree" name="program" value="Degree" required>
                    <label for="program_degree">Degree</label>
                </div>
                <div class="program-option" onclick="selectProgram('Masters')">
                    <input type="radio" id="program_masters" name="program" value="Masters" required>
                    <label for="program_masters">Masters</label>
                </div>
            </div>
        </div>

        <div class="input-group">
            <label class="required">Academic Year</label>
            <select name="academic_year" required>
                <option value="">Select Year</option>
                <option value="1st Year">1st Year</option>
                <option value="2nd Year">2nd Year</option>
                <option value="3rd Year">3rd Year</option>
                <option value="4th Year">4th Year</option>
                <option value="5th Year">5th Year</option>
                <option value="Graduate">Graduate</option>
            </select>
        </div>

        <div class="input-group" id="emailGroup">
            <label class="required">Email Address</label>
            <input type="email" 
                   name="email" 
                   id="email" 
                   placeholder="student@university.edu"
                   required>
        </div>

        <div class="input-group" id="phoneGroup">
            <label class="required">Phone Number</label>
            <input type="text" 
                   name="phone" 
                   id="phone" 
                   placeholder="+251 9XX XX XX XX"
                   required>
        </div>

        <div class="input-group">
            <label class="required">Complaint Subject</label>
            <input type="text" 
                   name="subject" 
                   placeholder="Brief description of your complaint"
                   required>
        </div>

        <div class="input-group">
            <label class="required">Your Complaint</label>
            <textarea name="complaint_text" 
                      placeholder="Please provide detailed information about your complaint..."
                      required></textarea>
            <small>Be specific and include relevant details. Maximum 1000 characters.</small>
        </div>

        <div class="input-group">
            <label>Upload Supporting Document (Optional)</label>
            <input type="file"
                   name="file_path"
                   id="file_path"
                   accept="pdf">
            <small style="color:#555;">
                Supported formats: PDF 2MB
            </small>
        </div>

        <input type="hidden" name="is_anonymous" id="is_anonymous" value="0">

        <button type="submit" class="btn-submit">
            Submit Complaint
        </button>

    </form>
</div>

<script>
let selectedProgram = '';

function selectProgram(program) {
    selectedProgram = program;
    
    // Update UI
    document.querySelectorAll('.program-option').forEach(option => {
        option.classList.remove('selected');
    });
    
    // Add selected class to clicked option
    event.currentTarget.classList.add('selected');
    
    // Update radio button
    document.getElementById(`program_${program.toLowerCase()}`).checked = true;
}

function toggleAnonymous(isAnonymous) {
    const nameInput = document.getElementById('student_name');
    const idInput = document.getElementById('student_id');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');
    const isAnonymousInput = document.getElementById('is_anonymous');
    const nameGroup = document.getElementById('nameGroup');
    const idGroup = document.getElementById('idGroup');
    const emailGroup = document.getElementById('emailGroup');
    const phoneGroup = document.getElementById('phoneGroup');
    const anonymousSection = document.getElementById('anonymousSection');
    
    if (isAnonymous) {
        // Disable and clear personal info fields
        nameInput.disabled = true;
        nameInput.value = 'Anonymous Student';
        nameInput.required = false;
        nameGroup.classList.add('hidden');
        
        idInput.disabled = true;
        idInput.value = 'ANONYMOUS-' + Date.now().toString().slice(-6);
        idInput.required = false;
        idGroup.classList.add('hidden');
        
        emailInput.disabled = true;
        emailInput.value = 'anonymous@university.edu';
        emailInput.required = false;
        emailGroup.classList.add('hidden');
        
        phoneInput.disabled = true;
        phoneInput.value = 'N/A';
        phoneInput.required = false;
        phoneGroup.classList.add('hidden');
        
        isAnonymousInput.value = '1';
        
        // Add warning animation
        anonymousSection.classList.add('pulse');
        setTimeout(() => {
            anonymousSection.classList.remove('pulse');
        }, 500);
        
    } else {
        // Enable personal info fields
        nameInput.disabled = false;
        nameInput.value = '';
        nameInput.required = true;
        nameInput.placeholder = 'Enter your full name';
        nameGroup.classList.remove('hidden');
        
        idInput.disabled = false;
        idInput.value = '';
        idInput.required = true;
        idInput.placeholder = 'Enter your student ID';
        idGroup.classList.remove('hidden');
        
        emailInput.disabled = false;
        emailInput.value = '';
        emailInput.required = true;
        emailInput.placeholder = 'student@university.edu';
        emailGroup.classList.remove('hidden');
        
        phoneInput.disabled = false;
        phoneInput.value = '';
        phoneInput.required = true;
        phoneInput.placeholder = '+251 9XX XX XX XX';
        phoneGroup.classList.remove('hidden');
        
        isAnonymousInput.value = '0';
    }
}

function validateForm() {
    const fileInput = document.getElementById('file_path');
    const complaintText = document.querySelector('textarea[name="complaint_text"]');
    const isAnonymous = document.getElementById('anonymous').checked;
    
    // File validation
    if (fileInput.value) {
        const file = fileInput.files[0];
        const maxSize = 2 * 1024 * 1024; // 5MB
        const allowedTypes = [
           "application/pdf"
        ];
        
        if (file.size > maxSize) {
            alert("File size must be less than 5MB.");
            fileInput.value = "";
            return false;
        }
        
        if (!allowedTypes.includes(file.type)) {
            alert("Invalid file type. Only PDF files are allowed.");
            fileInput.value = "";
            return false;
        }
    }
    
    // Complaint text validation
    if (complaintText.value.length > 1000) {
        alert("Complaint text must be less than 1000 characters.");
        complaintText.focus();
        return false;
    }
    
    // Program selection validation
    if (!selectedProgram) {
        alert("Please select your program (TVET, Degree, or Masters).");
        return false;
    }
    
    // Show confirmation for anonymous submission
    if (isAnonymous) {
        const confirmAnonymous = confirm(
            "⚠️ WARNING: You are submitting anonymously.\n\n" +
            "You will not receive email/SMS updates.\n" +
            "You need to track your complaint using the complaint ID.\n" +
            "Your identity will not be shared with the department.\n\n" +
            "Are you sure you want to continue?"
        );
        
        if (!confirmAnonymous) {
            return false;
        }
    }
    
    // Show final confirmation
    const confirmationMessage = isAnonymous ? 
        "Your complaint will be submitted anonymously." :
        "Your complaint will be submitted with your personal information.";
    
    return confirm("Are you sure you want to submit this complaint?\n\n" + confirmationMessage);
}

// Initialize form with anonymous check
document.addEventListener('DOMContentLoaded', function() {
    // Set default program selection
    selectProgram('Degree');
    
    // Initialize anonymous checkbox
    const anonymousCheckbox = document.getElementById('anonymous');
    toggleAnonymous(anonymousCheckbox.checked);
});

// Character counter for complaint text
document.querySelector('textarea[name="complaint_text"]').addEventListener('input', function(e) {
    const charCount = e.target.value.length;
    const counter = document.querySelector('.input-group:nth-child(9) small') || 
                   document.querySelector('.input-group:nth-child(9)').querySelector('small');
    
    if (charCount > 1000) {
        e.target.style.borderColor = '#dc3545';
        counter.style.color = '#dc3545';
        counter.textContent = `Character limit exceeded (${charCount}/1000)`;
    } else if (charCount > 900) {
        e.target.style.borderColor = '#ffc107';
        counter.style.color = '#ffc107';
        counter.textContent = `${charCount}/1000 characters`;
    } else {
        e.target.style.borderColor = '#e0e0e0';
        counter.style.color = '#666';
        counter.textContent = `${charCount}/1000 characters`;
    }
});
</script>

</body>
</html>