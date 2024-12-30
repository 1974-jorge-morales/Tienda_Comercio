<?php
include 'db_connection.php';
session_start();

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    die("El carrito está vacío.");
}

// Crear pedido
$conn->beginTransaction();

$sql = "INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'Pendiente')";
$stmt = $conn->prepare($sql);
$total_amount = calculateTotal($cart, $conn);
$stmt->execute([$user_id, $total_amount]);
$order_id = $conn->lastInsertId();

// Agregar productos al pedido
foreach ($cart as $product_id => $quantity) {
    $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $price = getProductPrice($product_id, $conn);
    $stmt->execute([$order_id, $product_id, $quantity, $price]);
}

// Confirmar pago (simulación)
if (processPayment($total_amount)) {
    $conn->commit();
    echo "Pago realizado con éxito.";
} else {
    $conn->rollBack();
    echo "Error en el pago.";
}

function calculateTotal($cart, $conn) {
    $total = 0;
    foreach ($cart as $product_id => $quantity) {
        $total += getProductPrice($product_id, $conn) * $quantity;
    }
    return $total;
}

function getProductPrice($product_id, $conn) {
    $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    return $stmt->fetchColumn();
}

function processPayment($amount) {
    // Aquí integrarías la pasarela de pago (Stripe, PayPal, etc.)
    return true;
}
?>