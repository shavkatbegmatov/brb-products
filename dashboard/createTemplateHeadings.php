<?php

session_start();

require '../connect/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: log.php');
}

if (!isset($_GET['id'])) {
    header('Location: createTemplate.php');
}

$template = R::findOne('template', 'id = ?', [$_GET['id']]);

if (!empty($_POST)) {
    foreach ($_POST['name_ru'] as $key => $name_ru) {
        $templateHeading = R::dispense('heading');
        
        $templateHeading['name_ru'] = $name_ru;
        $templateHeading['name_uz'] = $_POST['name_uz'][$key];
        $templateHeading['template_id'] = $_GET['id'];
        
        R::store($templateHeading);

        header('Location: template.php');
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body class="dashboard-body">
    <a href="template.php" class="button small">Назад</a>
    <br>
    <br>
    <form class="form" method="post" action="createTemplateHeadings.php?id=<?php echo $_GET['id']; ?>">
        <h1 class="heading">Добавить заголовки для шаблона "<?php echo $template['name']; ?>"</h1>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        <div class="form-sections">
            <div class="form-section">
                <h2 class="form-subheading">Заголовок</h2>
                <div class="input-container">
                    <label class="input-label">Названия на узбекском</label>
                    <input name="name_uz[]" type="text" class="input">
                </div>
                <div class="input-container">
                    <label class="input-label">Названия на русском</label>
                    <input name="name_ru[]" type="text" class="input">
                </div>
            </div>
        </div>
        <hr>
        <button type="button" id="add-section" class="button small">Добавить заголовок</button>
        <button class="button">Готово</button>
    </form>

    <script>
        $(document).ready(function() {
            $('#add-section').click(function() {
                let formSection = $('.form-section').first().clone();

                formSection.find('input').val('');

                $('.form-sections').append(formSection);
            });
        });
    </script>
</body>
</html>