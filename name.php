<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Настройка профиля — Messenger</title>
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

        .info-container {
            background: #ffffff;
            border-radius: 28px;
            padding: 40px 30px;
            width: 100%;
            max-width: 360px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            box-sizing: border-box;
        }

        .star-container {
            width: 70px;
            height: 70px;
            background: #4facfe;
            margin: 0 auto 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 18px;
            box-shadow: 0 8px 16px rgba(79, 172, 254, 0.2);
        }

        .star-svg {
            width: 40px;
            height: 40px;
            fill: white;
        }

        h1 {
            font-size: 24px;
            color: #222;
            margin-bottom: 8px;
            font-weight: 700;
        }

        p {
            color: #707579;
            font-size: 14px;
            margin-bottom: 25px;
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
            color: #333;
            font-size: 13px;
        }

        input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #dfe1e5;
            border-radius: 12px;
            box-sizing: border-box;
            background-color: #f4f4f5;
            font-size: 15px;
            transition: all 0.2s ease;
            outline: none;
            font-family: inherit;
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
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

<div class="info-container">
    <div class="star-container">
        <svg class="star-svg" viewBox="0 0 24 24">
            <path d="M12,1.5L14.5,9.5H22.5L16,14.5L18.5,22.5L12,17.5L5.5,22.5L8,14.5L1.5,9.5H9.5L12,1.5Z" 
                  stroke="white" stroke-width="2" stroke-linejoin="round" stroke-linecap="round"/>
        </svg>
    </div>

    <h1>О себе</h1>
    <p>Почти готово! Заполните данные профиля.</p>

    <form action="registr2.php" method="POST">
        <div class="input-group">
            <label>Имя</label>
            <input type="text" name="name" placeholder="Введите имя" required>
        </div>

        <div class="input-group">
            <label>Фамилия</label>
            <input type="text" name="surname" placeholder="Введите фамилию" required>
        </div>

        <div class="input-group">
            <label>Дата рождения</label>
            <input type="date" name="bithday" required>
        </div>

        <input type="submit" value="Завершить">
    </form>
</div>

</body>
</html>
