<?php
require_once __DIR__ . '/auth_check.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

$categoryName = trim($_POST['category_name'] ?? '');
$description = trim($_POST['description'] ?? '');

if ($categoryName === '') {
    redirect_with_message('../pages/categories.php', 'error', 'Category name is required.');
}

$stmt = $conn->prepare('INSERT INTO categories (category_name, description) VALUES (?, ?)');
$stmt->bind_param('ss', $categoryName, $description);

if ($stmt->execute()) {
    $stmt->close();
    redirect_with_message('../pages/categories.php', 'success', 'Category added successfully.');
}

$stmt->close();
redirect_with_message('../pages/categories.php', 'error', 'Unable to add category.');
