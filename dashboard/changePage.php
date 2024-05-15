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

$headings = R::findAll('heading', 'template_id = ?', [$page['template_id']]);

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
    
            header('Location: index.php');
        }
    } else {
        $id = $_GET['id'];
        foreach ($headings as $heading) {
            $heading_text_uz = $_POST[$heading['id'] . '_uz'];

            $headingText = R::findOne('text', 'heading_id = ? AND page_id = ?', [$heading['id'], $page['id']]);

            $headingText['text_uz'] = $heading_text_uz;
            $headingText['text_uz'] = $heading_text_ru;

            R::store($headingText);
        }

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
        <h1 class="heading">Редактировать страницу</h1>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        <?php if ($page['template_id'] == 0): ?>
        <h2 class="form-subheading">На узбекском</h2>
        <div class="input-container">
            <label class="input-label">Контент</label>
            <textarea name="content_uz" type="text" class="input"><?php echo $page['content_uz']; ?></textarea>
        </div>
        <hr>
        <h2 class="form-subheading">На русском</h2>
        <div class="input-container">
            <label class="input-label">Контент</label>
            <textarea name="content_ru" type="text" class="input"><?php echo $page['content_ru']; ?></textarea>
        </div>
        <?php else: ?>
            <div class="form-sections <?php if ($page['template_id'] == '9') { echo 'exchange-form'; } ?>">
                <?php foreach ($headings as $heading): ?>
                    <?php
                        $text = R::findOne('text', 'heading_id = ? AND page_id = ?', [$heading['id'], $page['id']]);
                    ?>
                    <div class="form-section">
                        <div class="input-container">
                            <label class="input-label"><?php echo $heading['name_uz']; ?></label>
                            <input name="<?php echo $heading['id']; ?>_uz" type="text" class="input" value="<?php echo $text['text_uz']; ?>">
                        </div>
                        <?php if ($page['template_id'] != '9'): ?>
                        <div class="input-container">
                            <label class="input-label"><?php echo $heading['name_ru']; ?></label>
                            <input name="<?php echo $heading['id']; ?>_ru" type="text" class="input" value="<?php echo $text['text_ru']; ?>">
                        </div>
                        <?php endif; ?>
                    </div>
                    <hr>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <button class="button">Готово</button>
    </form>
</body>
</html>