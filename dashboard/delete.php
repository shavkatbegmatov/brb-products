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
function deleteProduct($productId) {
    // Load the product
    $product = R::load('product', $productId);

    if ($product->id) {
        // If the product is of type 'page', delete associated page
        if ($product->type == 'page') {
            $page = R::findOne('page', 'parent_id = ?', [$product->id]);
            if ($page) {
                R::trash($page);
            }
        }

        // Find and delete all child products
        $child_products = R::find('product', 'parent_id = ?', [$productId]);
        foreach ($child_products as $child_product) {
            deleteProduct($child_product->id);
        }

        // Delete the product
        R::trash($product);
    }
}

// Get the parent_id before deleting the main product
$product = R::load('product', $id);
$parentProductId = $product->parent_id;

// Delete the main product
deleteProduct($id);

// Set the changedProductId in session
$_SESSION['changedProductId'] = $parentProductId;

// Redirect to index
header('Location: index.php');

?>
