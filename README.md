# nazvaniye
nety

admin_control.php

<?php
session_start();
[span_1](start_span)$connect = mysqli_connect('localhost', 'root', '', 'Messenger');[span_1](end_span)

// 1. Проверка прав (Безопасность)
if (!isset($_SESSION['user_id'])) {
    [span_2](start_span)die("Доступ запрещен: авторизуйтесь.");[span_2](end_span)
}

[span_3](start_span)$current_user_id = $_SESSION['user_id'];[span_3](end_span)
[span_4](start_span)$check_admin = mysqli_query($connect, "SELECT `IsAdmin` FROM `names_db` WHERE `id` = '$current_user_id'");[span_4](end_span)
[span_5](start_span)$admin_data = mysqli_fetch_assoc($check_admin);[span_5](end_span)

if (!$admin_data || $admin_data['IsAdmin'] != 1) {
    die("У вас нет прав администратора для этого действия.");
}

// 2. Обработка действий
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $target_id = mysqli_real_escape_string($connect, $_POST['user_id']);
    $action = $_POST['action'];

    if ($action === 'delete') {
        [span_6](start_span)// Удаление пользователя из основной таблицы[span_6](end_span)
        mysqli_query($connect, "DELETE FROM `names_db` WHERE `id` = '$target_id'");
        [span_7](start_span)// Опционально: удаление сообщений пользователя[span_7](end_span)
        mysqli_query($connect, "DELETE FROM `messages` WHERE `user_id` = '$target_id'");
    } 
    
    elseif ($action === 'update') {
        $new_name = mysqli_real_escape_string($connect, $_POST['name']);
        $new_email = mysqli_real_escape_string($connect, $_POST['email']);
        $new_password = $_POST['password'];

        [span_8](start_span)// Обновляем имя и почту[span_8](end_span)
        $sql_update = "UPDATE `names_db` SET `name` = '$new_name', `email` = '$new_email' WHERE `id` = '$target_id'";
        mysqli_query($connect, $sql_update);

        // Если введен новый пароль, обновляем и его
        if (!empty($new_password)) {
            // Внимание: в реальном проекте используйте password_hash()!
            mysqli_query($connect, "UPDATE `names_db` SET `password` = '$new_password' WHERE `id` = '$target_id'");
        }
    }
}

profile.php:
<?php
session_start();
[span_0](start_span)$connect = mysqli_connect('localhost', 'root', '', 'Messenger');[span_0](end_span)

// 1. Проверка авторизации
[span_1](start_span)if (!isset($_SESSION['user_id'])) {[span_1](end_span)
    [span_2](start_span)header("Location: login.php");[span_2](end_span)
    [span_3](start_span)exit();[span_3](end_span)
[span_4](start_span)}

$user_id = $_SESSION['user_id'];[span_4](end_span)

// 2. Получение данных текущего пользователя (включая флаг IsAdmin)
[span_5](start_span)$sql_user = "SELECT * FROM `names_db` WHERE `id` = '$user_id'";[span_5](end_span)
[span_6](start_span)$res_user = mysqli_query($connect, $sql_user);[span_6](end_span)
[span_7](start_span)$userData = mysqli_fetch_assoc($res_user);[span_7](end_span)

[span_8](start_span)if (!$userData) {[span_8](end_span)
    [span_9](start_span)$userData = ['name' => 'Пользователь', 'surname' => '', 'birthday' => '', 'IsAdmin' => 0];[span_9](end_span)
[span_10](start_span)}

$isAdmin = (isset($userData['IsAdmin']) && $userData['IsAdmin'] == 1);
$firstLetter = mb_substr($userData['name'], 0, 1);[span_10](end_span)

// 3. Получение списка всех пользователей для админ-панели (если пользователь админ)
$allUsers = null;
if ($isAdmin) {
    $allUsers = mysqli_query($connect, "SELECT * FROM `names_db` WHERE `id` != '$user_id'");
}

