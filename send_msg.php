<?php
session_start();
$connect = mysqli_connect('localhost', 'root', '', 'Messenger');

if (!isset($_SESSION['user_id'])) exit;

if (!empty($_POST['message'])) {
    $user_id = $_SESSION['user_id'];
    $text = mysqli_real_escape_string($connect, $_POST['message']);

    $sql = "INSERT INTO `messages` (`user_id`, `text`) VALUES ('$user_id', '$text')";
    
    if (mysqli_query($connect, $sql)) {
        header("Location: profile.php");
        exit();
    } else {
        echo "Ошибка: " . mysqli_error($connect);
    }
} else {
    header("Location: profile.php");
}
?>
