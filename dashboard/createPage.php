<?php

session_start();

require '../connect/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: log.php');
}

if (!isset($_GET['id'])) {
    header('Location: index.php');
}

if (!isset($_GET['template_id'])) {
    header('Location: index.php');
}

$headings = R::findAll('templateheading', 'template_id = ?', [$_GET['template_id']]);

if (!empty($_POST)) {
    if ($_GET['template_id'] == 0) {
        if (empty($_POST['content_uz']) || empty($_POST['content_ru'])) {
            $_SESSION['message'] = 'Заполните все поля';
        } else {
            $content_uz = $_POST['content_uz'];
            $content_ru = $_POST['content_ru'];
    
            $page = R::dispense('page');
            $page['content_uz'] = $content_uz;
            $page['content_ru'] = $content_ru;
            $page['parent_id'] = $_GET['id'];
            $page['template_id'] = $_GET['template_id'];
            R::store($page);
    
            header('Location: index.php');
        }
    } else {
        $page = R::dispense('page');
        $page['content_uz'] = '';
        $page['content_ru'] = '';
        $page['parent_id'] = $_GET['id'];
        $page['template_id'] = $_GET['template_id'];
        $id = R::store($page);
        foreach ($headings as $heading) {
            $heading_text_uz = $_POST[$heading['id'] . '_uz'];

            $headingText = R::dispense('text');
            $headingText['text_uz'] = $heading_text_uz;
            $headingText['text_ru'] = $heading_text_uz;
            $headingText['heading_id'] = $heading['id'];
            $headingText['page_id'] = $id;
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
    <title>Создать страницу</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-body">
    <form class="form" method="post" action="createPage.php?id=<?php echo $_GET['id']; ?>&template_id=<?php echo $_GET['template_id']; ?>">
        <h1 class="heading">Создать страницу</h1>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        <?php if ($_GET['template_id'] == 0): ?>
        <h2 class="form-subheading">На узбекском</h2>
        <div class="input-container">
            <label class="input-label">Контент</label>
            <textarea name="content_uz" type="text" class="input"></textarea>
        </div>
        <hr>
        <h2 class="form-subheading">На русском</h2>
        <div class="input-container">
            <label class="input-label">Контент</label>
            <textarea name="content_ru" type="text" class="input"></textarea>
        </div>
        <?php else: ?>
            <div class="form-sections <?php if ($_GET['template_id'] == '9') { echo 'exchange-form'; } ?>">
                <?php foreach ($headings as $heading): ?>
                    <?php $headingobj = R::findOne('heading', 'id = ?', [$heading['heading_id']]); ?>
                    <div class="form-section">
                        <div class="input-container">
                            <label class="input-label"><?php echo $headingobj['name_uz']; ?></label>
                            <input name="<?php echo $headingobj['id']; ?>_uz" type="text" class="input">
                        </div>
                        <?php if ($_GET['template_id'] != '9'): ?>
                        <div class="input-container">
                            <label class="input-label"><?php echo $headingobj['name_ru']; ?></label>
                            <input name="<?php echo $headingobj['id']; ?>_ru" type="text" class="input">
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