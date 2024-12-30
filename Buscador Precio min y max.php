<?php
include 'db_connection.php';

$query = $_GET['query'] ?? '';
$category = $_GET['categoria'] ?? '';
$min_price = $_GET['min_precio'] ?? 0;
$max_price = $_GET['max_precio'] ?? PHP_INT_MAX;

$sql = "SELECT * FROM pRODUCTOS  ?";
$params = ["%$query%", $min_price, $max_price];

if (!empty($categoria)) {
    $sql .= " AND categoria_id = ?";
    $params[] = $categoria;
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($products as $product) {
    echo "<div>";
    echo "<h2>{$product['nombre']}</h2>";
    echo "<p>Precio: {$product['price']}</p>";
    echo "<button data-id='{$product['id']}' class='add-to-cart'>Añadir al carrito</button>";
    echo "</div>";
}
?>