<?php
session_start();

$product_id = $_POST['product_id'] ?? null;
$quantity = $_POST['quantity'] ?? 1;

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

echo json_encode(['status' => 'success', 'cart' => $_SESSION['cart']]);
?>
