<?php
require_once __DIR__ . '/../backend/db.php';
$pageTitle = 'Dashboard';
require_once __DIR__ . '/../includes/header.php';

$totalProducts = (int)$conn->query('SELECT COUNT(*) AS total FROM products')->fetch_assoc()['total'];
$totalPurchases = (int)$conn->query('SELECT COUNT(*) AS total FROM purchases')->fetch_assoc()['total'];
$totalSales = (int)$conn->query('SELECT COUNT(*) AS total FROM sales')->fetch_assoc()['total'];
$lowStockCount = (int)$conn->query('SELECT COUNT(*) AS total FROM products WHERE stock_quantity <= low_stock_level')->fetch_assoc()['total'];

$lowStockProducts = $conn->query('SELECT product_name, stock_quantity, low_stock_level FROM products WHERE stock_quantity <= low_stock_level ORDER BY stock_quantity ASC');
?>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Products</h3>
        <p><?php echo $totalProducts; ?></p>
    </div>
    <div class="stat-card">
        <h3>Total Purchases</h3>
        <p><?php echo $totalPurchases; ?></p>
    </div>
    <div class="stat-card">
        <h3>Total Sales</h3>
        <p><?php echo $totalSales; ?></p>
    </div>
    <div class="stat-card">
        <h3>Low Stock Products</h3>
        <p><?php echo $lowStockCount; ?></p>
    </div>
</div>

<div class="card">
    <h2>Low Stock Alert</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Current Stock</th>
                    <th>Low Stock Level</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($lowStockProducts && $lowStockProducts->num_rows > 0): ?>
                <?php while ($item = $lowStockProducts->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo e($item['product_name']); ?></td>
                        <td><?php echo (int)$item['stock_quantity']; ?></td>
                        <td><?php echo (int)$item['low_stock_level']; ?></td>
                        <td><span class="badge badge-low">Low Stock</span></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No low stock products right now.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
