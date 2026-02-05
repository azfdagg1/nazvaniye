# nazvaniye
nety

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Login Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 50px;
        }
        .login-container {
            background: white;
            width: 400px;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background-color: #0d2137; /* Темно-синий из твоего примера */
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
        form {
            padding: 20px;
        }
        .input-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
            font-size: 14px;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            background-color: #f9f9f9;
        }
        input[type="submit"] {
            width: 100%;
            background-color: #1a73e8; /* Синяя кнопка */
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #1557b0;
        }
        .footer-link {
            margin-top: 15px;
            font-size: 14px;
        }
        .footer-link a {
            color: #1a73e8;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="header">Login Form</div>
    
    <form action="auth.php" method="POST">
        <div class="input-group">
            <label>Login</label>
            <input type="text" name="login" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <input type="submit" value="Sign In">
    </form>
</div>

<div class="footer-link">
    Нет аккаунта? <a href="reg.php">Зарегистрироваться</a>
</div>

</body>
</html>

