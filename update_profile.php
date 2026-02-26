<?php
session_start();
$connect = mysqli_connect('localhost', 'root', '', 'Messenger');

if (!isset($_SESSION['user_id'])) exit;

$id = $_SESSION['user_id'];
$name = mysqli_real_escape_string($connect, $_POST['name']);
$surname = mysqli_real_escape_string($connect, $_POST['surname']);
$birthday = mysqli_real_escape_string($connect, $_POST['birthday']);

$sql = "UPDATE `names_db` SET `name` = '$name', `surname` = '$surname', `birthday` = '$birthday' WHERE `id` = '$id'";

if (mysqli_query($connect, $sql)) {
    header("Location: profile.php?success=1");
} else {
    echo "Ошибка: " . mysqli_error($connect);
}
?>
