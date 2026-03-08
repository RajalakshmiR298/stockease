<?php
// Central configuration for local and shared-hosting deployment.
return [
    'db_host' => getenv('DB_HOST') ?: 'caboose.proxy.rlwy.net:50109',
    'db_name' => getenv('DB_NAME') ?: 'stockease',
    'db_user' => getenv('DB_USER') ?: 'root',
    'db_pass' => getenv('DB_PASS') ?: 'gYEiPRjXUjiDTgflUBnFuLMnTstiCgKo'
];
