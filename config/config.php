<?php
// Central configuration for local and shared-hosting deployment.
return [
    'db_host' => getenv('DB_HOST') ?: 'mysql.railway.internal',
    'db_name' => getenv('DB_NAME') ?: 'stockease',
    'db_user' => getenv('DB_USER') ?: 'root',
    'db_pass' => getenv('DB_PASS') ?: 'gYEiPRjXUjiDTgflUBnFuLMnTstiCgKo',
    'base_url' => getenv('BASE_URL') ?: 'mysql://root:gYEiPRjXUjiDTgflUBnFuLMnTstiCgKo@mysql.railway.internal:3306/railway'
];
