<?php

session_start();

require '../connect/db.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: log.php');
    exit();
}

// Check if id is set
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

// Sanitize inputs
$id = intval($_GET['id']);

// Recursive function to delete a product and its child products
function recoveryProduct($productId) {
    // Load the product
    $product = R::load('product', $productId);

    if ($product->id) {
        // Find and delete all child products

        // Delete the product
        $product['deleted'] = 0;
        
        R::store($product);
    }
}

// Delete the main product
recoveryProduct($id);

// Set the changedProductId in session
$_SESSION['changedProductId'] = $id;

// Redirect to index
header('Location: index.php');

?>
