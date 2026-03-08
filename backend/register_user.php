<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../auth/register.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

if ($username === '' || $email === '' || $password === '') {
    redirect_with_message('../auth/register.php', 'error', 'All fields are required.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect_with_message('../auth/register.php', 'error', 'Please enter a valid email address.');
}

if ($password !== $confirmPassword) {
    redirect_with_message('../auth/register.php', 'error', 'Passwords do not match.');
}

if (strlen($password) < 6) {
    redirect_with_message('../auth/register.php', 'error', 'Password must be at least 6 characters.');
}

$checkStmt = $conn->prepare('SELECT user_id FROM users WHERE email = ? OR username = ? LIMIT 1');
$checkStmt->bind_param('ss', $email, $username);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    $checkStmt->close();
    redirect_with_message('../auth/register.php', 'error', 'Username or email already exists.');
}
$checkStmt->close();

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$insertStmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
$insertStmt->bind_param('sss', $username, $email, $hashedPassword);

if ($insertStmt->execute()) {
    $insertStmt->close();
    redirect_with_message('../auth/login.php', 'success', 'Registration successful. Please login.');
}

$insertStmt->close();
redirect_with_message('../auth/register.php', 'error', 'Registration failed. Try again.');
