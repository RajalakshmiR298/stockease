<?php
require_once __DIR__ . '/../backend/db.php';
$pageTitle = 'Inventory View';
require_once __DIR__ . '/../includes/header.php';

$inventory = $conn->query('SELECT p.product_name, c.category_name, s.supplier_name, p.price, p.stock_quantity, p.low_stock_level,
    (p.stock_quantity * p.price) AS total_value
    FROM products p
    INNER JOIN categories c ON p.category_id = c.category_id
    INNER JOIN suppliers s ON p.supplier_id = s.supplier_id
    ORDER BY p.product_name ASC');
?>

<div class="card">
    <h2>Current Inventory</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Supplier</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Low Stock Level</th>
                    <th>Total Value</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($inventory->num_rows > 0): ?>
                <?php while ($row = $inventory->fetch_assoc()): ?>
                    <?php $isLow = (int)$row['stock_quantity'] <= (int)$row['low_stock_level']; ?>
                    <tr>
                        <td><?php echo e($row['product_name']); ?></td>
                        <td><?php echo e($row['category_name']); ?></td>
                        <td><?php echo e($row['supplier_name']); ?></td>
                        <td><?php echo number_format((float)$row['price'], 2); ?></td>
                        <td><?php echo (int)$row['stock_quantity']; ?></td>
                        <td><?php echo (int)$row['low_stock_level']; ?></td>
                        <td><?php echo number_format((float)$row['total_value'], 2); ?></td>
                        <td>
                            <?php if ($isLow): ?>
                                <span class="badge badge-low">Low Stock</span>
                            <?php else: ?>
                                In Stock
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No inventory data found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
