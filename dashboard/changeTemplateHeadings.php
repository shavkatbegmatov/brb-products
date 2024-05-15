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

// Fetch existing headings associated with the template
$templateHeadings = R::findAll('heading', 'template_id = ?', [$_GET['id']]);

if (!empty($_POST)) {
    // Update template headings
    foreach ($templateHeadings as $heading) {
        // Construct input names using heading_id
        $name_uz = $heading->id . '_uz';
        $name_ru = $heading->id . '_ru';
        
        // Ensure index exists in POST data
        if (isset($_POST[$name_ru]) && isset($_POST[$name_uz])) {
            $heading->name_ru = $_POST[$name_ru];
            $heading->name_uz = $_POST[$name_uz];
            R::store($heading);
        }
    }

    header('Location: template.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Изменить шаблон страницы</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-body">
    <a href="template.php" class="button small">Назад</a>
    <br>
    <br>
    <form class="form" method="post" action="changeTemplateHeadings.php?id=<?php echo $_GET['id']; ?>">
        <h1 class="heading">Изменить заголовки для шаблона "<?php echo $template['name']; ?>"</h1>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>
        <div class="form-sections">
            <?php foreach ($templateHeadings as $heading): ?>
                <div class="form-section">
                    <h2 class="form-subheading">Заголовок</h2>
                    <div class="input-container">
                        <label class="input-label">Названия на узбекском</label>
                        <input name="<?php echo $heading->id; ?>_uz" type="text" class="input" value="<?php echo $heading->name_uz; ?>">
                    </div>
                    <div class="input-container">
                        <label class="input-label">Названия на русском</label>
                        <input name="<?php echo $heading->id; ?>_ru" type="text" class="input" value="<?php echo $heading->name_ru; ?>">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <hr>
        <button class="button">Готово</button>
    </form>
</body>
</html>
