<?php
require_once __DIR__ . '/../backend/auth_check.php';
require_once __DIR__ . '/../backend/helpers.php';

$pageTitle = $pageTitle ?? 'StockEase';
$currentPage = basename($_SERVER['PHP_SELF']);
$flash = get_flash_message();
$username = $_SESSION['username'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?> - StockEase</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="app-layout">
    <aside class="sidebar">
        <div class="brand">StockEase</div>
        <nav class="menu">
            <a class="<?php echo $currentPage === 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">Dashboard</a>
            <a class="<?php echo $currentPage === 'products.php' ? 'active' : ''; ?>" href="products.php">Products</a>
            <a class="<?php echo $currentPage === 'categories.php' ? 'active' : ''; ?>" href="categories.php">Categories</a>
            <a class="<?php echo $currentPage === 'suppliers.php' ? 'active' : ''; ?>" href="suppliers.php">Suppliers</a>
            <a class="<?php echo $currentPage === 'purchases.php' ? 'active' : ''; ?>" href="purchases.php">Purchases</a>
            <a class="<?php echo $currentPage === 'sales.php' ? 'active' : ''; ?>" href="sales.php">Sales</a>
            <a class="<?php echo $currentPage === 'inventory.php' ? 'active' : ''; ?>" href="inventory.php">Inventory</a>
        </nav>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <h1><?php echo e($pageTitle); ?></h1>
            <div class="topbar-right">
                <span>Welcome, <?php echo e($username); ?></span>
                <a class="btn btn-dark" href="../auth/logout.php">Logout</a>
            </div>
        </header>

        <?php if ($flash): ?>
            <div class="alert <?php echo $flash['type'] === 'success' ? 'alert-success' : 'alert-error'; ?>">
                <?php echo e($flash['message']); ?>
            </div>
        <?php endif; ?>

        <section class="content">
