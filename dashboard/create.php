<?php

session_start();

require '../connect/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: log.php');
}

if (!isset($_GET['id'])) {
    header('Location: index.php');
}

$templates = R::findAll('template');

if (!empty($_POST)) {
    if (empty($_POST['name_uz']) || empty($_POST['name_ru'])) {
        $_SESSION['message'] = 'Заполните все поля';
    } else {
        $name_uz = $_POST['name_uz'];
        $name_ru = $_POST['name_ru'];
        $description_uz = $_POST['description_uz'];
        $description_ru = $_POST['description_ru'];
        $icon = $_POST['icon'];
        $type = $_POST['type'];
        $template_id = $_POST['template_id'];

        if ($type == '0') {
            $type = 'category';
        } else if ($type == '1') {
            $type = 'page';
        }

        $product = R::dispense('product');
        $product['name_uz'] = $name_uz;
        $product['name_ru'] = $name_ru;
        $product['description_uz'] = $description_uz;
        $product['description_ru'] = $description_ru;
        $product['icon'] = $icon;
        $product['type'] = $type;
        $product['parent_id'] = $_GET['id'];

        $id = R::store($product);

        $_SESSION['changedProductId'] = $id;

        if ($type == 'category') {
            header('Location: index.php');
        } else if ($type == 'page') {
            header('Location: createPage.php?id=' . $id . '&template_id=' . $template_id);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создать</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-body">
    <form class="form" method="post" action="create.php?id=<?php echo $_GET['id']; ?>">
        <h1 class="heading">Создать</h1>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        <h2 class="form-subheading">На узбекском <img src="/img/uz.png"></h2>
        <div class="input-container">
            <label class="input-label">Названия</label>
            <input name="name_uz" type="text" class="input">
        </div>
        <div class="input-container">
            <label class="input-label">Подробности</label>
            <input name="description_uz" type="text" class="input">
        </div>
        <hr>
        <h2 class="form-subheading">На русском <img src="/img/ru.png"></h2>
        <div class="input-container">
            <label class="input-label">Названия</label>
            <input name="name_ru" type="text" class="input">
        </div>
        <div class="input-container">
            <label class="input-label">Подробности</label>
            <input name="description_ru" type="text" class="input">
        </div>
        <hr>
        <div class="input-container">
            <label class="input-label">Иконка</label>
            <input placeholder='icon.png' name="icon" type="text" class="input" style="font-family: monospace;">
        </div>
        <div class="radio-container">
            <label class="input-label">Тип</label>
            <label class="radio-label"><input type="radio" name="type" value="0" id="hide" checked> Категория</label>
            <label class="radio-label"><input type="radio" name="type" value="1" id="show"> Страница</label>
        </div>
        <div class="radio-container" id="box" style="display: none;">
            <label class="input-label">Вид страницы</label>
            <select class="input" name="template_id">
                <option value="0">Без шаблона</option>
                <?php foreach ($templates as $template): ?>
                    <option value="<?php echo $template['id']; ?>"><?php echo $template['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <hr>
        <div class="buttons">
            <button class="button green">Сохранить</button>
            <a href="index.php" class="button" onclick="return confirmCancel();">Отменить</a>
        </div>
    </form>

    <script>
        let show = document.getElementById("show");
        let hide = document.getElementById("hide");
        let box = document.getElementById("box");

        show.addEventListener("change", function() {
            if (this.checked) {
                box.style.display = "flex";
            }
        });

        hide.addEventListener("change", function() {
            if (this.checked) {
                box.style.display = "none";
            }
        });

        function confirmCancel() {
            return confirm('Вы уверены?');
        }
    </script>
</body>
</html>