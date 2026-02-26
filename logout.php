<?php
session_start(); // Запускаем сессию, чтобы иметь к ней доступ

// 1. Очищаем все переменные сессии (user_id, login и т.д.)
$_SESSION = array();

// 2. Если используются куки сессии, удаляем их
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Полностью уничтожаем сессию на сервере
session_destroy();

// 4. Перенаправляем пользователя на страницу входа или главную
header("Location: login.php");
exit();
?>
