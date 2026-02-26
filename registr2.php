<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['new_user_id'])) {
    die("Ошибка: Сначала заполните данные регистрации.");
}

$user_id = $_SESSION['new_user_id'];
$name = mysqli_real_escape_string($connect, $_REQUEST['name']);
$surname = mysqli_real_escape_string($connect, $_REQUEST['surname']);
$birthday = $_REQUEST['birthday'];

// Проверка: если дата пустая, подставим NULL или текущую дату
if (empty($birthday)) {
    // Вариант А: записать текущую дату
    $birthday = date('Y-m-d'); 
    // Вариант Б: или выдать ошибку пользователю
    // die("Пожалуйста, укажите дату рождения.");
}

$sql = "INSERT INTO `names_db` (id, name, surname, birthday) VALUES ('$user_id', '$name', '$surname', '$birthday')";

if (mysqli_query($connect, $sql)) {
    unset($_SESSION['new_user_id']);
    echo "<h3>Вы зарегистрировались успешно!</h3>";
    sleep(1); // 3 секунды — это слишком долго для пользователя
    header("Location: profile.php");
    exit();
} else {
    echo "Ошибка: " . mysqli_error($connect);
}
