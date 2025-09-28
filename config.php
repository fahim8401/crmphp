<?php
// Copy this file to config.php and update values as needed.

return [
    'db' => [
        'host' => 'localhost',
        'database' => 'crm_db',
        'username' => 'crm_user',
        'password' => 'your_db_password',
        'charset' => 'utf8mb4',
    ],
    'timezone' => 'Asia/Dhaka',
    'currency_symbol' => '৳',
    'default_month_format' => 'Y-m',
    'session_name' => 'crm_session',
    'export_path' => __DIR__ . '/storage/exports/',
    'log_path' => __DIR__ . '/storage/logs/',
];