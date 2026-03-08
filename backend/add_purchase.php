<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

$supplierId = (int)($_POST['supplier_id'] ?? 0);
$productId = (int)($_POST['product_id'] ?? 0);
$quantity = (int)($_POST['quantity'] ?? 0);
$price = (float)($_POST['price'] ?? 0);
$purchaseDate = $_POST['purchase_date'] ?? date('Y-m-d');

if ($supplierId <= 0 || $productId <= 0 || $quantity <= 0 || $price <= 0) {
    redirect_with_message('../pages/purchases.php', 'error', 'Please enter valid purchase details.');
}

$totalAmount = $quantity * $price;

$conn->begin_transaction();

try {
    $purchaseStmt = $conn->prepare('INSERT INTO purchases (supplier_id, purchase_date, total_amount) VALUES (?, ?, ?)');
    $purchaseStmt->bind_param('isd', $supplierId, $purchaseDate, $totalAmount);
    $purchaseStmt->execute();
    $purchaseId = $purchaseStmt->insert_id;
    $purchaseStmt->close();

    $itemStmt = $conn->prepare('INSERT INTO purchase_items (purchase_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
    $itemStmt->bind_param('iiid', $purchaseId, $productId, $quantity, $price);
    $itemStmt->execute();
    $itemStmt->close();

    $conn->commit();
    redirect_with_message('../pages/purchases.php', 'success', 'Purchase recorded successfully.');
} catch (Throwable $e) {
    $conn->rollback();
    redirect_with_message('../pages/purchases.php', 'error', 'Failed to record purchase.');
}
