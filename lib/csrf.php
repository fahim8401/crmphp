<?php
// lib/csrf.php
// Simple CSRF protection

require_once __DIR__ . '/auth.php';

function csrf_token() {
    start_session();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    $token = htmlspecialchars(csrf_token());
    $name = htmlspecialchars(get_config()['csrf_token_name']);
    return '<input type="hidden" name="' . $name . '" value="' . $token . '">';
}

function validate_csrf() {
    start_session();
    $name = get_config()['csrf_token_name'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST[$name] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            echo "Invalid CSRF token.";
            exit;
        }
    }
}
