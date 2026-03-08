<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

$supplierId = (int)($_GET['id'] ?? 0);
if ($supplierId <= 0) {
    redirect_with_message('../pages/suppliers.php', 'error', 'Invalid supplier selected.');
}

$stmt = $conn->prepare('DELETE FROM suppliers WHERE supplier_id = ?');
$stmt->bind_param('i', $supplierId);

if ($stmt->execute()) {
    $stmt->close();
    redirect_with_message('../pages/suppliers.php', 'success', 'Supplier deleted successfully.');
}

$stmt->close();
redirect_with_message('../pages/suppliers.php', 'error', 'Supplier cannot be deleted while linked to products or purchases.');
