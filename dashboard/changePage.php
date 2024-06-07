<?php

session_start();

require '../connect/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: log.php');
}

if (!isset($_GET['id'])) {
    header('Location: index.php');
}

$page = R::findOne('page', 'id = ?', [$_GET['id']]);

$page_parent_product = R::findOne('product', 'id = ?', [$page['parent_id']]);

$headings = R::findAll('templateheading', 'template_id = ?', [$page['template_id']]);

if (!empty($_POST)) {
    if ($page['template_id'] == 0) {
        if (empty($_POST['content_uz']) || empty($_POST['content_ru'])) {
            $_SESSION['message'] = 'Заполните все поля';
        } else {
            $content_uz = $_POST['content_uz'];
            $content_ru = $_POST['content_ru'];
    
            $changePage = R::findOne('page', 'id = ?', [$_GET['id']]);
            $changePage['content_uz'] = $content_uz;
            $changePage['content_ru'] = $content_ru;
            R::store($changePage);
    
            $_SESSION['changedProductId'] = $page['parent_id'];
            header('Location: index.php');
        }
    } else {
        $id = $_GET['id'];
        foreach ($headings as $heading) {
            $headingobj = R::findOne('heading', 'id = ?', [$heading['heading_id']]);
            $heading_text_uz = $_POST[$headingobj['id'] . '_uz'];
            $heading_text_ru = $_POST[$headingobj['id'] . '_ru'];

            $headingText = R::findOne('text', 'heading_id = ? AND page_id = ?', [$headingobj['id'], $page['id']]);

            $headingText['text_uz'] = $heading_text_uz;
            $headingText['text_ru'] = $heading_text_ru;

            R::store($headingText);
        }

        $_SESSION['changedProductId'] = $page['parent_id'];
        header('Location: index.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать страницу</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-body">
    <form class="form" method="post" action="changePage.php?id=<?php echo $_GET['id']; ?>">
        <h1 class="heading">
            Редактировать страницу
            <br>
            "<?php echo $page_parent_product['name_ru']; ?>"
        </h1>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        <?php if ($page['template_id'] == 0): ?>
        <h2 class="form-subheading">На узбекском <img src="/img/uz.png"></h2>
        <div class="input-container">
            <label class="input-label">Контент</label>
            <textarea name="content_uz" type="text" class="input" rows="10"><?php echo $page['content_uz']; ?></textarea>
        </div>
        <hr>
        <h2 class="form-subheading">На русском <img src="/img/ru.png"></h2>
        <div class="input-container">
            <label class="input-label">Контент</label>
            <textarea name="content_ru" type="text" class="input" rows="10"><?php echo $page['content_ru']; ?></textarea>
        </div>
        <?php else: ?>
            <div class="form-sections <?php if ($page['template_id'] == '9') { echo 'exchange-form'; } ?>">
                <?php foreach ($headings as $heading): ?>
                    <?php
                        $headingobj = R::findOne('heading', 'id = ?', [$heading['heading_id']]);
                        $text = R::findOne('text', 'heading_id = ? AND page_id = ?', [$heading['id'], $page['id']]);
                    ?>
                    <div class="form-section">
                        <div class="input-container">
                            <label class="input-label"><?php echo $headingobj['name_uz']; ?> <img src="/img/uz.png"></label>
                            <input name="<?php echo $headingobj['id']; ?>_uz" type="text" class="input" value="<?php echo $text['text_uz']; ?>">
                        </div>
                        <?php if ($page['template_id'] != '9'): ?>
                        <div class="input-container">
                            <label class="input-label"><?php echo $headingobj['name_ru']; ?> <img src="/img/ru.png"></label>
                            <input name="<?php echo $headingobj['id']; ?>_ru" type="text" class="input" value="<?php echo $text['text_ru']; ?>">
                        </div>
                        <?php endif; ?>
                    </div>
                    <hr>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
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