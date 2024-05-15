<?php

session_start();

require '../connect/db.php';

if (isset($_SESSION['user'])) {
    header('Location: index.php');
}

if (!empty($_POST)) {
    if (empty($_POST['login']) || empty($_POST['password'])) {
        $_SESSION['message'] = 'Заполните все поля';
    } else {
        $login = $_POST['login'];
        $password = md5($_POST['password']);
        $user = R::findOne('user', 'login = ? AND password = ?', [$login, $password]);
        if ($user) {
            $_SESSION['user'] = $user;
            header('Location: index.php');
        } else {
            $_SESSION['message'] = 'Неверное имя пользователя или пароль';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="log-body">
    <form class="form" method="post" action="log.php">
        <h1 class="heading">Авторизация</h1>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        <div class="input-container">
            <label class="input-label">Имя пользователя</label>
            <input name="login" type="text" class="input">
        </div>
        <div class="input-container">
            <label class="input-label">Пароль</label>
            <input name="password" type="password" class="input">
        </div>
        <button class="button">Авторизоваться</button>
    </form>
</body>
</html>