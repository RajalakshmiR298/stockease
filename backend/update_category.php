<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

$categoryId = (int)($_POST['category_id'] ?? 0);
$categoryName = trim($_POST['category_name'] ?? '');
$description = trim($_POST['description'] ?? '');

if ($categoryId <= 0 || $categoryName === '') {
    redirect_with_message('../pages/categories.php', 'error', 'Invalid category data.');
}

$stmt = $conn->prepare('UPDATE categories SET category_name = ?, description = ? WHERE category_id = ?');
$stmt->bind_param('ssi', $categoryName, $description, $categoryId);

if ($stmt->execute()) {
    $stmt->close();
    redirect_with_message('../pages/categories.php', 'success', 'Category updated successfully.');
}

$stmt->close();
redirect_with_message('../pages/categories.php', 'error', 'Unable to update category.');
