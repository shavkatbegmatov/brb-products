<?php

session_start();

require '../connect/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: log.php');
}

if (!isset($_GET['id'])) {
    header('Location: heading.php');
}

$heading = R::findOne('heading', 'id = ?', [$_GET['id']]);

if (!empty($_POST)) {
    if (empty($_POST['name'])) {
        $_SESSION['message'] = 'Заполните все поля';
    } else {
        $name = $_POST['name'];

        $heading = R::load('heading', $_GET['id']);
        $heading['name_uz'] = $name_uz;
        $heading['name_ru'] = $name_ru;
        $id = R::store($heading);

        header('Location: heading.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать заголовок</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-body">
    <form class="form" method="post" action="changeHeading.php?id=<?php echo $_GET['id']; ?>">
        <h1 class="heading">Редактировать заголовок</h1>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        <div class="input-container">
            <label class="input-label">Название на узбекском <img src="/img/uz.png"></label>
            <input name="name" type="text" class="input" value="<?php echo $heading['name_uz']; ?>">
        </div>
        <div class="input-container">
            <label class="input-label">Название на русском <img src="/img/ru.png"></label>
            <input name="name" type="text" class="input" value="<?php echo $template['name_ru']; ?>">
        </div>
        <hr>
        <div class="buttons">
            <button class="button green">Сохранить</button>
            <a href="heading.php" class="button" onclick="return confirmCancel();">Отменить</a>
        </div>
    </form>

    <script>
        function confirmCancel() {
            return confirm('Вы уверены?');
        }
    </script>
</body>
</html>