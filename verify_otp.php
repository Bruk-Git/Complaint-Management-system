<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
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
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Verify OTP</h2>

    <form action="process/verify_otp_process.php" method="post">
        <label>Email Address</label>
        <input type="email" name="email" required>

        <label>OTP Code</label>
        <input type="text" name="otp" maxlength="6" required>

        <button type="submit">Verify OTP</button>
    </form>
</div>

</body>
</html>
