<?php
// Central configuration for local and shared-hosting deployment.
return [
    'db_host' => getenv('DB_HOST') ?: 'localhost',
    'db_name' => getenv('DB_NAME') ?: 'stockease',
    'db_user' => getenv('DB_USER') ?: 'root',
    'db_pass' => getenv('DB_PASS') ?: '',
    'base_url' => getenv('BASE_URL') ?: '/stockease'
];
