<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
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
            background: #003b6f;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #005fa3;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Forgot Password</h2>

    <form action="process/send_otp.php" method="post">
        <label>Email Address</label>
        <input type="email" name="email" required>

        <button type="submit">Send OTP</button>
    </form>
</div>

</body>
</html>
