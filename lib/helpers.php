<?php
// lib/helpers.php
// Utility functions

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/database.php';

/**
 * Escape HTML special characters for safe output
 * @param string|null $str The string to escape
 * @return string The escaped string
 */
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Set a flash message in the session
 * @param string $type Message type (success, error, info, warning)
 * @param string $msg The message to display
 * @return void
 */
function set_flash($type, $msg) {
    start_session();
    $_SESSION['flash'][$type][] = $msg;
}

/**
 * Get and clear flash messages from the session
 * @param string|null $type Optional type to get specific messages
 * @return array Flash messages
 */
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

/**
 * Calculate pagination parameters
 * @param int $total Total number of items
 * @param int $per_page Items per page
 * @param int $page Current page number
 * @return array Pagination details
 */
function paginate($total, $per_page = 25, $page = 1) {
    $pages = max(1, ceil($total / $per_page));
    $page = max(1, min($pages, (int)$page));
    $offset = ($page - 1) * $per_page;
    return compact('pages', 'page', 'offset', 'per_page');
}

/**
 * Format a date string
 * @param string|null $dt Date string
 * @param string $fmt Format string
 * @return string Formatted date
 */
function format_date($dt, $fmt = 'Y-m-d H:i') {
    if (!$dt) return '';
    return date($fmt, strtotime($dt));
}

/**
 * Format a number as currency
 * @param float $amount Amount to format
 * @return string Formatted currency string
 */
function format_currency($amount) {
    $cfg = get_config();
    return $cfg['currency_symbol'] . number_format((float)$amount, 2);
}
