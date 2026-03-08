<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

$supplierId = (int)($_POST['supplier_id'] ?? 0);
$supplierName = trim($_POST['supplier_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$address = trim($_POST['address'] ?? '');

if ($supplierId <= 0 || $supplierName === '') {
    redirect_with_message('../pages/suppliers.php', 'error', 'Invalid supplier data.');
}

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect_with_message('../pages/suppliers.php', 'error', 'Please enter a valid supplier email.');
}

$stmt = $conn->prepare('UPDATE suppliers SET supplier_name = ?, phone = ?, email = ?, address = ? WHERE supplier_id = ?');
$stmt->bind_param('ssssi', $supplierName, $phone, $email, $address, $supplierId);

if ($stmt->execute()) {
    $stmt->close();
    redirect_with_message('../pages/suppliers.php', 'success', 'Supplier updated successfully.');
}

$stmt->close();
redirect_with_message('../pages/suppliers.php', 'error', 'Unable to update supplier.');
