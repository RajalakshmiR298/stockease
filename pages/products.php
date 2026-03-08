<?php
require_once __DIR__ . '/../backend/db.php';
$pageTitle = 'Products';
require_once __DIR__ . '/../includes/header.php';

$categories = $conn->query('SELECT category_id, category_name FROM categories ORDER BY category_name ASC');
$suppliers = $conn->query('SELECT supplier_id, supplier_name FROM suppliers ORDER BY supplier_name ASC');

$editProduct = null;
$editId = (int)($_GET['edit'] ?? 0);
if ($editId > 0) {
    $editStmt = $conn->prepare('SELECT product_id, product_name, category_id, supplier_id, price, stock_quantity, low_stock_level FROM products WHERE product_id = ? LIMIT 1');
    $editStmt->bind_param('i', $editId);
    $editStmt->execute();
    $editProduct = $editStmt->get_result()->fetch_assoc();
    $editStmt->close();
}

$productList = $conn->query('SELECT p.product_id, p.product_name, c.category_name, s.supplier_name, p.price, p.stock_quantity, p.low_stock_level
    FROM products p
    INNER JOIN categories c ON p.category_id = c.category_id
    INNER JOIN suppliers s ON p.supplier_id = s.supplier_id
    ORDER BY p.product_name ASC');
?>

<div class="card">
    <h2><?php echo $editProduct ? 'Edit Product' : 'Add Product'; ?></h2>
    <form method="POST" action="../backend/<?php echo $editProduct ? 'update_product.php' : 'add_product.php'; ?>" class="form-grid">
        <?php if ($editProduct): ?>
            <input type="hidden" name="product_id" value="<?php echo (int)$editProduct['product_id']; ?>">
        <?php endif; ?>

        <label>Product Name</label>
        <input type="text" name="product_name" required value="<?php echo e($editProduct['product_name'] ?? ''); ?>">

        <div class="row-2">
            <div>
                <label>Category</label>
                <select name="category_id" required>
                    <option value="">Select Category</option>
                    <?php while ($category = $categories->fetch_assoc()): ?>
                        <option value="<?php echo (int)$category['category_id']; ?>" <?php echo (($editProduct['category_id'] ?? 0) == $category['category_id']) ? 'selected' : ''; ?>>
                            <?php echo e($category['category_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label>Supplier</label>
                <select name="supplier_id" required>
                    <option value="">Select Supplier</option>
                    <?php while ($supplier = $suppliers->fetch_assoc()): ?>
                        <option value="<?php echo (int)$supplier['supplier_id']; ?>" <?php echo (($editProduct['supplier_id'] ?? 0) == $supplier['supplier_id']) ? 'selected' : ''; ?>>
                            <?php echo e($supplier['supplier_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>

        <div class="row-2">
            <div>
                <label>Price</label>
                <input type="number" name="price" step="0.01" min="0" required value="<?php echo e($editProduct['price'] ?? ''); ?>">
            </div>
            <div>
                <label>Stock Quantity</label>
                <input type="number" name="stock_quantity" min="0" required value="<?php echo e($editProduct['stock_quantity'] ?? 0); ?>">
            </div>
        </div>

        <label>Low Stock Level</label>
        <input type="number" name="low_stock_level" min="0" required value="<?php echo e($editProduct['low_stock_level'] ?? 10); ?>">

        <div class="action-group">
            <button type="submit" class="btn btn-primary"><?php echo $editProduct ? 'Update Product' : 'Add Product'; ?></button>
            <?php if ($editProduct): ?>
                <a href="products.php" class="btn btn-secondary">Cancel</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="card">
    <h2>Product List</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Supplier</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Low Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($product = $productList->fetch_assoc()): ?>
                <tr>
                    <td><?php echo (int)$product['product_id']; ?></td>
                    <td><?php echo e($product['product_name']); ?></td>
                    <td><?php echo e($product['category_name']); ?></td>
                    <td><?php echo e($product['supplier_name']); ?></td>
                    <td><?php echo number_format((float)$product['price'], 2); ?></td>
                    <td><?php echo (int)$product['stock_quantity']; ?></td>
                    <td>
                        <?php if ((int)$product['stock_quantity'] <= (int)$product['low_stock_level']): ?>
                            <span class="badge badge-low"><?php echo (int)$product['low_stock_level']; ?> (Low)</span>
                        <?php else: ?>
                            <?php echo (int)$product['low_stock_level']; ?>
                        <?php endif; ?>
                    </td>
                    <td class="action-group">
                        <a class="btn btn-secondary" href="products.php?edit=<?php echo (int)$product['product_id']; ?>">Edit</a>
                        <a class="btn btn-danger" data-confirm="Delete this product?" href="../backend/delete_product.php?id=<?php echo (int)$product['product_id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
