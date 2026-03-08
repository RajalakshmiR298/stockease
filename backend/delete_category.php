<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

$categoryId = (int)($_GET['id'] ?? 0);
if ($categoryId <= 0) {
    redirect_with_message('../pages/categories.php', 'error', 'Invalid category selected.');
}

$stmt = $conn->prepare('DELETE FROM categories WHERE category_id = ?');
$stmt->bind_param('i', $categoryId);

if ($stmt->execute()) {
    $stmt->close();
    redirect_with_message('../pages/categories.php', 'success', 'Category deleted successfully.');
}

$stmt->close();
redirect_with_message('../pages/categories.php', 'error', 'Category cannot be deleted while linked to products.');
