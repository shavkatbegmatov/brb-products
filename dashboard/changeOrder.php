<?php

session_start();

require '../connect/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: log.php');
}

if (isset($_GET['id']) && isset($_GET['direction'])) {
    $id = $_GET['id'];
    $direction = $_GET['direction'];

    $product = R::load('product', $id);

    if ($direction == 'up') {
        $product['sort_order'] = $product['sort_order'] + 1;
    } else if ($direction == 'down') {
        if ($product['sort_order'] != 0) {
            $product['sort_order'] = $product['sort_order'] - 1;
        }
    }

    $id = R::store($product);

    $_SESSION['changedProductId'] = $id;
}

header('Location: index.php');