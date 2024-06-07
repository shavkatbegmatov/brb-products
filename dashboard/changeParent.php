<?php

session_start();

require '../connect/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: log.php');
}

if (!isset($_GET['item_id'])) {
    header('Location: index.php');
}

if (!isset($_GET['target_id'])) {
    header('Location: index.php');
}

$product = R::findOne('product', 'id = ?', [$_GET['item_id']]);
$target_product = R::findOne('product', 'id = ?', [$_GET['target_id']]);

$target_product_parents = [$target_product['id']];

while (end($target_product_parents) != '0') {
    $parent_product = R::findOne('product', 'id = ?', [end($target_product_parents)]);
    array_push($target_product_parents, $parent_product['parent_id']);
}

if (!in_array($_GET['item_id'], $target_product_parents)) {
    if ($target_product['type'] != 'page') {
        $product['parent_id'] = $_GET['target_id'];
    
        $id = R::store($product);
        $_SESSION['changedProductId'] = $id;
    }
}

header('Location: index.php');