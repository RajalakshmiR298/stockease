<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

$productName = trim($_POST['product_name'] ?? '');
$categoryId = (int)($_POST['category_id'] ?? 0);
$supplierId = (int)($_POST['supplier_id'] ?? 0);
$price = (float)($_POST['price'] ?? 0);
$stockQuantity = (int)($_POST['stock_quantity'] ?? 0);
$lowStockLevel = (int)($_POST['low_stock_level'] ?? 10);

if ($productName === '' || $categoryId <= 0 || $supplierId <= 0 || $price <= 0 || $stockQuantity < 0 || $lowStockLevel < 0) {
    redirect_with_message('../pages/products.php', 'error', 'Please enter valid product details.');
}

$stmt = $conn->prepare('INSERT INTO products (product_name, category_id, supplier_id, price, stock_quantity, low_stock_level) VALUES (?, ?, ?, ?, ?, ?)');
$stmt->bind_param('siidii', $productName, $categoryId, $supplierId, $price, $stockQuantity, $lowStockLevel);

if ($stmt->execute()) {
    $stmt->close();
    redirect_with_message('../pages/products.php', 'success', 'Product added successfully.');
}

$stmt->close();
redirect_with_message('../pages/products.php', 'error', 'Unable to add product.');
