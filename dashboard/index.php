<?php

session_start();

require '../connect/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: log.php');
}

$products = R::findAll('product', 'parent_id = ? ORDER BY sort_order DESC, id ASC', ['0']);

if (isset($_SESSION['changedProductId'])) {
    $changedProductId = $_SESSION['changedProductId'];
    $changedProductParents = [$changedProductId];

    while (end($changedProductParents) != '0') {
        $parent_product = R::findOne('product', 'id = ?', [end($changedProductParents)]);
        array_push($changedProductParents, $parent_product['parent_id']);
    }

    array_shift($changedProductParents);
    $changedProductParents = json_encode($changedProductParents);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель управления</title>
    <link rel="stylesheet" href="style.css">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body class="dashboard-body">
    <a href="logout.php" class="button small"><i class='bx bx-exit'></i> Покинуть панель управления</a>
    <a href="template.php" class="button small"><i class='bx bx-book-content'></i> Шаблоны страниц</a>
    <a href="currency-exchange.php" class="button small"><i class='bx bx-dollar'></i> Курс валют</a>
    <br>
    <br>
    <div class="products" id="products">
        <?php
        function productTree($childProducts) {
            foreach ($childProducts as $product): ?>
                <?php
                    $changedProduct = false;
                    if (isset($_SESSION['changedProductId'])) {
                        if ($product['id'] == $_SESSION['changedProductId']) {
                            $changedProduct = true;
                        }
                    }
                ?>
                <div class="product">
                    <div class="product-box" draggable="true" <?php if ($changedProduct): ?> id="last-changed" <?php endif; ?> data-product-id="<?php echo $product['id']; ?>">
                        <?php if ($product['type'] == 'category'): ?>
                            <i class="bx bx-category-alt"></i>
                        <?php elseif ($product['type'] == 'page'): ?>
                            <i class="bx bx-news"></i>
                        <?php endif; ?>
                        <?php echo $product['name_ru']; ?>

                        <hr class="product-divider">

                        <?php if ($product['type'] == 'page'): ?>
                            <?php $page = R::findOne('page', 'parent_id = ?', [$product['id']]);?>
                            <a class="button small" href="changePage.php?id=<?php echo $page['id']; ?>" title="Редактировать контент"><i class="bx bx-edit"></i></a>
                        <?php endif; ?>
                        <a class="button small" href="change.php?id=<?php echo $product['id']; ?>" title="Редактировать"><i class="bx bx-pencil"></i></a>
                        <?php if ($product['type'] == 'category'): ?>
                            <a class="button small" href="create.php?id=<?php echo $product['id']; ?>" title="Добавить"><i class="bx bx-plus"></i></a>
                        <?php endif; ?>
                        <a class="button small" href="delete.php?id=<?php echo $product['id']; ?>" title="Удалить" onclick="return confirmDelete();"><i class="bx bx-trash"></i></a>
                        <?php if ($product['parent_id'] != '0'): ?>
                            <a class="button small" href="parentUp.php?id=<?php echo $product['id']; ?>" title="Поднять на один уровень"><i class="bx bx-chevron-up"></i> Уровень вверх</a>
                        <?php endif; ?>

                        <?php if ($product['visibility'] == 'true'): ?>
                            <a class="button small" href="toggleVisibility.php?id=<?php echo $product['id']; ?>"><i class="bx bx-show"></i></a>
                        <?php else: ?>
                            <a class="button small" href="toggleVisibility.php?id=<?php echo $product['id']; ?>"><i class="bx bx-hide"></i></a>
                        <?php endif; ?>

                        <hr class="product-divider">

                        <span class="product-sort-order">Уровень приоритета: <?php echo $product['sort_order']; ?></span>
                        <a class="button small" href="changeOrder.php?id=<?php echo $product['id']; ?>&direction=up"><i class="bx bx-up-arrow-alt"></i></a>
                        <?php if ($product['sort_order'] != '0'): ?>
                            <a class="button small" href="changeOrder.php?id=<?php echo $product['id']; ?>&direction=down"><i class="bx bx-down-arrow-alt"></i></a>
                        <?php endif; ?>
                    </div>
                    <?php if ($product['type'] == 'category'): ?>
                        <?php $childProducts = R::findAll('product', 'parent_id = ? ORDER BY sort_order DESC, id ASC', [$product['id']]); ?>
                        <?php if ($childProducts): ?> 
                            <div class="products" id="<?php echo $product['id']; ?>">
                                <?php productTree($childProducts); ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; 
        } 
        productTree($products);
        ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
        const productList = document.getElementById('products');

        productList.addEventListener('dragstart', function (event) {
            event.dataTransfer.setData('text/plain', event.target.getAttribute('data-product-id'));
        });

        productList.addEventListener('dragover', function (event) {
            event.preventDefault(); // Allow drop
        });

        productList.addEventListener('dragenter', function (event) {
            if (event.target && event.target.hasAttribute('data-product-id')) {
                event.target.classList.add('drag-over-target'); // Добавить класс
            }
        });

        productList.addEventListener('dragleave', function (event) {
            if (event.target && event.target.hasAttribute('data-product-id')) {
                event.target.classList.remove('drag-over-target'); // Удалить класс
            }
        });

        productList.addEventListener('drop', function (event) {
            event.preventDefault();
            const draggedProductId = event.dataTransfer.getData('text/plain');
            const targetProductId = event.target.getAttribute('data-product-id');

            if (draggedProductId && targetProductId && draggedProductId !== targetProductId) {
                const url = `changeParent.php?item_id=${draggedProductId}&target_id=${targetProductId}`;
                window.location.href = url;
            }

            if (event.target && event.target.hasAttribute('data-product-id')) {
                event.target.classList.remove('drag-over-target'); // Удалить класс после drop
            }
        });
    });

    </script>
    <script>
        const a_speed = 300;

        function confirmDelete() {
            return confirm('Вы уверены, что хотите удалить этот продукт? Это действие нельзя будет отменить. Все дочерние продукты тоже будут удалены.');
        }

        <?php if (isset($_SESSION['changedProductId'])): ?>
            const changedProductParents = <?php echo $changedProductParents; ?>;
        <?php endif; ?>

        $('.products').each(function() {
            $(this).hide();
        });

        $('.products').each(function() {
            if ($(this).attr('id') == 'products') {
                $(this).show();
            }
            <?php if (isset($_SESSION['changedProductId'])): ?>
                if (changedProductParents.includes($(this).attr('id'))) {
                    $(this).show();
                }
            <?php endif; unset($_SESSION['changedProductId']); ?>
        });

        $(".product-box").click(function(event) {
            if ($(event.target).closest("a").length) {
                return;
            } else {
                let productId = $(this).data("product-id");
                $('#' + productId).slideToggle(a_speed);
            }
        });

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