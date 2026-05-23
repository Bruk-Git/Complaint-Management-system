<?php
session_start();
include "Connection.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
$id = $_GET['id'];
$q = mysqli_query($conn, "SELECT * FROM dean_login WHERE id='$id'");
$dean = mysqli_fetch_assoc($q);

if (!$dean) die("Dean not found");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name  = $_POST['dean_name'];
    $email = $_POST['email'];

    mysqli_query($conn, "
        UPDATE dean_login SET
        dean_name='$name',
        email='$email'
        WHERE id='$id'
    ");

    echo "<script>alert('Dean updated'); window.location='manage_deans.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head><title>Edit Dean</title></head>
<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0c2461 0%, #1e3799 50%, #4a69bd 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 500px;
            animation: fadeIn 0.8s ease-out;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #fff;
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .header p {
            color: #dff9fb;
            font-size: 1.1rem;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            padding: 40px;
            transition: transform 0.3s ease;
        }

        .form-container:hover {
            transform: translateY(-5px);
        }

        .form-header {
            background: linear-gradient(to right, #1e3799, #4a69bd);
            color: white;
            padding: 20px;
            margin: -40px -40px 30px -40px;
            text-align: center;
            border-bottom: 5px solid #0c2461;
        }

        .form-header h2 {
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #1e3799;
            font-weight: 600;
            font-size: 1rem;
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: 40px;
            color: #4a69bd;
            font-size: 1.1rem;
        }

        .form-group input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #a4b0be;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            background-color: #f8f9fa;
        }

        .form-group input:focus {
            outline: none;
            border-color: #1e3799;
            box-shadow: 0 0 0 3px rgba(30, 55, 153, 0.2);
            background-color: #fff;
        }

        .form-group input:required:valid {
            border-color: #2ecc71;
        }

        button {
            background: linear-gradient(to right, #1e3799, #4a69bd);
            color: white;
            border: none;
            padding: 16px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }

        button:hover {
            background: linear-gradient(to right, #0c2461, #1e3799);
            box-shadow: 0 5px 15px rgba(30, 55, 153, 0.4);
            transform: translateY(-2px);
        }

        button:active {
            transform: translateY(1px);
        }

        .info-note {
            background-color: #dff9fb;
            border-left: 4px solid #4a69bd;
            padding: 15px;
            border-radius: 0 8px 8px 0;
            margin-top: 25px;
            color: #0c2461;
            font-size: 0.9rem;
        }

        .info-note i {
            margin-right: 8px;
            color: #1e3799;
        }

        .university-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .logo-circle {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #1e3799, #4a69bd);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .logo-circle i {
            color: white;
            font-size: 2.5rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            .form-container {
                padding: 30px 25px;
            }
            
            .form-header {
                margin: -30px -25px 25px -25px;
                padding: 18px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .container {
                padding: 10px;
            }
        }
    </style>
<body>

<div class="container">
        <div class="header">
            <div class="university-logo">
                <div class="logo-circle">
                    <i class="fas fa-university"></i>
                </div>
            </div>
            <h1>University Administration</h1>
            <p>Dean Management System</p>
        </div>
        
        <div class="form-container">
            <div class="form-header">
                <h2><i class="fas fa-user-edit"></i> Edit Dean</h2>
            </div>
            
            <form method="post" id="editDeanForm">
                <div class="form-group">
                    <label for="dean_name">Dean Name</label>
                    <i class="fas fa-user-tie"></i>
                    <input type="text" id="dean_name" name="dean_name" value="<?= htmlspecialchars($dean['dean_name']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($dean['email']) ?>" required>
                </div>
                
                <button type="submit">
                    <i class="fas fa-sync-alt"></i> Update Dean
                </button>
            </form>
            
            <div class="info-note">
                <p><i class="fas fa-info-circle"></i> This form updates dean information in the university database. All changes are logged for administrative review.</p>
            </div>
        </div>
    </div>


</body>
</html>
