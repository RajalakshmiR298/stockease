<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

$supplierName = trim($_POST['supplier_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$address = trim($_POST['address'] ?? '');

if ($supplierName === '') {
    redirect_with_message('../pages/suppliers.php', 'error', 'Supplier name is required.');
}

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect_with_message('../pages/suppliers.php', 'error', 'Please enter a valid supplier email.');
}

$stmt = $conn->prepare('INSERT INTO suppliers (supplier_name, phone, email, address) VALUES (?, ?, ?, ?)');
$stmt->bind_param('ssss', $supplierName, $phone, $email, $address);

if ($stmt->execute()) {
    $stmt->close();
    redirect_with_message('../pages/suppliers.php', 'success', 'Supplier added successfully.');
}

$stmt->close();
redirect_with_message('../pages/suppliers.php', 'error', 'Unable to add supplier.');
