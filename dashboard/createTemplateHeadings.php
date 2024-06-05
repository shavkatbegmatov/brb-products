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
    foreach ($_POST['heading'] as $key => $heading) {
        $templateHeading = R::dispense('templateheading');
        
        $templateHeading['template_id'] = $_GET['id'];
        $templateHeading['heading_id'] = $heading;
        
        R::store($templateHeading);

        header('Location: template.php');
    }
}

$headings = R::findAll('heading');

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
                    <label class="input-label">Выберите заголовок</label>
                    <select name="heading[]" class="input">
                        <?php foreach ($headings as $heading): ?>
                            <option value="<?php echo $heading['id']; ?>"><?php echo $heading['name_ru']; ?></option>
                        <?php endforeach; ?>
                    </select>
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