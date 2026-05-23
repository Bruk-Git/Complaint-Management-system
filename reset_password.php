<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
        }
        .box {
            width: 400px;
            margin: 100px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        input, button {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
        }
        button {
            background: #dc3545;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #c82333;
        }
        small {
            color: #666;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Reset Password</h2>

    <form action="process/reset_password_process.php" method="post">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>">

        <label>New Password</label>
        <input type="password" name="password" required>

        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required>

        <small>
            Password must be at least 8 characters, include a number and a symbol.
        </small>

        <button type="submit">Reset Password</button>
    </form>
</div>

</body>
</html>
