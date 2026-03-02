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
        // Fetch dashboard statistics
        $db = get_db();
        $stats = [];
        
        // Total employees
        $stmt = $db->query("SELECT COUNT(*) FROM employees");
        $stats['total_employees'] = $stmt->fetchColumn();
        
        // Total clients
        $stmt = $db->query("SELECT COUNT(*) FROM clients");
        $stats['total_clients'] = $stmt->fetchColumn();
        
        // Current month transactions
        $current_month = date('Y-m');
        $stmt = $db->prepare("SELECT COUNT(*), SUM(amount) FROM transactions WHERE month_year = ? AND type = 'received' AND status = 'completed'");
        $stmt->execute([$current_month]);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stats['monthly_received_count'] = $row[0];
        $stats['monthly_received_amount'] = $row[1] ?? 0;
        
        $stmt = $db->prepare("SELECT COUNT(*), SUM(amount) FROM transactions WHERE month_year = ? AND type = 'pending'");
        $stmt->execute([$current_month]);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stats['monthly_pending_count'] = $row[0];
        $stats['monthly_pending_amount'] = $row[1] ?? 0;
        
        include __DIR__ . '/views/dashboard.php';
        break;
    case 'employees':
        require_once __DIR__ . '/controllers/EmployeesController.php';
        $action = $_GET['action'] ?? 'index';
        switch ($action) {
            case 'index':
                employees_index_page();
                break;
            case 'create':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    employees_create_handler();
                } else {
                    employees_create_page();
                }
                break;
            case 'edit':
                $id = $_GET['id'] ?? 0;
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    employees_edit_handler($id);
                } else {
                    employees_edit_page($id);
                }
                break;
            case 'delete':
                employees_delete_handler($_GET['id'] ?? 0);
                break;
            default:
                employees_index_page();
                break;
        }
        break;
    case 'clients':
        require_once __DIR__ . '/controllers/ClientsController.php';
        $action = $_GET['action'] ?? 'index';
        switch ($action) {
            case 'index':
                clients_index_page();
                break;
            case 'create':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    clients_create_handler();
                } else {
                    clients_create_page();
                }
                break;
            case 'edit':
                $id = $_GET['id'] ?? 0;
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    clients_edit_handler($id);
                } else {
                    clients_edit_page($id);
                }
                break;
            case 'delete':
                clients_delete_handler($_GET['id'] ?? 0);
                break;
            default:
                clients_index_page();
                break;
        }
        break;
    case 'transactions':
        require_once __DIR__ . '/controllers/TransactionsController.php';
        $action = $_GET['action'] ?? 'index';
        switch ($action) {
            case 'index':
                transactions_index_page();
                break;
            case 'create':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    transactions_create_handler();
                } else {
                    transactions_create_page();
                }
                break;
            case 'edit':
                $id = $_GET['id'] ?? 0;
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    transactions_edit_handler($id);
                } else {
                    transactions_edit_page($id);
                }
                break;
            case 'delete':
                transactions_delete_handler($_GET['id'] ?? 0);
                break;
            default:
                transactions_index_page();
                break;
        }
        break;
    case 'profile':
        include __DIR__ . '/views/profile.php';
        break;
    case 'reports':
        include __DIR__ . '/views/reports.php';
        break;
    case 'users':
        require_once __DIR__ . '/controllers/UsersController.php';
        $action = $_GET['action'] ?? 'index';
        switch ($action) {
            case 'index':
                users_index_page();
                break;
            case 'create':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    users_create_handler();
                } else {
                    users_create_page();
                }
                break;
            case 'edit':
                $id = $_GET['id'] ?? 0;
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    users_edit_handler($id);
                } else {
                    users_edit_page($id);
                }
                break;
            case 'delete':
                users_delete_handler($_GET['id'] ?? 0);
                break;
            default:
                users_index_page();
                break;
        }
        break;
    case 'activity_logs':
        include __DIR__ . '/views/activity_logs.php';
        break;
    case 'month_close':
        include __DIR__ . '/views/month_close.php';
        break;
    default:
        http_response_code(404);
        echo '<div class="p-8"><h1 class="text-2xl font-bold mb-4">404 Not Found</h1><p>Page not found.</p></div>';
        break;
}

include __DIR__ . '/views/layout/footer.php';
