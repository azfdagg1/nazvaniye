<?php
// db.php
$connect = mysqli_connect('localhost', 'root', '', 'Messenger');
if (!$connect) {
    die("Ошибка подключения: " . mysqli_connect_error());
}
