<?php
// index.php
// Main entry point and router for HPLink CRM

require_once __DIR__ . '/lib/database.php';
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/lib/helpers.php';
require_once __DIR__ . '/lib/role_checks.php';

// Set timezone
date_default_timezone_set(get_config()['timezone'] ?? 'Asia/Dhaka');

// Routing
$page = $_GET['page'] ?? 'dashboard';

// Public pages
if ($page === 'login') {
    require_once __DIR__ . '/controllers/AuthController.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        auth_login_handler();
    } else {
        auth_login_page();
    }
    exit;
}
if ($page === 'logout') {
    require_once __DIR__ . '/controllers/AuthController.php';
    auth_logout_handler();
    exit;
}

// Require login for all other pages
if (!is_logged_in()) {
    header('Location: ?page=login');
    exit;
}

// Layout wrapper for all protected pages
include __DIR__ . '/views/layout/header.php';

switch ($page) {
    case 'dashboard':
        echo '<div class="p-8"><h1 class="text-2xl font-bold mb-4">HPLink CRM Dashboard</h1><p>Welcome, '.e(current_user()['name']).'!</p></div>';
        break;
    // Add more cases for other controllers/entities, e.g.:
    // case 'employees':
    //     require_once __DIR__ . '/controllers/EmployeesController.php';
    //     employees_index_page();
    //     break;
    default:
        http_response_code(404);
        echo '<div class="p-8"><h1 class="text-2xl font-bold mb-4">404 Not Found</h1><p>Page not found.</p></div>';
        break;
}

include __DIR__ . '/views/layout/footer.php';
