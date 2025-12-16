<?php
// lib/auth.php
// Authentication and session helpers

require_once __DIR__ . '/database.php';

/**
 * Start PHP session if not already started
 * @return void
 */
function start_session() {
    $cfg = get_config();
    if (session_status() === PHP_SESSION_NONE) {
        session_name($cfg['session_name']);
        session_start();
    }
}

/**
 * Log in a user and set session variables
 * @param array $user User data array
 * @return void
 */
function login($user) {
    start_session();
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['employee_id'] = $user['employee_id'];
}

/**
 * Log out the current user and destroy session
 * @return void
 */
function logout() {
    start_session();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}

/**
 * Check if a user is logged in
 * @return bool True if logged in, false otherwise
 */
function is_logged_in() {
    start_session();
    return isset($_SESSION['user_id']);
}

/**
 * Get the current logged-in user's data
 * @return array|null User data or null if not logged in
 */
function current_user() {
    start_session();
    if (!isset($_SESSION['user_id'])) return null;
    static $user = null;
    if ($user === null) {
        $db = get_db();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
    }
    return $user;
}

/**
 * Hash a password securely
 * @param string $password Plain text password
 * @return string Hashed password
 */
function password_hash_safe($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify a password against a hash
 * @param string $password Plain text password
 * @param string $hash Hashed password
 * @return bool True if password matches, false otherwise
 */
function password_verify_safe($password, $hash) {
    return password_verify($password, $hash);
}
