<?php

session_start();

require '../connect/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: log.php');
}

if (!isset($_GET['id'])) {
    header('Location: index.php');
}

$product = R::load('product', $_GET['id']);

if ($product['type'] == 'page') {
    $page = R::findOne('page', 'parent_id = ?', [$product['id']]);
    $page = R::load('page', $page['id']);
    R::trash($page);
}

R::trash($product);

$child_products = R::find('product', 'parent_id = ?', [$_GET['id']]);

if ($child_products) {
    foreach ($child_products as $child_product) {
        header('Location: delete.php?id=' . $child_product['id']);
    }
} else {
    header('Location: index.php');
}

?>