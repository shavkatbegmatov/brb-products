<?php

session_start();

require '../connect/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: log.php');
}

if (!empty($_POST)) {
    if (empty($_POST['name'])) {
        $_SESSION['message'] = 'Заполните все поля';
    } else {
        $name = $_POST['name'];

        $heading = R::dispense('heading');
        $heading['name_uz'] = $name_uz;
        $heading['name_ru'] = $name_ru;
        $id = R::store($heading);

        header('Location: template.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создать заголовок</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-body">
    <a href="template.php" class="button small">Назад</a>
    <br>
    <br>
    <form class="form" method="post" action="createTemplate.php">
        <h1 class="heading">Создать заголовок</h1>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        <div class="input-container">
            <label class="input-label">Название на узбекском</label>
            <input name="name_uz" type="text" class="input">
        </div>
        <div class="input-container">
            <label class="input-label">Название на русском</label>
            <input name="name_ru" type="text" class="input">
        </div>
        <hr>
        <button class="button">Готово</button>
    </form>
</body>
</html>