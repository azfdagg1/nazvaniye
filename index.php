<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messenger</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(to right, #4facfe, #00f2fe);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .login-card {
            background: #ffffff;
            border-radius: 28px;
            padding: 45px 35px;
            width: 100%;
            max-width: 320px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        /* Новая мягкая звезда */
        .star-container {
            width: 90px;
            height: 90px;
            background: #4facfe;
            margin: 0 auto 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 22px; /* Мягкий квадрат-подложка, как у iOS иконок */
            box-shadow: 0 10px 20px rgba(79, 172, 254, 0.3);
        }

        .star-svg {
            width: 55px;
            height: 55px;
            fill: white;
        }

        h1 {
            font-size: 26px;
            margin: 0 0 10px;
            color: #222;
            font-weight: 700;
        }

        p {
            color: #707579;
            font-size: 15px;
            line-height: 1.4;
            margin-bottom: 35px;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 16px 0;
            border-radius: 14px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }

        .btn-login {
            background: #4facfe;
            color: white;
            margin-bottom: 12px;
        }

        .btn-login:hover {
            opacity: 0.9;
            transform: scale(0.98);
        }

        .btn-reg {
            color: #4facfe;
            background: #f1f9ff;
        }

        .btn-reg:hover {
            background: #e3f2fd;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Контейнер с иконкой -->
        <div class="star-container">
            <svg class="star-svg" viewBox="0 0 24 24">
                <!-- Путь максимально скругленной звезды -->
                <path d="M12,1.5L14.5,9.5H22.5L16,14.5L18.5,22.5L12,17.5L5.5,22.5L8,14.5L1.5,9.5H9.5L12,1.5Z" 
                      stroke="white" stroke-width="2" stroke-linejoin="round" stroke-linecap="round"/>
            </svg>
        </div>

        <h1>Messenger</h1>
        <p>Добро пожаловать. Пожалуйста, авторизуйтесь.</p>

        <a href="login.php" class="btn btn-login">Войти</a>
        <a href="reg.php" class="btn btn-reg">Регистрация</a>
    </div>

</body>
</html>
