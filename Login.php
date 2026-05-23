<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Admas Universty
           
        </title> <link rel="icon" href="images/Logos/AU Logo.png">
        
         <link rel="stylesheet" href="CSS/StudentLogin.css">

         <link rel="preconnect" href="https://fonts.googleapis.com">
         <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
         <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
         <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        </head>
        <style>* {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 400px;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 59, 111, 0.1);
            text-align: center;
        }

        .log-img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            margin-bottom: 15px;
            border: 2px solid #003b6f;
            padding: 5px;
        }

        h1 {
            color: #003b6f;
            font-size: 24px;
            margin-bottom: 25px;
            font-weight: 600;
        }

        #Login-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .input-box {
            position: relative;
        }

        .input-box input {
            width: 100%;
            padding: 14px 15px;
            border: 2px solid #e0e6ed;
            border-radius: 8px;
            font-size: 14px;
            color: #333;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .input-box input:focus {
            outline: none;
            border-color: #003b6f;
            background: white;
            box-shadow: 0 0 0 3px rgba(0, 59, 111, 0.1);
        }

        .input-box input::placeholder {
            color: #8a9aad;
        }

        .Remember-Forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
        }

        .Remember-Forgot label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #5a6c7d;
            cursor: pointer;
        }

        .Remember-Forgot input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #003b6f;
            cursor: pointer;
        }

        .Remember-Forgot a {
            color: #003b6f;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .Remember-Forgot a:hover {
            color: #0056a8;
            text-decoration: underline;
        }

        .btn button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #003b6f, #0056a8);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn button:hover {
            background: linear-gradient(135deg, #0056a8, #003b6f);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 59, 111, 0.2);
        }

        .btn button:active {
            transform: translateY(0);
        }

        .Back-to-staff {
            margin-top: 20px;
            font-size: 14px;
        }

        .Back-to-staff a {
            color: #003b6f;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .Back-to-staff a:hover {
            color: #0056a8;
            text-decoration: underline;
        }

        .register-link {
            margin-top: 20px;
            font-size: 14px;
            color: #5a6c7d;
        }

        .register-link a {
            color: #003b6f;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .register-link a:hover {
            color: #0056a8;
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .container {
                padding: 25px 20px;
                margin: 10px;
            }
            
            h1 {
                font-size: 22px;
            }
            
            .input-box input {
                padding: 12px 14px;
            }
            
            .Remember-Forgot {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
        }</style>
    <body>

    <div class="container">
    <img class="log-img" src="images/logos/AU Logo.png">
    <h1>Student Login Page</h1>

    <form action="loginProcess.php" method="post" id="Login-form">

        <div class="input-box">
            <input id="email" type="email" name="email" placeholder="Email" required>
            
        </div>

        <div class="input-box">
            <input type="password" id="password" name="password" placeholder="Password" required>
            <!-- Show/Hide button will be added by JavaScript -->
        </div>
        
        <div class="Remember-Forgot">
            <label><input type="checkbox">Remember Me</label>
            <a href="forgot_password.php">Forgot password?</a>
        </div>
 <div class="btn">
            <button type="submit">Login</button>
        </div>
       
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wrap password input in container for show/hide functionality
    const passwordInput = document.querySelector('input[type="password"]');
    const passwordBox = passwordInput.closest('.input-box');
    
    // Create toggle button
    const toggleButton = document.createElement('button');
    toggleButton.type = 'button';
    toggleButton.className = 'toggle-password';
    toggleButton.setAttribute('aria-label', 'Show password');
    
    // Wrap password input in container
    const passwordContainer = document.createElement('div');
    passwordContainer.className = 'password-container';
    passwordBox.insertBefore(passwordContainer, passwordInput);
    passwordContainer.appendChild(passwordInput);
    passwordContainer.appendChild(toggleButton);
    
    // Toggle password visibility
    toggleButton.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Toggle showing class
        this.classList.toggle('showing');
        
        // Update aria label
        this.setAttribute('aria-label', type === 'text' ? 'Hide password' : 'Show password');
        
        // Add animation effect
        this.style.transform = 'translateY(-50%) scale(1.2)';
        setTimeout(() => {
            this.style.transform = 'translateY(-50%) scale(1)';
        }, 200);
        
        // Keep focus on password field
        passwordInput.focus();
    });
    
    // Change lock icon when password is visible
    const lockIcon = passwordContainer.querySelector('.bx-lock-alt');
    passwordInput.addEventListener('input', function() {
        if (lockIcon && this.value.length > 0) {
            if (this.type === 'text') {
                lockIcon.classList.remove('bx-lock-alt');
                lockIcon.classList.add('bx-lock-open-alt');
            } else {
                lockIcon.classList.remove('bx-lock-open-alt');
                lockIcon.classList.add('bx-lock-alt');
            }
        }
    });
});
</script>

</body>
</html>