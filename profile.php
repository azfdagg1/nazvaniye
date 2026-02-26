<?php
session_start();
$connect = mysqli_connect('localhost', 'root', '', 'Messenger');

// 1. Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. Получение данных пользователя для профиля
$sql_user = "SELECT * FROM `names_db` WHERE `id` = '$user_id'";
$res_user = mysqli_query($connect, $sql_user);
$userData = mysqli_fetch_assoc($res_user);

// Если данных в names_db нет (первый вход), создаем заглушку
if (!$userData) {
    $userData = ['name' => 'Пользователь', 'surname' => '', 'birthday' => ''];
}

$firstLetter = mb_substr($userData['name'], 0, 1);

// 3. Получение сообщений из "Избранного"
$sql_msg = "SELECT * FROM `messages` WHERE `user_id` = '$user_id' ORDER BY `date_time` ASC";
$res_msg = mysqli_query($connect, $sql_msg);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messenger — Избранное</title>
    <style>
        :root {
            --primary-gradient: linear-gradient(to right, #4facfe, #00f2fe);
            --sidebar-width: 350px;
        }

        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            display: flex;
            height: 100vh;
            overflow: hidden;
            background-color: #fff;
        }

        /* САЙДБАР */
        .sidebar {
            width: var(--sidebar-width);
            border-right: 1px solid #dfe1e5;
            display: flex;
            flex-direction: column;
            background: #fff;
        }

        .sidebar-header {
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-bottom: 1px solid #f4f4f5;
        }

        .avatar-circle {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: var(--primary-gradient);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 3px 6px rgba(79,172,254,0.2);
            flex-shrink: 0;
        }

        .chats-list { flex: 1; overflow-y: auto; }
        
        .chat-item {
            display: flex;
            padding: 15px;
            gap: 15px;
            cursor: pointer;
            background: #3390ec; /* Активен по умолчанию, так как мы в Избранном */
            color: white;
        }

        .chat-info h4 { margin: 0; font-size: 16px; }
        .chat-info p { margin: 4px 0 0; font-size: 13px; opacity: 0.8; }

        /* ОКНО ЧАТА */
        .chat-window {
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: #f0f2f5;
            background-image: url('https://web.telegram.org'); /* Светлый паттерн */
        }

        .chat-header {
            background: #fff;
            padding: 12px 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-bottom: 1px solid #dfe1e5;
        }

        .messages-area {
            flex: 1;
            padding: 20px 40px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .msg-out {
            align-self: flex-end;
            background: #eeffde;
            padding: 8px 15px;
            border-radius: 12px 12px 2px 12px;
            max-width: 70%;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
            position: relative;
        }

        .msg-time {
            font-size: 10px;
            color: #65a15a;
            text-align: right;
            margin-top: 4px;
        }

        /* ФОРМА ОТПРАВКИ */
        .input-area {
            background: #fff;
            padding: 15px 30px;
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .input-area input {
            flex: 1;
            padding: 12px 18px;
            border: 1px solid #dfe1e5;
            border-radius: 12px;
            outline: none;
            background: #f4f4f5;
            font-size: 15px;
        }

        .btn-send {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        /* МОДАЛЬНОЕ ОКНО */
        .modal-overlay {
            display: <?php echo isset($_GET['success']) ? 'flex' : 'none'; ?>;
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.4); justify-content: center; align-items: center; z-index: 1000;
        }

        .modal {
            background: white; width: 360px; border-radius: 25px; padding: 35px; text-align: center; position: relative;
        }

        .modal-input {
            width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 12px; box-sizing: border-box; outline: none;
        }

        .modal-input:focus { border-color: #4facfe; }

        .btn-save-profile {
            width: 100%; padding: 14px; background: var(--primary-gradient); color: white; border: none; border-radius: 12px; cursor: pointer; font-weight: bold; margin-top: 15px;
        }

        .btn-logout { color: #ff5e5e; text-decoration: none; display: block; margin-top: 25px; font-weight: 600; }
    </style>
</head>
<body>

    <!-- Сайдбар -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="avatar-circle" onclick="toggleModal()"><?php echo $firstLetter; ?></div>
            <div style="font-weight: 600; font-size: 17px;"><?php echo htmlspecialchars($userData['name']); ?></div>
        </div>
        
        <div class="chats-list">
            <div class="chat-item">
                <div class="avatar-circle" style="background: #fff; color: #3390ec;">★</div>
                <div class="chat-info">
                    <h4>Избранное</h4>
                    <p>Личные заметки</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Окно чата -->
    <div class="chat-window">
        <div class="chat-header">
            <div class="avatar-circle" style="width: 36px; height: 36px;">★</div>
            <div style="font-weight: 600;">Избранное</div>
        </div>

        <div class="messages-area" id="msgBox">
            <?php if (mysqli_num_rows($res_msg) == 0): ?>
                <div style="text-align: center; color: #707579; margin-top: 50px;">Здесь пока нет сообщений...</div>
            <?php endif; ?>

            <?php while($msg = mysqli_fetch_assoc($res_msg)): ?>
                <div class="msg-out">
                    <?php echo htmlspecialchars($msg['text']); ?>
                    <div class="msg-time"><?php echo date('H:i', strtotime($msg['date_time'])); ?></div>
                </div>
            <?php endwhile; ?>
        </div>

        <form action="send_msg.php" method="POST" class="input-area">
            <input type="text" name="message" placeholder="Написать себе..." required autocomplete="off">
            <button type="submit" class="btn-send">Отправить</button>
        </form>
    </div>

    <!-- Настройки профиля (Модалка) -->
    <div class="modal-overlay" id="profileModal">
        <div class="modal">
            <span style="position:absolute; right:25px; top:20px; cursor:pointer; font-size: 20px; color: #ccc;" onclick="toggleModal()">✕</span>
            
            <?php if(isset($_GET['success'])): ?>
                <div style="background: #e7f9ed; color: #1db954; padding: 10px; border-radius: 12px; margin-bottom: 20px; font-size: 14px;">
                    ✅ Изменения сохранены!
                </div>
            <?php endif; ?>

            <div class="avatar-circle" style="width: 80px; height: 80px; margin: 0 auto 15px; font-size: 28px;">
                <?php echo $firstLetter; ?>
            </div>
            
            <form action="update_profile.php" method="POST">
                <input type="text" name="name" class="modal-input" placeholder="Имя" value="<?php echo htmlspecialchars($userData['name']); ?>" required>
                <input type="text" name="surname" class="modal-input" placeholder="Фамилия" value="<?php echo htmlspecialchars($userData['surname']); ?>" required>
                <input type="date" name="birthday" class="modal-input" value="<?php echo $userData['birthday']; ?>" required>
                
                <button type="submit" class="btn-save-profile">Сохранить данные</button>
            </form>

            <a href="logout.php" class="btn-logout">Выйти из профиля</a>
        </div>
    </div>

    <script>
        // Прокрутка чата вниз
        const msgBox = document.getElementById('msgBox');
        msgBox.scrollTop = msgBox.scrollHeight;

        // Открытие/закрытие профиля
        function toggleModal() {
            const modal = document.getElementById('profileModal');
            modal.style.display = (modal.style.display === 'flex') ? 'none' : 'flex';
        }
    </script>
</body>
</html>