// 4. Получение сообщений из "Избранного"
[span_11](start_span)$sql_msg = "SELECT * FROM `messages` WHERE `user_id` = '$user_id' ORDER BY `date_time` ASC";[span_11](end_span)
[span_12](start_span)$res_msg = mysqli_query($connect, $sql_msg);[span_12](end_span)
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messenger — <?php echo $isAdmin ? 'Администрирование' : 'Избранное'; ?></title>
    <style>
        :root {
            -[span_13](start_span)-primary-gradient: linear-gradient(to right, #4facfe, #00f2fe);[span_13](end_span)
            --admin-gradient: linear-gradient(to right, #f85032, #e73827);
            -[span_14](start_span)-sidebar-width: 350px;[span_14](end_span)
        }

        body {
            [span_15](start_span)margin: 0;[span_15](end_span)
            [span_16](start_span)font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;[span_16](end_span)
            [span_17](start_span)display: flex;[span_17](end_span)
            [span_18](start_span)height: 100vh;[span_18](end_span)
            [span_19](start_span)overflow: hidden;[span_19](end_span)
            [span_20](start_span)background-color: #f0f2f5;[span_20](end_span)
        }

        /* САЙДБАР */
        .sidebar {
            [span_21](start_span)width: var(--sidebar-width);[span_21](end_span)
            [span_22](start_span)border-right: 1px solid #dfe1e5;[span_22](end_span)
            [span_23](start_span)display: flex;[span_23](end_span)
            [span_24](start_span)flex-direction: column;[span_24](end_span)
            [span_25](start_span)background: #fff;[span_25](end_span)
            z-index: 10;
        }

        .sidebar-header {
            [span_26](start_span)padding: 15px;[span_26](end_span)
            [span_27](start_span)display: flex;[span_27](end_span)
            [span_28](start_span)align-items: center;[span_28](end_span)
            [span_29](start_span)gap: 15px;[span_29](end_span)
            [span_30](start_span)border-bottom: 1px solid #f4f4f5;[span_30](end_span)
        }

        .avatar-circle {
            [span_31](start_span)width: 42px;[span_31](end_span)
            [span_32](start_span)height: 42px;[span_32](end_span)
            [span_33](start_span)border-radius: 50%;[span_33](end_span)
            [span_34](start_span)background: var(--primary-gradient);[span_34](end_span)
            [span_35](start_span)color: white;[span_35](end_span)
            [span_36](start_span)display: flex;[span_36](end_span)
            [span_37](start_span)align-items: center;[span_37](end_span)
            [span_38](start_span)justify-content: center;[span_38](end_span)
            [span_39](start_span)font-weight: bold;[span_39](end_span)
            [span_40](start_span)cursor: pointer;[span_40](end_span)
            [span_41](start_span)box-shadow: 0 3px 6px rgba(79,172,254,0.2);[span_41](end_span)
            [span_42](start_span)flex-shrink: 0;[span_42](end_span)
        }

        .chats-list { flex: 1; overflow-y: auto; [span_43](start_span)}
        
        .chat-item {
            display: flex;[span_43](end_span)
            [span_44](start_span)padding: 15px;[span_44](end_span)
            [span_45](start_span)gap: 15px;[span_45](end_span)
            [span_46](start_span)cursor: pointer;[span_46](end_span)
            transition: background 0.2s;
        }
        .chat-item:hover { background: #f4f4f5; }
        .chat-item.active { background: #3390ec; color: white; [span_47](start_span)}

        .chat-info h4 { margin: 0; font-size: 16px; }[span_47](end_span)
        .chat-info p { margin: 4px 0 0; font-size: 13px; opacity: 0.8; [span_48](start_span)}

        /* ОКНО ЧАТА */
        .chat-window {
            flex: 1;[span_48](end_span)
            [span_49](start_span)display: flex;[span_49](end_span)
            [span_50](start_span)flex-direction: column;[span_50](end_span)
            [span_51](start_span)background-color: #f0f2f5;[span_51](end_span)
        }

        .chat-header {
            [span_52](start_span)background: #fff;[span_52](end_span)
            [span_53](start_span)padding: 12px 25px;[span_53](end_span)
            [span_54](start_span)display: flex;[span_54](end_span)
            [span_55](start_span)align-items: center;[span_55](end_span)
            [span_56](start_span)gap: 15px;[span_56](end_span)
            [span_57](start_span)border-bottom: 1px solid #dfe1e5;[span_57](end_span)
        }

        .messages-area {
            [span_58](start_span)flex: 1;[span_58](end_span)
            [span_59](start_span)padding: 20px 40px;[span_59](end_span)
            [span_60](start_span)overflow-y: auto;[span_60](end_span)
            [span_61](start_span)display: flex;[span_61](end_span)
            [span_62](start_span)flex-direction: column;[span_62](end_span)
            [span_63](start_span)gap: 8px;[span_63](end_span)
        }

        .msg-out {
            [span_64](start_span)align-self: flex-end;[span_64](end_span)
            [span_65](start_span)background: #eeffde;[span_65](end_span)
            [span_66](start_span)padding: 8px 15px;[span_66](end_span)
            [span_67](start_span)border-radius: 12px 12px 2px 12px;[span_67](end_span)
            [span_68](start_span)max-width: 70%;[span_68](end_span)
            [span_69](start_span)box-shadow: 0 1px 2px rgba(0,0,0,0.1);[span_69](end_span)
            [span_70](start_span)position: relative;[span_70](end_span)
        }

        .msg-time {
            [span_71](start_span)font-size: 10px;[span_71](end_span)
            [span_72](start_span)color: #65a15a;[span_72](end_span)
            [span_73](start_span)text-align: right;[span_73](end_span)
            [span_74](start_span)margin-top: 4px;[span_74](end_span)
        }

        /* ФОРМА ОТПРАВКИ */
        .input-area {
            [span_75](start_span)background: #fff;[span_75](end_span)
            [span_76](start_span)padding: 15px 30px;[span_76](end_span)
            [span_77](start_span)display: flex;[span_77](end_span)
            [span_78](start_span)gap: 15px;[span_78](end_span)
            [span_79](start_span)align-items: center;[span_79](end_span)
        }

        .input-area input {
            [span_80](start_span)flex: 1;[span_80](end_span)
            [span_81](start_span)padding: 12px 18px;[span_81](end_span)
            [span_82](start_span)border: 1px solid #dfe1e5;[span_82](end_span)
            [span_83](start_span)border-radius: 12px;[span_83](end_span)
            [span_84](start_span)outline: none;[span_84](end_span)
            [span_85](start_span)background: #f4f4f5;[span_85](end_span)
            [span_86](start_span)font-size: 15px;[span_86](end_span)
        }

        .btn-send {
            [span_87](start_span)background: var(--primary-gradient);[span_87](end_span)
            [span_88](start_span)color: white;[span_88](end_span)
            [span_89](start_span)border: none;[span_89](end_span)
            [span_90](start_span)padding: 12px 25px;[span_90](end_span)
            [span_91](start_span)border-radius: 12px;[span_91](end_span)
            [span_92](start_span)font-weight: 600;[span_92](end_span)
            [span_93](start_span)cursor: pointer;[span_93](end_span)
        }

        /* МОДАЛЬНЫЕ ОКНА */
        .modal-overlay {
            display: none;
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            [span_94](start_span)background: rgba(0,0,0,0.4); justify-content: center; align-items: center;[span_94](end_span)
            [span_95](start_span)z-index: 1000;[span_95](end_span)
        }

        .modal {
            [span_96](start_span)background: white;[span_96](end_span)
            [span_97](start_span)width: 400px; border-radius: 25px; padding: 30px; text-align: center; position: relative;[span_97](end_span)
        }

        .modal-admin { width: 800px; max-width: 90%; }

        .modal-input {
            [span_98](start_span)width: 100%;[span_98](end_span)
            [span_99](start_span)padding: 10px; margin: 8px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box;[span_99](end_span)
        }

        /* ТАБЛИЦА АДМИНА */
        .admin-table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
        .admin-table th { background: #f8f9fa; padding: 10px; border-bottom: 2px solid #dee2e6; text-align: left; }
        .admin-table td { padding: 10px; border-bottom: 1px solid #eee; text-align: left; }
        
        .btn-action { padding: 5px 10px; border-radius: 6px; border: none; cursor: pointer; font-size: 12px; }
        .btn-save { background: #4facfe; color: white; }
        .btn-del { background: #ff5e5e; color: white; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <div class="avatar-circle" onclick="toggleModal('profileModal')"><?php echo $firstLetter; [span_100](start_span)?></div>[span_100](end_span)
            <div style="font-weight: 600; font-size: 17px;"><?php echo htmlspecialchars($userData['name']); [span_101](start_span)?></div>[span_101](end_span)
        </div>
        
        <div class="chats-list">
            <div class="chat-item active">
                [span_102](start_span)<div class="avatar-circle" style="background: #fff; color: #3390ec;">★</div>[span_102](end_span)
                <div class="chat-info">
                    [span_103](start_span)<h4>Избранное</h4>[span_103](end_span)
                    [span_104](start_span)<p>Личные заметки</p>[span_104](end_span)
                </div>
            </div>

            <?php if ($isAdmin): ?>
            <div class="chat-item" onclick="toggleModal('adminModal')" style="border-top: 1px solid #eee; margin-top: 10px;">
                <div class="avatar-circle" style="background: var(--admin-gradient);">⚙️</div>
                <div class="chat-info">
                    <h4 style="color: #e73827;">Админ-панель</h4>
                    <p>Управление пользователями</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="chat-window">
        <div class="chat-header">
            [span_105](start_span)<div class="avatar-circle" style="width: 36px; height: 36px; background: #3390ec; color: #fff;">★</div>[span_105](end_span)
            [span_106](start_span)<div style="font-weight: 600;">Избранное</div>[span_106](end_span)
        </div>

        <div class="messages-area" id="msgBox">
            [span_107](start_span)<?php if (mysqli_num_rows($res_msg) == 0): ?>[span_107](end_span)
                [span_108](start_span)<div style="text-align: center; color: #707579; margin-top: 50px;">Здесь пока нет сообщений...</div>[span_108](end_span)
            <?php endif; [span_109](start_span)?>[span_109](end_span)

            [span_110](start_span)<?php while($msg = mysqli_fetch_assoc($res_msg)): ?>[span_110](end_span)
                <div class="msg-out">
                    <?php echo htmlspecialchars($msg['text']); [span_111](start_span)?>[span_111](end_span)
                    <div class="msg-time"><?php echo date('H:i', strtotime($msg['date_time'])); [span_112](start_span)?></div>[span_112](end_span)
                </div>
            <?php endwhile; [span_113](start_span)?>[span_113](end_span)
        </div>

        [span_114](start_span)<form action="send_msg.php" method="POST" class="input-area">[span_114](end_span)
            [span_115](start_span)<input type="text" name="message" placeholder="Написать себе..." required autocomplete="off">[span_115](end_span)
            [span_116](start_span)<button type="submit" class="btn-send">Отправить</button>[span_116](end_span)
        </form>
    </div>

    [span_117](start_span)<div class="modal-overlay" id="profileModal" style="<?php echo isset($_GET['success']) ? 'display:flex' : ''; ?>">[span_117](end_span)
        <div class="modal">
            [span_118](start_span)<span style="position:absolute; right:25px; top:20px; cursor:pointer; color: #ccc;" onclick="toggleModal('profileModal')">✕</span>[span_118](end_span)
            
            [span_119](start_span)<?php if(isset($_GET['success'])): ?>[span_119](end_span)
                [span_120](start_span)<div style="background: #e7f9ed; color: #1db954; padding: 10px; border-radius: 12px; margin-bottom: 20px; font-size: 14px;">✅ Изменения сохранены!</div>[span_120](end_span)
            <?php endif; [span_121](start_span)?>[span_121](end_span)

            [span_122](start_span)<div class="avatar-circle" style="width: 80px; height: 80px; margin: 0 auto 15px; font-size: 28px;">[span_122](end_span)
                <?php echo $firstLetter; [span_123](start_span)?>[span_123](end_span)
            </div>
            
            [span_124](start_span)<form action="update_profile.php" method="POST">[span_124](end_span)
                [span_125](start_span)<input type="text" name="name" class="modal-input" placeholder="Имя" value="<?php echo htmlspecialchars($userData['name']); ?>" required>[span_125](end_span)
                [span_126](start_span)<input type="text" name="surname" class="modal-input" placeholder="Фамилия" value="<?php echo htmlspecialchars($userData['surname']); ?>" required>[span_126](end_span)
                [span_127](start_span)<input type="date" name="birthday" class="modal-input" value="<?php echo $userData['birthday']; ?>" required>[span_127](end_span)
                [span_128](start_span)<button type="submit" class="btn-save-profile" style="width: 100%; padding: 12px; background: var(--primary-gradient); color: white; border: none; border-radius: 10px; margin-top: 10px; cursor: pointer;">Сохранить</button>[span_128](end_span)
            </form>

            [span_129](start_span)<a href="logout.php" style="color: #ff5e5e; text-decoration: none; display: block; margin-top: 20px;">Выйти из профиля</a>[span_129](end_span)
        </div>
    </div>

    <?php if ($isAdmin): ?>
    <div class="modal-overlay" id="adminModal">
        <div class="modal modal-admin">
            <span style="position:absolute; right:25px; top:20px; cursor:pointer; color: #ccc;" onclick="toggleModal('adminModal')">✕</span>
            <h3 style="margin-top: 0;">Управление пользователями</h3>
            
            <div style="max-height: 400px; overflow-y: auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Новый пароль</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($user = mysqli_fetch_assoc($allUsers)): ?>
                        <tr>
                            <form action="admin_control.php" method="POST">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <td><input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" class="modal-input" style="margin:0;"></td>
                                <td><input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" class="modal-input" style="margin:0;"></td>
                                <td><input type="password" name="password" placeholder="Изменить" class="modal-input" style="margin:0;"></td>
                                <td style="white-space: nowrap;">
                                    <button type="submit" name="action" value="update" class="btn-action btn-save">💾</button>
                                    <button type="submit" name="action" value="delete" class="btn-action btn-del" onclick="return confirm('Удалить пользователя навсегда?')">🗑️</button>
                                </td>
                            </form>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script>
        [span_130](start_span)// Прокрутка чата вниз[span_130](end_span)
        [span_131](start_span)const msgBox = document.getElementById('msgBox');[span_131](end_span)
        [span_132](start_span)msgBox.scrollTop = msgBox.scrollHeight;[span_132](end_span)

        [span_133](start_span)// Универсальная функция открытия модалок[span_133](end_span)
        [span_134](start_span)function toggleModal(id) {[span_134](end_span)
            [span_135](start_span)const modal = document.getElementById(id);[span_135](end_span)
            modal.style.display = (modal.style.display === 'flex') ? [span_136](start_span)'none' : 'flex';[span_136](end_span)
        }
    </script>
</body>
</html>
// Возвращаемся обратно в профиль
header("Location: profile.php?admin_success=1");
exit();
?>
