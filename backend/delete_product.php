<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

$productId = (int)($_GET['id'] ?? 0);
if ($productId <= 0) {
    redirect_with_message('../pages/products.php', 'error', 'Invalid product selected.');
}

$stmt = $conn->prepare('DELETE FROM products WHERE product_id = ?');
$stmt->bind_param('i', $productId);

if ($stmt->execute()) {
    $stmt->close();
    redirect_with_message('../pages/products.php', 'success', 'Product deleted successfully.');
}

$stmt->close();
redirect_with_message('../pages/products.php', 'error', 'Product cannot be deleted while linked to purchases or sales.');
