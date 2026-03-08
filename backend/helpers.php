<?php
require_once __DIR__ . '/session.php';

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function redirect_with_message(string $path, string $type, string $message): void
{
    $_SESSION['flash_type'] = $type;
    $_SESSION['flash_message'] = $message;
    header('Location: ' . $path);
    exit;
}

function get_flash_message(): ?array
{
    if (!isset($_SESSION['flash_message'], $_SESSION['flash_type'])) {
        return null;
    }

    $flash = [
        'type' => $_SESSION['flash_type'],
        'message' => $_SESSION['flash_message']
    ];

    unset($_SESSION['flash_type'], $_SESSION['flash_message']);
    return $flash;
}
