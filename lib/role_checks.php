<?php
// lib/role_checks.php
// Centralized role checks

require_once __DIR__ . '/auth.php';

function require_role($roles) {
    start_session();
    $user_role = $_SESSION['user_role'] ?? null;
    if (is_string($roles)) $roles = [$roles];
    if (!$user_role || !in_array($user_role, $roles, true)) {
        forbidden_page();
        exit;
    }
}

function forbidden_page() {
    http_response_code(403);
    echo '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body style="font-family:sans-serif;text-align:center;padding:2em;"><h1>403 Forbidden</h1><p>You do not have permission to access this page.</p></body></html>';
    exit;
}
