<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

$productId = (int)($_POST['product_id'] ?? 0);
$quantity = (int)($_POST['quantity'] ?? 0);
$price = (float)($_POST['price'] ?? 0);
$saleDate = $_POST['sale_date'] ?? date('Y-m-d');

if ($productId <= 0 || $quantity <= 0 || $price <= 0) {
    redirect_with_message('../pages/sales.php', 'error', 'Please enter valid sales details.');
}

$stockStmt = $conn->prepare('SELECT stock_quantity FROM products WHERE product_id = ? LIMIT 1');
$stockStmt->bind_param('i', $productId);
$stockStmt->execute();
$stockResult = $stockStmt->get_result();
$product = $stockResult->fetch_assoc();
$stockStmt->close();

if (!$product) {
    redirect_with_message('../pages/sales.php', 'error', 'Selected product does not exist.');
}

if ((int)$product['stock_quantity'] < $quantity) {
    redirect_with_message('../pages/sales.php', 'error', 'Not enough stock for this sale.');
}

$totalAmount = $quantity * $price;

$conn->begin_transaction();

try {
    $saleStmt = $conn->prepare('INSERT INTO sales (sale_date, total_amount) VALUES (?, ?)');
    $saleStmt->bind_param('sd', $saleDate, $totalAmount);
    $saleStmt->execute();
    $saleId = $saleStmt->insert_id;
    $saleStmt->close();

    $itemStmt = $conn->prepare('INSERT INTO sales_items (sale_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
    $itemStmt->bind_param('iiid', $saleId, $productId, $quantity, $price);
    $itemStmt->execute();
    $itemStmt->close();

    $conn->commit();
    redirect_with_message('../pages/sales.php', 'success', 'Sale recorded successfully.');
} catch (Throwable $e) {
    $conn->rollback();
    redirect_with_message('../pages/sales.php', 'error', 'Failed to record sale.');
}
