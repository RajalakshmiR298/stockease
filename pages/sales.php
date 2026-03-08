<?php
require_once __DIR__ . '/../backend/db.php';
$pageTitle = 'Sales';
require_once __DIR__ . '/../includes/header.php';

$productList = $conn->query('SELECT product_id, product_name, price, stock_quantity FROM products ORDER BY product_name ASC');

$sales = $conn->query('SELECT si.sale_item_id, s.sale_date, p.product_name, si.quantity, si.price,
    (si.quantity * si.price) AS line_total
    FROM sales_items si
    INNER JOIN sales s ON si.sale_id = s.sale_id
    INNER JOIN products p ON si.product_id = p.product_id
    ORDER BY s.sale_date DESC, si.sale_item_id DESC');
?>

<div class="card">
    <h2>Record Sale</h2>
    <form method="POST" action="../backend/add_sale.php" class="form-grid">
        <label>Product</label>
        <select name="product_id" id="sale_product" required>
            <option value="">Select Product</option>
            <?php while ($product = $productList->fetch_assoc()): ?>
                <option value="<?php echo (int)$product['product_id']; ?>" data-price="<?php echo e($product['price']); ?>">
                    <?php echo e($product['product_name']); ?> (Stock: <?php echo (int)$product['stock_quantity']; ?>)
                </option>
            <?php endwhile; ?>
        </select>

        <div class="row-2">
            <div>
                <label>Quantity</label>
                <input type="number" name="quantity" min="1" required>
            </div>
            <div>
                <label>Price (per item)</label>
                <input type="number" id="sale_price" name="price" step="0.01" min="0.01" required>
            </div>
        </div>

        <label>Sale Date</label>
        <input type="date" name="sale_date" value="<?php echo date('Y-m-d'); ?>" required>

        <button type="submit" class="btn btn-primary">Save Sale</button>
    </form>
</div>

<div class="card">
    <h2>Sales History</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($sales->num_rows > 0): ?>
                <?php while ($row = $sales->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo e($row['sale_date']); ?></td>
                        <td><?php echo e($row['product_name']); ?></td>
                        <td><?php echo (int)$row['quantity']; ?></td>
                        <td><?php echo number_format((float)$row['price'], 2); ?></td>
                        <td><?php echo number_format((float)$row['line_total'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No sales records found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
const saleProduct = document.getElementById('sale_product');
const salePrice = document.getElementById('sale_price');

if (saleProduct && salePrice) {
    saleProduct.addEventListener('change', function () {
        const selected = saleProduct.options[saleProduct.selectedIndex];
        if (selected && selected.dataset.price) {
            salePrice.value = selected.dataset.price;
        }
    });
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
