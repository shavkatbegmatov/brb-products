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

$parent = R::findOne('product', 'id = ?', [$product['parent_id']]);

$product['parent_id'] = $parent['parent_id'];

$id = R::store($product);
$_SESSION['changedProductId'] = $id;
header('Location: index.php');