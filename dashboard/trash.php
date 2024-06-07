<?php

session_start();

require '../connect/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: log.php');
}

$products = R::findAll('product', 'deleted = ? ORDER BY sort_order DESC, id ASC', ['1']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Корзина</title>
    <link rel="stylesheet" href="style.css">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body class="dashboard-body">
    <div class="buttons">
        <a href="index.php" class="button">Назад</a>
    </div>
    <br>
    <div class="products" id="products">
        <?php
        function productTree($childProducts) {
            foreach ($childProducts as $product): ?>
                <div class="product">
                    <div class="product-box">
                        <div class="product-group">
                            <?php if ($product['type'] == 'category'): ?>
                                <i class="bx bx-category-alt"></i>
                            <?php elseif ($product['type'] == 'page'): ?>
                                <i class="bx bx-news"></i>
                            <?php endif; ?>
    
                            <span class="product-sort-order">ID: <?php echo $product['id']; ?></span>
    
                            <?php echo $product['name_ru']; ?>
                        </div>
                        
                        <div class="product-group">
                            <a class="button small" href="recovery.php?id=<?php echo $product['id']; ?>">Восстановить</a>   
                        </div>
                    </div>
                </div>
            <?php endforeach; 
        } 
        productTree($products);
        ?>
    </div>
</body>
</html>