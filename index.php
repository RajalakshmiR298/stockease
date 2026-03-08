<?php
require_once __DIR__ . '/backend/session.php';

if (isset($_SESSION['user_id'])) {
    header('Location: pages/dashboard.php');
    exit;
}

header('Location: auth/login.php');
exit;
