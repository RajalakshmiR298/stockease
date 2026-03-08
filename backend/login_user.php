<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../auth/login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    redirect_with_message('../auth/login.php', 'error', 'Email and password are required.');
}

$stmt = $conn->prepare('SELECT user_id, username, password FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || !password_verify($password, $user['password'])) {
    redirect_with_message('../auth/login.php', 'error', 'Invalid login credentials.');
}

$_SESSION['user_id'] = $user['user_id'];
$_SESSION['username'] = $user['username'];

redirect_with_message('../pages/dashboard.php', 'success', 'Login successful.');
