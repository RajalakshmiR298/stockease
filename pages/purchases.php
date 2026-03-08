<?php
require_once __DIR__ . '/../backend/db.php';
$pageTitle = 'Purchases';
require_once __DIR__ . '/../includes/header.php';

$supplierList = $conn->query('SELECT supplier_id, supplier_name FROM suppliers ORDER BY supplier_name ASC');
$productList = $conn->query('SELECT product_id, product_name, price FROM products ORDER BY product_name ASC');

$purchases = $conn->query('SELECT pi.purchase_item_id, p.purchase_date, s.supplier_name, pr.product_name, pi.quantity, pi.price,
    (pi.quantity * pi.price) AS line_total
    FROM purchase_items pi
    INNER JOIN purchases p ON pi.purchase_id = p.purchase_id
    INNER JOIN suppliers s ON p.supplier_id = s.supplier_id
    INNER JOIN products pr ON pi.product_id = pr.product_id
    ORDER BY p.purchase_date DESC, pi.purchase_item_id DESC');
?>

<div class="card">
    <h2>Record Purchase</h2>
    <form method="POST" action="../backend/add_purchase.php" class="form-grid">
        <div class="row-2">
            <div>
                <label>Supplier</label>
                <select name="supplier_id" required>
                    <option value="">Select Supplier</option>
                    <?php while ($supplier = $supplierList->fetch_assoc()): ?>
                        <option value="<?php echo (int)$supplier['supplier_id']; ?>"><?php echo e($supplier['supplier_name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label>Product</label>
                <select name="product_id" id="purchase_product" required>
                    <option value="">Select Product</option>
                    <?php while ($product = $productList->fetch_assoc()): ?>
                        <option value="<?php echo (int)$product['product_id']; ?>" data-price="<?php echo e($product['price']); ?>">
                            <?php echo e($product['product_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>

        <div class="row-2">
            <div>
                <label>Quantity</label>
                <input type="number" name="quantity" min="1" required>
            </div>
            <div>
                <label>Price (per item)</label>
                <input type="number" id="purchase_price" name="price" step="0.01" min="0.01" required>
            </div>
        </div>

        <label>Purchase Date</label>
        <input type="date" name="purchase_date" value="<?php echo date('Y-m-d'); ?>" required>

        <button type="submit" class="btn btn-primary">Save Purchase</button>
    </form>
</div>

<div class="card">
    <h2>Purchase History</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Supplier</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($purchases->num_rows > 0): ?>
                <?php while ($row = $purchases->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo e($row['purchase_date']); ?></td>
                        <td><?php echo e($row['supplier_name']); ?></td>
                        <td><?php echo e($row['product_name']); ?></td>
                        <td><?php echo (int)$row['quantity']; ?></td>
                        <td><?php echo number_format((float)$row['price'], 2); ?></td>
                        <td><?php echo number_format((float)$row['line_total'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No purchase records found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
const purchaseProduct = document.getElementById('purchase_product');
const purchasePrice = document.getElementById('purchase_price');

if (purchaseProduct && purchasePrice) {
    purchaseProduct.addEventListener('change', function () {
        const selected = purchaseProduct.options[purchaseProduct.selectedIndex];
        if (selected && selected.dataset.price) {
            purchasePrice.value = selected.dataset.price;
        }
    });
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
