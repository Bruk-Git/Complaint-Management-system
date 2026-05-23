<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Registration - Admas University CMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .register-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 850px;
            overflow: hidden;
            display: flex;
            min-height: 700px;
        }

        .registration-left {
            flex: 1;
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .registration-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="rgba(255,255,255,0.1)"/></svg>');
            background-size: cover;
        }

        .registration-left h1 {
            font-size: 32px;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .registration-left p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
            opacity: 0.95;
        }

        .benefits-list {
            list-style: none;
            position: relative;
            z-index: 1;
        }

        .benefits-list li {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            font-size: 15px;
        }

        .benefits-list i {
            margin-right: 10px;
            color: #bbdefb;
            font-size: 18px;
        }

        .university-logo-large {
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0d47a1;
            font-size: 48px;
            font-weight: bold;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
        }

        .registration-right {
            flex: 1.5;
            padding: 40px;
            overflow-y: auto;
            max-height: 700px;
        }

        .registration-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .registration-header h2 {
            color: #0d47a1;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .registration-header p {
            color: #666;
            font-size: 14px;
        }

        .registration-form {
            margin-top: 20px;
        }

        .form-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }

        .section-title {
            display: flex;
            align-items: center;
            color: #0d47a1;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .section-title i {
            margin-right: 10px;
            font-size: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        .form-group label.required::after {
            content: ' *';
            color: #d32f2f;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon input,
        .input-with-icon select {
            width: 100%;
            padding: 14px 14px 14px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .input-with-icon input:focus,
        .input-with-icon select:focus {
            outline: none;
            border-color: #0d47a1;
            background: white;
            box-shadow: 0 0 0 3px rgba(13, 71, 161, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #757575;
            font-size: 18px;
        }

        .password-strength {
            margin-top: 5px;
            height: 5px;
            border-radius: 3px;
            overflow: hidden;
            background: #e0e0e0;
        }

        .strength-bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease, background-color 0.3s ease;
        }

        .strength-text {
            font-size: 12px;
            margin-top: 5px;
            color: #666;
        }

        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            margin: 25px 0;
        }

        .terms-checkbox input {
            margin-top: 3px;
            margin-right: 10px;
        }

        .terms-checkbox label {
            font-size: 14px;
            color: #333;
            line-height: 1.5;
        }

        .terms-checkbox a {
            color: #0d47a1;
            text-decoration: none;
        }

        .terms-checkbox a:hover {
            text-decoration: underline;
        }

        .register-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-btn:hover {
            background: linear-gradient(135deg, #0b3d91 0%, #1357b0 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(13, 71, 161, 0.3);
        }

        .register-btn i {
            margin-right: 10px;
        }

        .message-container {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .message-container.success {
            background: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #2e7d32;
            display: block;
        }

        .message-container.error {
            background: #ffebee;
            color: #c62828;
            border-left: 4px solid #c62828;
            display: block;
        }

        .message-container.warning {
            background: #fff3e0;
            color: #ef6c00;
            border-left: 4px solid #ef6c00;
            display: block;
        }

        .login-link {
            text-align: center;
            margin-top: 25px;
            color: #666;
            font-size: 14px;
        }

        .login-link a {
            color: #0d47a1;
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .form-note {
            font-size: 12px;
            color: #757575;
            margin-top: 5px;
            font-style: italic;
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }

        .progress-steps::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e0e0e0;
            z-index: 1;
        }

        .step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
        }

        .step-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e0e0e0;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .step.active .step-circle {
            background: #0d47a1;
            color: white;
        }

        .step-label {
            font-size: 12px;
            color: #666;
        }

        .step.active .step-label {
            color: #0d47a1;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .register-container {
                flex-direction: column;
                max-width: 100%;
            }
            
            .registration-left {
                display: none;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .registration-right {
                padding: 25px 20px;
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="register-container">
        <!-- Left Section - Information -->
        <div class="registration-left">
            <div class="university-logo-large">AU</div>
            <h1>Department Registration</h1>
            <p>Register your department to access the Complaint Management System of Admas University. Manage student complaints efficiently and transparently.</p>
            
            <ul class="benefits-list">
                <li><i class="fas fa-check-circle"></i> Streamlined complaint processing</li>
                <li><i class="fas fa-check-circle"></i> Real-time status tracking</li>
                <li><i class="fas fa-check-circle"></i> Secure data management</li>
                <li><i class="fas fa-check-circle"></i> Automated notifications</li>
                <li><i class="fas fa-check-circle"></i> Comprehensive reporting tools</li>
                <li><i class="fas fa-check-circle"></i> Multi-user access control</li>
                <li><i class="fas fa-check-circle"></i> 24/7 system availability</li>
            </ul>
            
            <div style="margin-top: 30px; font-size: 13px; opacity: 0.8;">
                <p><i class="fas fa-shield-alt"></i> All data is encrypted and secured</p>
                <p><i class="fas fa-headset"></i> Support available: support@admas.edu</p>
            </div>
        </div>

        <!-- Right Section - Registration Form -->
        <div class="registration-right">
            <div class="registration-header">
                <h2>Register New Department</h2>
                <p>Complete all fields to request department access</p>
                
                <!-- Progress Steps -->
                <div class="progress-steps">
                    <div class="step active" id="step1">
                        <div class="step-circle">1</div>
                        <div class="step-label">Department</div>
                    </div>
                    <div class="step" id="step2">
                        <div class="step-circle">2</div>
                        <div class="step-label">Contact</div>
                    </div>
                    <div class="step" id="step3">
                        <div class="step-circle">3</div>
                        <div class="step-label">Account</div>
                    </div>
                    <div class="step" id="step4">
                        <div class="step-circle">4</div>
                        <div class="step-label">Complete</div>
                    </div>
                </div>
            </div>

            <!-- Message Container -->
            <?php if (isset($_GET['message'])): ?>
            <div class="message-container <?php echo $_GET['type'] ?? 'info'; ?> fade-in" id="messageBox">
                <i class="fas <?php 
                    echo $_GET['type'] == 'success' ? 'fa-check-circle' : 
                         ($_GET['type'] == 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'); 
                ?>"></i>
                <?php echo htmlspecialchars(urldecode($_GET['message'])); ?>
            </div>
            <?php endif; ?>

            <form id="registrationForm" action="department_register_process.php" method="POST" class="registration-form">
                
                <!-- Section 1: Department Information -->
                <div class="form-section" id="section1">
                    <div class="section-title">
                        <i class="fas fa-university"></i> Department Information
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="department_name" class="required">Department Name</label>
                            <div class="input-with-icon">
                                <i class="fas fa-building input-icon"></i>
                                <input type="text" 
                                       id="department_name" 
                                       name="department_name" 
                                       placeholder="e.g., Computer Science" 
                                       required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="department_code" class="required">Department Code</label>
                            <div class="input-with-icon">
                                <i class="fas fa-code input-icon"></i>
                                <input type="text" 
                                       id="department_code" 
                                       name="department_code" 
                                       placeholder="e.g., CS, IT, EE" 
                                       maxlength="10"
                                       required>
                                <div class="form-note">Short code for your department</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="faculty" class="required">Faculty</label>
                        <div class="input-with-icon">
                            <i class="fas fa-graduation-cap input-icon"></i>
                            <select id="faculty" name="faculty" required>
                                <option value="">Select Faculty</option>
                                <option value="Computing">Faculty of Computing</option>
                                <option value="Engineering">Faculty of Engineering</option>
                                <option value="Business">Faculty of Business</option>
                                <option value="Science">Faculty of Science</option>
                                <option value="Health Sciences">Faculty of Health Sciences</option>
                                <option value="Social Sciences">Faculty of Social Sciences</option>
                                <option value="Law">Faculty of Law</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description (Optional)</label>
                        <div class="input-with-icon">
                            <i class="fas fa-align-left input-icon"></i>
                            <input type="text" 
                                   id="description" 
                                   name="description" 
                                   placeholder="Brief description of your department">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Contact Information -->
                <div class="form-section" id="section2">
                    <div class="section-title">
                        <i class="fas fa-address-card"></i> Contact Information
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contact_person" class="required">Contact Person</label>
                            <div class="input-with-icon">
                                <i class="fas fa-user input-icon"></i>
                                <input type="text" 
                                       id="contact_person" 
                                       name="contact_person" 
                                       placeholder="Full name of contact person" 
                                       required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="contact_position" class="required">Position</label>
                            <div class="input-with-icon">
                                <i class="fas fa-briefcase input-icon"></i>
                                <input type="text" 
                                       id="contact_position" 
                                       name="contact_position" 
                                       placeholder="e.g., Department Head" 
                                       required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contact_email" class="required">Contact Email</label>
                            <div class="input-with-icon">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" 
                                       id="contact_email" 
                                       name="contact_email" 
                                       placeholder="department@admas.edu" 
                                       required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="contact_phone" class="required">Contact Phone</label>
                            <div class="input-with-icon">
                                <i class="fas fa-phone input-icon"></i>
                                <input type="tel" 
                                       id="contact_phone" 
                                       name="contact_phone" 
                                       placeholder="+251 XXX XXX XXX" 
                                       required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Account Information -->
                <div class="form-section" id="section3">
                    <div class="section-title">
                        <i class="fas fa-user-shield"></i> Account Information
                    </div>
                    
                    <div class="form-group">
                        <label for="username" class="required">Username</label>
                        <div class="input-with-icon">
                            <i class="fas fa-user-tie input-icon"></i>
                            <input type="text" 
                                   id="username" 
                                   name="username" 
                                   placeholder="Choose a username" 
                                   required>
                            <div class="form-note">This will be your login username</div>
                        </div>
                        <div id="username-feedback" class="form-note"></div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password" class="required">Password</label>
                            <div class="input-with-icon">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Create a strong password" 
                                       required>
                            </div>
                            <div class="password-strength">
                                <div class="strength-bar" id="strengthBar"></div>
                            </div>
                            <div class="strength-text" id="strengthText">Password strength</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password" class="required">Confirm Password</label>
                            <div class="input-with-icon">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" 
                                       id="confirm_password" 
                                       name="confirm_password" 
                                       placeholder="Re-enter your password" 
                                       required>
                            </div>
                            <div id="password-match" class="form-note"></div>
                        </div>
                    </div>
                    
                    <div class="terms-checkbox">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">
                            I agree to the <a href="terms.php" target="_blank">Terms of Service</a> and 
                            <a href="privacy.php" target="_blank">Privacy Policy</a>. I confirm that I am an 
                            authorized representative of this department at Admas University.
                        </label>
                    </div>
                </div>

                <button type="submit" class="register-btn" id="submitBtn">
                    <i class="fas fa-paper-plane"></i> Submit Registration Request
                </button>
            </form>

            <div class="login-link">
                Already have an account? <a href="department_login.php">Login here</a>
            </div>
        </div>
    </div>

    <script>
        // Password strength checker
        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');
        const confirmPasswordInput = document.getElementById('confirm_password');
        const passwordMatch = document.getElementById('password-match');
        const usernameInput = document.getElementById('username');
        const usernameFeedback = document.getElementById('username-feedback');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let text = '';
            let color = '';

            // Check password strength
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            switch(strength) {
                case 0:
                case 1:
                    text = 'Very Weak';
                    color = '#ff4444';
                    break;
                case 2:
                    text = 'Weak';
                    color = '#ff8800';
                    break;
                case 3:
                    text = 'Fair';
                    color = '#ffbb33';
                    break;
                case 4:
                    text = 'Good';
                    color = '#00C851';
                    break;
                case 5:
                    text = 'Strong';
                    color = '#007E33';
                    break;
            }

            strengthBar.style.width = (strength * 20) + '%';
            strengthBar.style.backgroundColor = color;
            strengthText.textContent = text;
            strengthText.style.color = color;
        });

        // Password confirmation check
        confirmPasswordInput.addEventListener('input', function() {
            if (passwordInput.value !== this.value) {
                passwordMatch.textContent = 'Passwords do not match';
                passwordMatch.style.color = '#ff4444';
            } else {
                passwordMatch.textContent = 'Passwords match';
                passwordMatch.style.color = '#00C851';
            }
        });

        // Username availability check
        let usernameTimeout;
        usernameInput.addEventListener('input', function() {
            clearTimeout(usernameTimeout);
            const username = this.value.trim();
            
            if (username.length < 3) {
                usernameFeedback.textContent = 'Username must be at least 3 characters';
                usernameFeedback.style.color = '#ff8800';
                return;
            }
            
            if (!/^[a-zA-Z0-9_]+$/.test(username)) {
                usernameFeedback.textContent = 'Only letters, numbers, and underscores allowed';
                usernameFeedback.style.color = '#ff4444';
                return;
            }
            
            usernameFeedback.textContent = 'Checking availability...';
            usernameFeedback.style.color = '#ffbb33';
            
            usernameTimeout = setTimeout(() => {
                checkUsernameAvailability(username);
            }, 500);
        });

        function checkUsernameAvailability(username) {
            fetch('check_username.php?username=' + encodeURIComponent(username))
                .then(response => response.json())
                .then(data => {
                    if (data.available) {
                        usernameFeedback.textContent = 'Username is available';
                        usernameFeedback.style.color = '#00C851';
                    } else {
                        usernameFeedback.textContent = 'Username is already taken';
                        usernameFeedback.style.color = '#ff4444';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    usernameFeedback.textContent = 'Error checking username';
                    usernameFeedback.style.color = '#ff4444';
                });
        }

        // Form validation
        const form = document.getElementById('registrationForm');
        const submitBtn = document.getElementById('submitBtn');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate password match
            if (passwordInput.value !== confirmPasswordInput.value) {
                showMessage('Passwords do not match', 'error');
                return;
            }
            
            // Validate password strength
            if (passwordInput.value.length < 8) {
                showMessage('Password must be at least 8 characters', 'error');
                return;
            }
            
            // Validate terms
            if (!document.getElementById('terms').checked) {
                showMessage('You must agree to the terms and conditions', 'error');
                return;
            }
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            submitBtn.disabled = true;
            
            // Submit form
            this.submit();
        });

        function showMessage(text, type) {
            let messageBox = document.getElementById('messageBox');
            if (!messageBox) {
                messageBox = document.createElement('div');
                messageBox.id = 'messageBox';
                messageBox.className = 'message-container fade-in';
                document.querySelector('.registration-right').insertBefore(messageBox, form);
            }
            
            messageBox.className = `message-container ${type} fade-in`;
            messageBox.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${text}`;
            
            setTimeout(() => {
                messageBox.classList.remove('fade-in');
                setTimeout(() => {
                    messageBox.style.display = 'none';
                }, 300);
            }, 5000);
        }

        // Auto-hide messages
        const autoHideMessage = document.querySelector('.message-container');
        if (autoHideMessage) {
            setTimeout(() => {
                autoHideMessage.classList.remove('fade-in');
                setTimeout(() => {
                    autoHideMessage.style.display = 'none';
                }, 300);
            }, 8000);
        }

        // Progress steps highlighting based on scroll
        const sections = document.querySelectorAll('.form-section');
        const steps = document.querySelectorAll('.step');
        
        function updateProgressSteps() {
            const scrollPosition = document.querySelector('.registration-right').scrollTop + 100;
            
            sections.forEach((section, index) => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.offsetHeight;
                
                if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                    steps.forEach(step => step.classList.remove('active'));
                    steps[index].classList.add('active');
                }
            });
        }
        
        document.querySelector('.registration-right').addEventListener('scroll', updateProgressSteps);
        
        // Initialize progress steps
        updateProgressSteps();
        
        // Department code auto-suggestion
        const deptNameInput = document.getElementById('department_name');
        const deptCodeInput = document.getElementById('department_code');
        
        deptNameInput.addEventListener('blur', function() {
            if (deptNameInput.value && !deptCodeInput.value) {
                // Generate simple code from department name
                let code = deptNameInput.value
                    .split(' ')
                    .map(word => word.charAt(0).toUpperCase())
                    .join('')
                    .substring(0, 5);
                
                deptCodeInput.value = code;
            }
        });
    </script>
</body>
</html>