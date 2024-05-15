<?php

session_start();

require '../connect/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: log.php');
}

if (!isset($_GET['id'])) {
    header('Location: index.php');
}

$product = R::findOne('product', 'id = ?', [$_GET['id']]);

if ($product['visibility'] == 'true') {
    $product['visibility'] = 'false';
} else {
    $product['visibility'] = 'true';
}

$id = R::store($product);

$_SESSION['changedProductId'] = $id;

header('Location: index.php');