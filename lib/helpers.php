<?php
// lib/helpers.php
// Utility functions

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/database.php';

function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Flash messages (stored in session)
function set_flash($type, $msg) {
    start_session();
    $_SESSION['flash'][$type][] = $msg;
}

function get_flash($type = null) {
    start_session();
    if ($type) {
        $msgs = $_SESSION['flash'][$type] ?? [];
        unset($_SESSION['flash'][$type]);
        return $msgs;
    } else {
        $all = $_SESSION['flash'] ?? [];
        $_SESSION['flash'] = [];
        return $all;
    }
}

// Pagination helper
function paginate($total, $per_page = 25, $page = 1) {
    $pages = max(1, ceil($total / $per_page));
    $page = max(1, min($pages, (int)$page));
    $offset = ($page - 1) * $per_page;
    return compact('pages', 'page', 'offset', 'per_page');
}

// Date formatting
function format_date($dt, $fmt = 'Y-m-d H:i') {
    if (!$dt) return '';
    return date($fmt, strtotime($dt));
}

// Currency formatting
function format_currency($amount) {
    $cfg = get_config();
    return $cfg['currency_symbol'] . number_format((float)$amount, 2);
}
