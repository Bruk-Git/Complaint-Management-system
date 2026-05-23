<?php
session_start();

// Prevent access without login
if (!isset($_SESSION["user_id"])) {
    header("Location:login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/Logos/AU Logo.png">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="CSS/dashboard.css">

</head>
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

.dashboard-container {
    background: white;
    width: 100%;
    max-width: 800px;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.25);
    border: 1px solid rgba(255, 255, 255, 0.1);
    text-align: center;
}

.log-img {
    display: block;
    margin: 0 auto 30px;
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 5px solid #003b6f;
    padding: 8px;
    background: white;
    box-shadow: 0 10px 30px rgba(0, 59, 111, 0.3);
    transition: all 0.4s ease;
}

.log-img:hover {
    transform: scale(1.05) rotate(5deg);
    box-shadow: 0 15px 40px rgba(0, 59, 111, 0.4);
}

h2 {
    color: #003b6f;
    font-size: 34px;
    font-weight: 800;
    margin-bottom: 30px;
    position: relative;
    padding-bottom: 20px;
    animation: fadeIn 0.8s ease-out;
}

h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 5px;
    background: linear-gradient(90deg, #003b6f, #4da6ff, #003b6f);
    border-radius: 3px;
}

.info-box {
    background: linear-gradient(135deg, #f0f7ff 0%, #e6f2ff 100%);
    padding: 30px;
    border-radius: 15px;
    margin: 30px 0;
    border: 2px solid #e0f0ff;
    box-shadow: 0 10px 25px rgba(0, 95, 163, 0.1);
    animation: slideUp 0.6s ease-out 0.2s both;
}

.info-box p {
    font-size: 18px;
    color: #00509e;
    margin: 15px 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s ease;
}

.info-box p:hover {
    transform: translateX(5px);
    color: #003b6f;
}

.info-box strong {
    color: #003b6f;
    font-weight: 700;
    min-width: 140px;
    text-align: right;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 40px;
    animation: slideUp 0.6s ease-out 0.4s both;
}

.btn {
    padding: 20px;
    border-radius: 12px;
    font-size: 18px;
    font-weight: 700;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    border: none;
    cursor: pointer;
}

.btn::before {
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

.btn:hover::before {
    left: 100%;
}

.btn-complain {
    background: linear-gradient(135deg, #0078d4 0%, #005fa3 100%);
    color: white;
    box-shadow: 0 10px 25px rgba(0, 120, 212, 0.3);
}

.btn-complain:hover {
    background: linear-gradient(135deg, #005fa3 0%, #0078d4 100%);
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 120, 212, 0.4);
}

.btn-status {
    background: linear-gradient(135deg, #17a2b8 0%, #0dcaf0 100%);
    color: white;
    box-shadow: 0 10px 25px rgba(23, 162, 184, 0.3);
}

.btn-status:hover {
    background: linear-gradient(135deg, #0dcaf0 0%, #17a2b8 100%);
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(23, 162, 184, 0.4);
}

.btn-logout {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    box-shadow: 0 10px 25px rgba(220, 53, 69, 0.3);
}

.btn-logout:hover {
    background: linear-gradient(135deg, #c82333 0%, #dc3545 100%);
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(220, 53, 69, 0.4);
}

/* Emoji styling */
h2 span, .info-box strong span {
    font-size: 24px;
    display: inline-block;
    animation: wave 2s infinite;
}

@keyframes wave {
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(10deg); }
    75% { transform: rotate(-10deg); }
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .dashboard-container {
        padding: 30px 25px;
        margin: 20px;
    }
    
    h2 {
        font-size: 28px;
    }
    
    .log-img {
        width: 100px;
        height: 100px;
    }
    
    .info-box {
        padding: 25px 20px;
    }
    
    .info-box p {
        font-size: 16px;
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
        margin: 12px 0;
        padding-left: 20px;
    }
    
    .info-box strong {
        min-width: auto;
        text-align: left;
        margin-bottom: 5px;
        font-size: 14px;
    }
    
    .btn {
        padding: 18px;
        font-size: 16px;
    }
    
    .actions {
        grid-template-columns: 1fr;
        gap: 15px;
    }
}

@media (max-width: 480px) {
    .dashboard-container {
        padding: 25px 20px;
    }
    
    h2 {
        font-size: 24px;
    }
    
    .log-img {
        width: 80px;
        height: 80px;
    }
    
    .info-box {
        padding: 20px 15px;
    }
    
    .info-box p {
        font-size: 15px;
        padding-left: 15px;
    }
    
    .btn {
        padding: 16px;
        font-size: 15px;
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
        radial-gradient(circle at 20% 80%, rgba(77, 166, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(0, 95, 163, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(0, 59, 111, 0.1) 0%, transparent 50%);
    z-index: -1;
    animation: float 20s infinite ease-in-out;
}

@keyframes float {
    0%, 100% { transform: translate(0, 0); }
    25% { transform: translate(-10px, 10px); }
    50% { transform: translate(10px, -10px); }
    75% { transform: translate(-10px, -10px); }
}
</style>
<link rel="icon" href="images/Logos/AU Logo.png">
<body>

<div class="dashboard-container">
<img class="log-img" src="images/logos/AU Logo.png" alt="AU Logo">
    <h2>Welcome, <?php echo $_SESSION["full_name"]; ?> 👋</h2>

    <div class="info-box">
        <p><strong>Student ID: 🆔</strong> <?php echo $_SESSION["student_id"]; ?></p>
        <p><strong>Department: 🏫</strong> <?php echo $_SESSION["department"]; ?></p>
    </div>

    <div class="actions">
        <a href="Complain.php" class="btn btn-complain">Submit a Complaint</a>
        <a href="complain_status.php" class="btn btn-status">View Complaint Status</a>
        <a href="login.php" class="btn btn-logout">Logout</a>
    </div>

</div>

</body>
</html>
