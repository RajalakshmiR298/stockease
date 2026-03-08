<?php
require_once __DIR__ . '/../backend/helpers.php';

if (isset($_SESSION['user_id'])) {
    header('Location: ../pages/dashboard.php');
    exit;
}

$flash = get_flash_message();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - StockEase</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="auth-body">
<div class="auth-card">
    <h1>StockEase</h1>
    <p class="auth-subtitle">Login to continue</p>

    <?php if ($flash): ?>
        <div class="alert <?php echo $flash['type'] === 'success' ? 'alert-success' : 'alert-error'; ?>">
            <?php echo e($flash['message']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="../backend/login_user.php" class="form-grid">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn btn-primary full">Login</button>
    </form>

    <p class="auth-link">No account? <a href="register.php">Register here</a></p>
</div>
</body>
</html>
