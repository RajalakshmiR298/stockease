<?php
require_once __DIR__ . '/../backend/session.php';

session_unset();
session_destroy();

header('Location: login.php');
exit;
