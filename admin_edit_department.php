<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET['id'];
$result = mysqli_query($conn,"SELECT * FROM department_login WHERE id='$id'");
$dept = mysqli_fetch_assoc($result);

if (!$dept) die("Department not found");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name  = $_POST['department_name'];
    $email = $_POST['email'];

    mysqli_query($conn,"
        UPDATE department_login SET
        department_name='$name',
        email='$email'
        WHERE id='$id'
    ");

    echo "<script>alert('Department updated'); window.location='manage_departments.php';</script>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="icon" href="images/Logos/AU Logo.png">
<title>Admin Edit Department</title>

<style>* {
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

form {
    background: white;
    width: 100%;
    max-width: 500px;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.25);
    border: 1px solid rgba(255, 255, 255, 0.1);
    text-align: center;
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
    width: 90px;
    height: 90px;
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

h3 {
    color: #003b6f;
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 30px;
    position: relative;
    padding-bottom: 15px;
}

h3::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 4px;
    background: linear-gradient(90deg, #003b6f, #4da6ff);
    border-radius: 2px;
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
    margin-bottom: 20px;
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

/* Form field animation */
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

input {
    animation: slideIn 0.5s ease-out forwards;
    opacity: 0;
}

input:nth-child(1) { animation-delay: 0.1s; }
input:nth-child(2) { animation-delay: 0.2s; }
input:nth-child(3) { animation-delay: 0.3s; }
button[type="submit"] { animation-delay: 0.4s; }

/* Label styling (if you add labels later) */
label {
    display: block;
    text-align: left;
    color: #003b6f;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 8px;
    margin-top: 15px;
}

/* Back button styling */
.back-btn {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 24px;
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.back-btn:hover {
    background: linear-gradient(135deg, #495057 0%, #6c757d 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(108, 117, 125, 0.3);
}

/* Success message styling */
.alert {
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
        radial-gradient(circle at 90% 10%, rgba(0, 95, 163, 0.15) 0%, transparent 40%);
    z-index: -1;
}

/* Responsive Design */
@media (max-width: 768px) {
    form {
        padding: 30px 25px;
        margin: 20px;
    }
    
    h3 {
        font-size: 24px;
    }
    
    .log-img {
        width: 80px;
        height: 80px;
    }
    
    input {
        padding: 14px 18px;
        font-size: 15px;
    }
    
    button[type="submit"] {
        padding: 16px;
        font-size: 16px;
    }
}

@media (max-width: 480px) {
    form {
        padding: 25px 20px;
    }
    
    h3 {
        font-size: 22px;
    }
    
    .log-img {
        width: 70px;
        height: 70px;
    }
    
    input {
        padding: 12px 16px;
        font-size: 14px;
    }
    
    button[type="submit"] {
        padding: 14px;
        font-size: 15px;
    }
}
</style>
<body>
    

<form method="post">
    <img class="log-img" src="images/logos/AU Logo.png" alt="AU Logo">
    <h3>Edit Department</h3>
    
    <label>Department Name</label>
    <input name="department_name" value="<?= htmlspecialchars($dept['department_name']); ?>" required>
    
    <label>Username</label>
    <input name="username" value="<?= htmlspecialchars($dept['username']); ?>" required>
    
    <label>Email Address</label>
    <input type="email" name="email" value="<?= htmlspecialchars($dept['email']); ?>" required>
    
    <button type="submit">Update Department</button>
</form>
</body>
</html>