<?php
// Testing config - minimal setup
return [
    'db' => [
        'host' => 'localhost',
        'database' => 'crm_test',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
    ],
    'timezone' => 'Asia/Dhaka',
    'currency_symbol' => '৳',
    'default_month_format' => 'Y-m',
    'session_name' => 'crm_session',
    'csrf_token_name' => 'csrf_token',
    'export_path' => __DIR__ . '/storage/exports/',
    'log_path' => __DIR__ . '/storage/logs/',
];