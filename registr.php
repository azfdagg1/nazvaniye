<?php
session_start(); // Включаем сессии
require_once 'db.php';

// Защита от SQL-инъекций
$login = mysqli_real_escape_string($connect, $_REQUEST['login']);
$password = $_REQUEST['password']; // В идеале использовать password_hash
$email = mysqli_real_escape_string($connect, $_REQUEST['email']);

$sql = "INSERT INTO `users_db` (login, password, email, IsAdmin) VALUES ('$login', '$password', '$email', 0)";

if (mysqli_query($connect, $sql)) {
    // Получаем ID, который только что создала БД (Auto Increment)
    $_SESSION['new_user_id'] = mysqli_insert_id($connect); 
    
    sleep(1); // 3 секунды — это слишком долго для пользователя
    header("Location: name.php");
    exit();
} else {
    echo "Ошибка при создании аккаунта: " . mysqli_error($connect);
}
