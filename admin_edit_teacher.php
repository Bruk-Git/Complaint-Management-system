<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) die("Invalid teacher.");

$result = mysqli_query($conn, "SELECT * FROM teacher_login WHERE id='$id'");
$teacher = mysqli_fetch_assoc($result);

if (!$teacher) die("Teacher not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = $_POST['teacher_name'];
    $email = $_POST['email'];
    $dept  = $_POST['department'];
    $phone = $_POST['phone'];

    mysqli_query($conn, "
        UPDATE teacher_login SET
            teacher_name='$name',
            email='$email',
            department='$dept',
            phone='$phone'
        WHERE id='$id'
    ");

    echo "<script>alert('Teacher updated');window.location='manage_teachers.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="images/Logos/AU Logo.png">
<title>Edit Teacher</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.form-container {
    background: white;
    width: 100%;
    max-width: 500px;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.25);
    border: 1px solid rgba(255, 255, 255, 0.1);
    animation: fadeIn 0.8s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
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
    box-shadow: 0 10px 30px rgba(0, 59, 111, 0.3);
    transition: all 0.4s ease;
}

.log-img:hover {
    transform: scale(1.05) rotate(5deg);
    box-shadow: 0 15px 40px rgba(0, 59, 111, 0.4);
}

h2 {
    text-align: center;
    color: #003b6f;
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 30px;
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
    background: linear-gradient(90deg, #003b6f, #4da6ff);
    border-radius: 2px;
}

form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

label {
    color: #003b6f;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 5px;
    display: block;
}

input {
    width: 100%;
    padding: 16px 20px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: #f8fafc;
    color: #333;
    box-sizing: border-box;
}

input:focus {
    outline: none;
    border-color: #003b6f;
    box-shadow: 0 0 0 3px rgba(0, 59, 111, 0.1);
    background: white;
    transform: translateY(-2px);
}

input::placeholder {
    color: #999;
}

button[type="submit"] {
    width: 100%;
    padding: 18px;
    background: linear-gradient(135deg, #003b6f 0%, #005fa3 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 18px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    margin-top: 10px;
    position: relative;
    overflow: hidden;
}

button[type="submit"]::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, 
        transparent, 
        rgba(255, 255, 255, 0.3), 
        transparent);
    transition: left 0.7s;
}

button[type="submit"]:hover::before {
    left: 100%;
}

button[type="submit"]:hover {
    background: linear-gradient(135deg, #005fa3 0%, #007acc 100%);
    transform: translateY(-3px);
    box-shadow: 0 15px 30px rgba(0, 95, 163, 0.3);
}

button[type="submit"]:active {
    transform: translateY(-1px);
}

.back-link {
    display: block;
    text-align: center;
    margin-top: 25px;
    padding: 12px;
    color: #003b6f;
    text-decoration: none;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
    background: #f8fafc;
    border: 2px solid #e0e0e0;
}

.back-link:hover {
    background: #e9f7fe;
    border-color: #003b6f;
    color: #005fa3;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 59, 111, 0.1);
}

.back-link::before {
    content: '←';
    margin-right: 8px;
    font-weight: bold;
}

/* Animation for form elements */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

form label, form input, form button {
    animation: slideIn 0.5s ease-out forwards;
    opacity: 0;
}

form label:nth-child(1) { animation-delay: 0.1s; }
form input:nth-child(2) { animation-delay: 0.2s; }
form label:nth-child(3) { animation-delay: 0.3s; }
form input:nth-child(4) { animation-delay: 0.4s; }
form label:nth-child(5) { animation-delay: 0.5s; }
form input:nth-child(6) { animation-delay: 0.6s; }
form label:nth-child(7) { animation-delay: 0.7s; }
form input:nth-child(8) { animation-delay: 0.8s; }
form button[type="submit"] { animation-delay: 0.9s; }

/* Background pattern */
body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: 
        radial-gradient(circle at 10% 90%, rgba(77, 166, 255, 0.15) 0%, transparent 40%),
        radial-gradient(circle at 90% 10%, rgba(0, 95, 163, 0.15) 0%, transparent 40%),
        radial-gradient(circle at 50% 50%, rgba(0, 59, 111, 0.1) 0%, transparent 60%);
    z-index: -1;
    animation: float 20s infinite ease-in-out;
}

@keyframes float {
    0%, 100% { transform: translate(0, 0) scale(1); }
    25% { transform: translate(-10px, 10px) scale(1.02); }
    50% { transform: translate(10px, -10px) scale(0.98); }
    75% { transform: translate(-10px, -10px) scale(1.01); }
}

/* Success message styling */
.success-message {
    position: fixed;
    top: 20px;
    right: 20px;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 15px 25px;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(40, 167, 69, 0.3);
    z-index: 1000;
    animation: slideInRight 0.5s ease-out;
    display: none;
}

.success-message.show {
    display: block;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-container {
        padding: 30px 25px;
        margin: 20px;
    }
    
    h2 {
        font-size: 28px;
    }
    
    .log-img {
        width: 90px;
        height: 90px;
    }
    
    input {
        padding: 14px 18px;
        font-size: 15px;
    }
    
    button[type="submit"] {
        padding: 16px;
        font-size: 16px;
    }
    
    .back-link {
        padding: 10px;
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .form-container {
        padding: 25px 20px;
    }
    
    h2 {
        font-size: 24px;
    }
    
    .log-img {
        width: 80px;
        height: 80px;
    }
    
    input {
        padding: 12px 16px;
        font-size: 14px;
    }
    
    button[type="submit"] {
        padding: 14px;
        font-size: 15px;
    }
    
    .back-link {
        padding: 8px;
        font-size: 13px;
    }
}

/* Loading state for button */
button[type="submit"].loading {
    position: relative;
    color: transparent;
}

button[type="submit"].loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 20px;
    height: 20px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: translate(-50%, -50%) rotate(360deg); }
}

/* Optional: Add some icons to labels */
label::before {
    content: '📝';
    margin-right: 8px;
}

label[for="email"]::before { content: '📧'; }
label[for="department"]::before { content: '🏫'; }
label[for="phone"]::before { content: '📱'; }
</style>
</head>

<body>

<div class="form-container">
    <img class="log-img" src="images/logos/AU Logo.png" alt="AU Logo">
    <h2>Edit Teacher</h2>

    <form method="post">
        <label>Teacher Name</label>
        <input name="teacher_name" value="<?= $teacher['teacher_name']; ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= $teacher['email']; ?>" required>

        <label>Department</label>
        <input name="department" value="<?= $teacher['department']; ?>" required>

        <label>Phone</label>
        <input name="phone" value="<?= $teacher['phone']; ?>">

        <button type="submit">Update Teacher</button>
    </form>

    <a href="manage_teachers.php" class="back-link">← Back to Teachers</a>
</div>

</body>
</html>
