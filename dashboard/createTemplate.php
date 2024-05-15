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

        $template = R::dispense('template');
        $template['name'] = $name;
        $id = R::store($template);

        $_SESSION['changedTemplateId'] = $id;

        header('Location: createTemplateHeadings.php?id=' . $id);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создать шаблон страницы</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-body">
    <a href="template.php" class="button small">Назад</a>
    <br>
    <br>
    <form class="form" method="post" action="createTemplate.php">
        <h1 class="heading">Создать шаблон страницы</h1>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        <div class="input-container">
            <label class="input-label">Названия</label>
            <input name="name" type="text" class="input">
        </div>
        <hr>
        <button class="button">Готово</button>
    </form>
</body>
</html>