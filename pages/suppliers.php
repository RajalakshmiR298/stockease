<?php
require_once __DIR__ . '/../backend/db.php';
$pageTitle = 'Suppliers';
require_once __DIR__ . '/../includes/header.php';

$editSupplier = null;
$editId = (int)($_GET['edit'] ?? 0);
if ($editId > 0) {
    $editStmt = $conn->prepare('SELECT supplier_id, supplier_name, phone, email, address FROM suppliers WHERE supplier_id = ? LIMIT 1');
    $editStmt->bind_param('i', $editId);
    $editStmt->execute();
    $editSupplier = $editStmt->get_result()->fetch_assoc();
    $editStmt->close();
}

$suppliers = $conn->query('SELECT supplier_id, supplier_name, phone, email, address FROM suppliers ORDER BY supplier_name ASC');
?>

<div class="card">
    <h2><?php echo $editSupplier ? 'Edit Supplier' : 'Add Supplier'; ?></h2>
    <form method="POST" action="../backend/<?php echo $editSupplier ? 'update_supplier.php' : 'add_supplier.php'; ?>" class="form-grid">
        <?php if ($editSupplier): ?>
            <input type="hidden" name="supplier_id" value="<?php echo (int)$editSupplier['supplier_id']; ?>">
        <?php endif; ?>

        <label>Supplier Name</label>
        <input type="text" name="supplier_name" required value="<?php echo e($editSupplier['supplier_name'] ?? ''); ?>">

        <div class="row-2">
            <div>
                <label>Phone</label>
                <input type="text" name="phone" value="<?php echo e($editSupplier['phone'] ?? ''); ?>">
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" value="<?php echo e($editSupplier['email'] ?? ''); ?>">
            </div>
        </div>

        <label>Address</label>
        <textarea name="address" rows="3"><?php echo e($editSupplier['address'] ?? ''); ?></textarea>

        <div class="action-group">
            <button type="submit" class="btn btn-primary"><?php echo $editSupplier ? 'Update Supplier' : 'Add Supplier'; ?></button>
            <?php if ($editSupplier): ?>
                <a href="suppliers.php" class="btn btn-secondary">Cancel</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="card">
    <h2>Supplier List</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($supplier = $suppliers->fetch_assoc()): ?>
                <tr>
                    <td><?php echo (int)$supplier['supplier_id']; ?></td>
                    <td><?php echo e($supplier['supplier_name']); ?></td>
                    <td><?php echo e($supplier['phone']); ?></td>
                    <td><?php echo e($supplier['email']); ?></td>
                    <td><?php echo e($supplier['address']); ?></td>
                    <td class="action-group">
                        <a class="btn btn-secondary" href="suppliers.php?edit=<?php echo (int)$supplier['supplier_id']; ?>">Edit</a>
                        <a class="btn btn-danger" data-confirm="Delete this supplier?" href="../backend/delete_supplier.php?id=<?php echo (int)$supplier['supplier_id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
