<?php

session_start();

require '../connect/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: log.php');
}

if (!isset($_GET['id'])) {
    header('Location: index.php');
}

$product = R::findOne('product', 'id = ?', [$_GET['id']]);

if (!empty($_POST)) {
    if (empty($_POST['name_uz']) || empty($_POST['name_ru'])) {
        $_SESSION['message'] = 'Заполните все поля';
    } else {
        $name_uz = $_POST['name_uz'];
        $name_ru = $_POST['name_ru'];
        $description_uz = $_POST['description_uz'];
        $description_ru = $_POST['description_ru'];
        $icon = $_POST['icon'];

        $product = R::load('product', $_GET['id']);
        $product['name_uz'] = $name_uz;
        $product['name_ru'] = $name_ru;
        $product['description_uz'] = $description_uz;
        $product['description_ru'] = $description_ru;
        $product['icon'] = $icon;
        $id = R::store($product);

        $_SESSION['changedProductId'] = $id;

        header('Location: index.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-body">
    <form class="form" method="post" action="change.php?id=<?php echo $product['id']; ?>">
        <h1 class="heading">Редактировать</h1>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        <h2 class="form-subheading">На узбекском <img src="/img/uz.png"></h2>
        <div class="input-container">
            <label class="input-label">Названия</label>
            <input name="name_uz" type="text" class="input" value="<?php echo $product['name_uz']; ?>">
        </div>
        <div class="input-container">
            <label class="input-label">Подробности</label>
            <input name="description_uz" type="text" class="input" value="<?php echo $product['description_uz']; ?>">
        </div>
        <hr>
        <h2 class="form-subheading">На русском <img src="/img/ru.png"></h2>
        <div class="input-container">
            <label class="input-label">Названия</label>
            <input name="name_ru" type="text" class="input" value="<?php echo $product['name_ru']; ?>">
        </div>
        <div class="input-container">
            <label class="input-label">Подробности</label>
            <input name="description_ru" type="text" class="input" value="<?php echo $product['description_ru']; ?>">
        </div>
        <hr>
        <div class="input-container">
            <label class="input-label">Иконка</label>
            <input placeholder='icon.png' name="icon" type="text" class="input" value="<?php echo $product['icon']; ?>" style="font-family: monospace;">
        </div>
        <hr>
        <div class="buttons">
            <button class="button green">Сохранить</button>
            <a href="index.php" class="button" onclick="return confirmCancel();">Отменить</a>
        </div>
    </form>

    <script>
        function confirmCancel() {
            return confirm('Вы уверены?');
        }
    </script>
</body>
</html>