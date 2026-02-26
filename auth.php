<?php
session_start();
$connect = mysqli_connect('localhost', 'root', '', 'Messenger');

$login = mysqli_real_escape_string($connect, $_POST['login']);
$password = mysqli_real_escape_string($connect, $_POST['password']);

$sql = "SELECT * FROM `users_db` WHERE `login` = '$login' AND `password` = '$password'";
$query = mysqli_query($connect, $sql);

if (mysqli_num_rows($query) == 1) {
    $user = mysqli_fetch_assoc($query);
    $_SESSION['user_id'] = $user['id']; // СОХРАНЯЕМ ID
    $_SESSION['login'] = $user['login'];
    
    header("Location: profile.php");
    exit();
} else {
    echo "<a href='login.php'>Неверный логин и/или пароль, зайти по новой</a>";
}
?>
