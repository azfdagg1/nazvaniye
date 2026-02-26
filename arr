profile
<?php
session_start();
require_once 'db.php';

// 1. Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. Получение данных пользователя и проверка на Админа
$sql_user = "SELECT n.*, u.IsAdmin FROM `names_db` n 
             RIGHT JOIN `users_db` u ON n.id = u.id 
             WHERE u.id = '$user_id'";
$res_user = mysqli_query($connect, $sql_user);
$userData = mysqli_fetch_assoc($res_user);

// Если данных в names_db нет, создаем заглушку
if (!$userData['name']) {
    $userData['name'] = 'Пользователь';
}

$firstLetter = mb_substr($userData['name'], 0, 1);
$isAdmin = ($userData['IsAdmin'] == 1);

// 3. Получение сообщений
$sql_msg = "SELECT * FROM `messages` WHERE `user_id` = '$user_id' ORDER BY `date_time` ASC";
$res_msg = mysqli_query($connect, $sql_msg);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messenger — Профиль</title>
    <style>
        :root { --primary-gradient: linear-gradient(to right, #4facfe, #00f2fe); }
        body { margin: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; display: flex; height: 100vh; overflow: hidden; background-color: #f0f2f5; }
        .sidebar { width: 350px; border-right: 1px solid #dfe1e5; display: flex; flex-direction: column; background: #fff; }
        .sidebar-header { padding: 15px; display: flex; align-items: center; gap: 15px; border-bottom: 1px solid #f4f4f5; }
        .avatar-circle { width: 42px; height: 42px; border-radius: 50%; background: var(--primary-gradient); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; cursor: pointer; }
        .chat-window { flex: 1; display: flex; flex-direction: column; }
        .chat-header { background: #fff; padding: 12px 25px; display: flex; align-items: center; gap: 15px; border-bottom: 1px solid #dfe1e5; }
        .messages-area { flex: 1; padding: 20px 40px; overflow-y: auto; display: flex; flex-direction: column; gap: 8px; }
        .msg-out { align-self: flex-end; background: #eeffde; padding: 8px 15px; border-radius: 12px 12px 2px 12px; max-width: 70%; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
        .input-area { background: #fff; padding: 15px 30px; display: flex; gap: 15px; }
        .input-area input { flex: 1; padding: 12px; border: 1px solid #dfe1e5; border-radius: 12px; outline: none; }
        .btn-send { background: var(--primary-gradient); color: white; border: none; padding: 12px 25px; border-radius: 12px; cursor: pointer; }
        
        /* Модалка */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); justify-content: center; align-items: center; z-index: 1000; }
        .modal { background: white; width: 360px; border-radius: 25px; padding: 35px; text-align: center; position: relative; }
        .modal-input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 12px; box-sizing: border-box; }
        .btn-save-profile { width: 100%; padding: 14px; background: var(--primary-gradient); color: white; border: none; border-radius: 12px; cursor: pointer; font-weight: bold; margin-top: 10px; }
        .btn-admin { display: block; margin-top: 15px; padding: 10px; background: #f1f9ff; color: #4facfe; text-decoration: none; border-radius: 10px; font-weight: 600; border: 1px solid #4facfe; }
        .btn-logout { color: #ff5e5e; text-decoration: none; display: block; margin-top: 20px; font-size: 14px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <div class="avatar-circle" onclick="toggleModal()"><?php echo $firstLetter; ?></div>
            <div style="font-weight: 600;"><?php echo htmlspecialchars($userData['name']); ?></div>
        </div>
        <div style="padding: 20px; color: #707579;">Избранное</div>
    </div>

    <div class="chat-window">
        <div class="chat-header"><b>Избранное</b></div>
        <div class="messages-area" id="msgBox">
            <?php while($msg = mysqli_fetch_assoc($res_msg)): ?>
                <div class="msg-out">
                    <?php echo htmlspecialchars($msg['text']); ?>
                </div>
            <?php endwhile; ?>
        </div>
        <form action="send_msg.php" method="POST" class="input-area">
            <input type="text" name="message" placeholder="Написать себе..." required>
            <button type="submit" class="btn-send">Отправить</button>
        </form>
    </div>

    <div class="modal-overlay" id="profileModal">
        <div class="modal">
            <span style="position:absolute; right:20px; top:15px; cursor:pointer;" onclick="toggleModal()">✕</span>
            <div class="avatar-circle" style="width: 70px; height: 70px; margin: 0 auto 15px; font-size: 24px;"><?php echo $firstLetter; ?></div>
            
            <form action="update_profile.php" method="POST">
                <input type="text" name="name" class="modal-input" value="<?php echo htmlspecialchars($userData['name']); ?>" placeholder="Имя">
                <input type="text" name="surname" class="modal-input" value="<?php echo htmlspecialchars($userData['surname'] ?? ''); ?>" placeholder="Фамилия">
                <button type="submit" class="btn-save-profile">Сохранить</button>
            </form>

            <?php if ($isAdmin): ?>
                <a href="admin.php" class="btn-admin">🛠 Админ-панель</a>
            <?php endif; ?>

            <a href="logout.php" class="btn-logout">Выйти из аккаунта</a>
        </div>
    </div>

    <script>
admin
<?php
session_start();
require_once 'db.php';

// Проверка прав (повторно для безопасности)
$admin_id = $_SESSION['user_id'];
$check = mysqli_query($connect, "SELECT IsAdmin FROM `users_db` WHERE id = '$admin_id'");
$admin = mysqli_fetch_assoc($check);

if ($admin['IsAdmin'] != 1) { die("Ошибка доступа."); }

$id = $_POST['id'];
$login = mysqli_real_escape_string($connect, $_POST['login']);
$password = mysqli_real_escape_string($connect, $_POST['password']);
$email = mysqli_real_escape_string($connect, $_POST['email']);
$name = mysqli_real_escape_string($connect, $_POST['name']);
$surname = mysqli_real_escape_string($connect, $_POST['surname']);
$birthday = $_POST['birthday'];

// Обновляем таблицу аккаунтов
$sql1 = "UPDATE `users_db` SET login='$login', password='$password', email='$email' WHERE id='$id'";
mysqli_query($connect, $sql1);

// Обновляем таблицу данных профиля
$sql2 = "UPDATE `names_db` SET name='$name', surname='$surname', birthday='$birthday' WHERE id='$id'";
mysqli_query($connect, $sql2);

header("Location: admin.php?success=1");
exit();
        function toggleModal() {
            const modal = document.getElementById('profileModal');
            modal.style.display = (modal.style.display === 'flex') ? 'none' : 'flex';
        }
    </script>
</body>
</html>

admin update
<?php
session_start();
require_once 'db.php';

// Проверка: админ ли это?
$user_id = $_SESSION['user_id'];
$check = mysqli_query($connect, "SELECT IsAdmin FROM `users_db` WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($check);

if ($user['IsAdmin'] != 1) {
    die("Доступ запрещен. Вы не администратор.");
}

// Получаем всех пользователей
$sql = "SELECT u.id, u.login, u.password, u.email, u.IsAdmin, n.name, n.surname, n.birthday 
        FROM `users_db` u 
        LEFT JOIN `names_db` n ON u.id = n.id";
$result = mysqli_query($connect, $sql);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; padding: 20px; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #4facfe; color: white; }
        input { padding: 5px; border: 1px solid #ddd; border-radius: 4px; width: 100%; box-sizing: border-box; }
        .btn-update { background: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer; }
        .back-link { display: inline-block; margin-bottom: 15px; text-decoration: none; color: #4facfe; font-weight: bold; }
    </style>
</head>
<body>

    <a href="profile.php" class="back-link">← Назад в профиль</a>
    <h1>Управление пользователями</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Логин</th>
                <th>Пароль</th>
                <th>Email</th>
                <th>Имя</th>
                <th>Фамилия</th>
                <th>Дата рожд.</th>
                <th>Действие</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <form action="admin_update.php" method="POST">
                    <td><?php echo $row['id']; ?><input type="hidden" name="id" value="<?php echo $row['id']; ?>"></td>
                    <td><input type="text" name="login" value="<?php echo $row['login']; ?>"></td>
                    <td><input type="text" name="password" value="<?php echo $row['password']; ?>"></td>
                    <td><input type="text" name="email" value="<?php echo $row['email']; ?>"></td>
                    <td><input type="text" name="name" value="<?php echo $row['name']; ?>"></td>
                    <td><input type="text" name="surname" value="<?php echo $row['surname']; ?>"></td>
                    <td><input type="date" name="birthday" value="<?php echo $row['birthday']; ?>"></td>
                    <td><button type="submit" class="btn-update">Сохранить</button></td>
                </form>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>
