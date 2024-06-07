<?php

session_start();

require '../connect/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: log.php');
}

$headings = R::findAll('heading');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заголовки</title>
    <link rel="stylesheet" href="style.css">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body class="dashboard-body">
    <div class="buttons">
        <a href="template.php" class="button">Назад</a>
        <a href="createHeading.php" class="button"><i class="bx bx-plus"></i> Добавить заголовок</a>
    </div>
    <br>
    <div class="products" id="products">
        <?php foreach ($headings as $heading): ?>
            <div class="product">
                <div class="product-box">
                    <div class="product-group">
                        <i class="bx bx-menu"></i>
    
                        <?php echo $heading['name_ru']; ?>
                    </div>
                    
                    <a class="button yellow small" href="changeHeading.php?id=<?php echo $heading['id']; ?>" title="Редактировать"><i class="bx bx-pencil"></i></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>