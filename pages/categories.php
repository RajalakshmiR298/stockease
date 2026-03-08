<?php
require_once __DIR__ . '/../backend/db.php';
$pageTitle = 'Categories';
require_once __DIR__ . '/../includes/header.php';

$editCategory = null;
$editId = (int)($_GET['edit'] ?? 0);
if ($editId > 0) {
    $editStmt = $conn->prepare('SELECT category_id, category_name, description FROM categories WHERE category_id = ? LIMIT 1');
    $editStmt->bind_param('i', $editId);
    $editStmt->execute();
    $editCategory = $editStmt->get_result()->fetch_assoc();
    $editStmt->close();
}

$categories = $conn->query('SELECT category_id, category_name, description FROM categories ORDER BY category_name ASC');
?>

<div class="card">
    <h2><?php echo $editCategory ? 'Edit Category' : 'Add Category'; ?></h2>
    <form method="POST" action="../backend/<?php echo $editCategory ? 'update_category.php' : 'add_category.php'; ?>" class="form-grid">
        <?php if ($editCategory): ?>
            <input type="hidden" name="category_id" value="<?php echo (int)$editCategory['category_id']; ?>">
        <?php endif; ?>

        <label>Category Name</label>
        <input type="text" name="category_name" required value="<?php echo e($editCategory['category_name'] ?? ''); ?>">

        <label>Description</label>
        <textarea name="description" rows="3"><?php echo e($editCategory['description'] ?? ''); ?></textarea>

        <div class="action-group">
            <button type="submit" class="btn btn-primary"><?php echo $editCategory ? 'Update Category' : 'Add Category'; ?></button>
            <?php if ($editCategory): ?>
                <a href="categories.php" class="btn btn-secondary">Cancel</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="card">
    <h2>Category List</h2>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($category = $categories->fetch_assoc()): ?>
                <tr>
                    <td><?php echo (int)$category['category_id']; ?></td>
                    <td><?php echo e($category['category_name']); ?></td>
                    <td><?php echo e($category['description']); ?></td>
                    <td class="action-group">
                        <a class="btn btn-secondary" href="categories.php?edit=<?php echo (int)$category['category_id']; ?>">Edit</a>
                        <a class="btn btn-danger" data-confirm="Delete this category?" href="../backend/delete_category.php?id=<?php echo (int)$category['category_id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
