<?php

session_start();

require '../connect/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: log.php');
}

$templates = R::findAll('template');

if (isset($_SESSION['changedTemplateId'])) {
    $changedTemplateId = $_SESSION['changedTemplateId'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Шаблоны страниц</title>
    <link rel="stylesheet" href="style.css">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body class="dashboard-body">
    <div class="buttons">
        <a href="index.php" class="button">Назад</a>
        <a href="createTemplate.php" class="button"><i class="bx bx-plus"></i> Добавить шаблон</a>
        <a href="heading.php" class="button"><i class="bx bx-heading"></i> Список заголовков</a>
    </div>
    <br>
    <div class="products" id="products">
        <?php foreach ($templates as $template): ?>
            <?php
                $changedTemplate = false;
                if (isset($_SESSION['changedTemplateId'])) {
                    if ($template['id'] == $_SESSION['changedTemplateId']) {
                        $changedTemplate = true;
                    }
                    unset($_SESSION['changedTemplateId']);
                }
            ?>
            <div class="product">
                <div class="product-box" <?php if ($changedTemplate): ?> id="last-changed" <?php endif; ?>>
                    <div class="product-group">
                        <i class="bx bx-menu"></i>
    
                        <?php echo $template['name']; ?>
                    </div>

                    <div class="product-group">
                        <div class="buttons">
                            <a class="button yellow small" href="changeTemplateHeadings.php?id=<?php echo $template['id']; ?>" title="Редактировать заголовки"><i class="bx bx-edit"></i></a>
                            <a class="button yellow small" href="changeTemplate.php?id=<?php echo $template['id']; ?>" title="Редактировать"><i class="bx bx-pencil"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function confirmDelete() {
            return confirm('Вы уверены, что хотите удалить этот шаблон? Это действие нельзя будет отменить.');
        }

        function scrollToLastChanged() {
            var element = document.getElementById('last-changed');
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        scrollToLastChanged();
    </script>
</body>
</html>