<?php
// lib/database.php
// Database connection using PDO with exceptions

function get_config() {
    static $config = null;
    if ($config === null) {
        $config = require __DIR__ . '/../config.php';
    }
    return $config;
}

function get_db() {
    static $pdo = null;
    if ($pdo === null) {
        $cfg = get_config()['db'];
        $dsn = "mysql:host={$cfg['host']};dbname={$cfg['database']};charset={$cfg['charset']}";
        try {
            $pdo = new PDO($dsn, $cfg['username'], $cfg['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo "Database connection failed: " . htmlspecialchars($e->getMessage());
            exit;
        }
    }
    return $pdo;
}
