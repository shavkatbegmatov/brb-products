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

$product['parent_id'] = $_GET['target_id'];

$id = R::store($product);
$_SESSION['changedProductId'] = $id;
header('Location: index.php');