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
    <title>Register - StockEase</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="auth-body">
<div class="auth-card">
    <h1>Create Account</h1>
    <p class="auth-subtitle">Start managing your inventory</p>

    <?php if ($flash): ?>
        <div class="alert <?php echo $flash['type'] === 'success' ? 'alert-success' : 'alert-error'; ?>">
            <?php echo e($flash['message']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="../backend/register_user.php" class="form-grid">
        <label>Username</label>
        <input type="text" name="username" required maxlength="50">

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required minlength="6">

        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required minlength="6">

        <button type="submit" class="btn btn-primary full">Register</button>
    </form>

    <p class="auth-link">Already have an account? <a href="login.php">Login</a></p>
</div>
</body>
</html>
