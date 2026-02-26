<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в Messenger</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(to right, #4facfe, #00f2fe);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-container {
            background: #ffffff;
            border-radius: 28px;
            padding: 45px 35px;
            width: 100%;
            max-width: 340px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            box-sizing: border-box;
        }

        /* Единая иконка звезды */
        .star-container {
            width: 75px;
            height: 75px;
            background: #4facfe;
            margin: 0 auto 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(79, 172, 254, 0.2);
        }

        .star-svg {
            width: 45px;
            height: 45px;
            fill: white;
        }

        h1 {
            font-size: 26px;
            color: #222;
            margin: 0 0 10px;
            font-weight: 700;
        }

        p {
            color: #707579;
            font-size: 15px;
            margin-bottom: 30px;
        }

        .input-group {
            text-align: left;
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            margin-left: 4px;
            font-weight: 500;
            color: #444;
            font-size: 13px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #dfe1e5;
            border-radius: 12px;
            box-sizing: border-box;
            background-color: #f4f4f5;
            font-size: 15px;
            transition: all 0.2s ease;
            outline: none;
        }

        input:focus {
            border-color: #4facfe;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.1);
        }

        input[type="submit"] {
            width: 100%;
            background: #4facfe;
            color: white;
            padding: 16px;
            border: none;
            border-radius: 14px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            margin-top: 10px;
            transition: all 0.2s ease;
        }

        input[type="submit"]:hover {
            opacity: 0.9;
            transform: scale(0.99);
        }

        .footer-link {
            margin-top: 25px;
            font-size: 14px;
            color: #707579;
        }

        .footer-link a {
            color: #4facfe;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="star-container">
        <svg class="star-svg" viewBox="0 0 24 24">
            <path d="M12,1.5L14.5,9.5H22.5L16,14.5L18.5,22.5L12,17.5L5.5,22.5L8,14.5L1.5,9.5H9.5L12,1.5Z" 
                  stroke="white" stroke-width="2" stroke-linejoin="round" stroke-linecap="round"/>
        </svg>
    </div>

    <h1>Messenger</h1>
    <p>С возвращением!</p>
    
    <!-- Изменено на POST для безопасности -->
    <form action="auth.php" method="POST">
        <div class="input-group">
            <label>Login</label>
            <input type="text" name="login" placeholder="Ваш логин" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>

        <input type="submit" value="Войти">
    </form>

    <div class="footer-link">
        Нет аккаунта? <a href="reg.php">Зарегистрироваться</a>
    </div>
</div>

</body>
</html>
