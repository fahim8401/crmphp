<?php
// controllers/TransactionsController.php
// Handles transaction CRUD for HPLink CRM

require_once __DIR__ . '/../lib/database.php';
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/role_checks.php';

// List transactions (admin, hr)
function transactions_index_page() {
    require_role(['admin', 'hr']);
    $db = get_db();
    
    // Get current month or filter
    $month_year = $_GET['month'] ?? date('Y-m');
    
    $stmt = $db->prepare("
        SELECT t.*, e.name AS employee_name, c.name AS client_name, u.name AS created_by_name
        FROM transactions t
        LEFT JOIN employees e ON t.employee_id = e.id
        LEFT JOIN clients c ON t.client_id = c.id
        LEFT JOIN users u ON t.created_by_user_id = u.id
        WHERE t.month_year = ?
        ORDER BY t.created_at DESC
    ");
    $stmt->execute([$month_year]);
    $transactions = $stmt->fetchAll();
    
    include __DIR__ . '/../views/transactions/index.php';
}

// Create transaction (admin, hr)
function transactions_create_page($error = null) {
    require_role(['admin', 'hr']);
    $db = get_db();
    
    // Get employees and clients for dropdowns
    $employees = $db->query("SELECT id, name FROM employees ORDER BY name")->fetchAll();
    $clients = $db->query("SELECT id, name FROM clients ORDER BY name")->fetchAll();
    
    include __DIR__ . '/../views/transactions/create.php';
}

function transactions_create_handler() {
    require_role(['admin', 'hr']);
    $db = get_db();
    $user = current_user();
    
    $type = $_POST['type'];
    $employee_id = !empty($_POST['employee_id']) ? $_POST['employee_id'] : null;
    $client_id = !empty($_POST['client_id']) ? $_POST['client_id'] : null;
    $amount = floatval($_POST['amount']);
    $description = trim($_POST['description']);
    $month_year = $_POST['month_year'];
    $settle_date = !empty($_POST['settle_date']) ? $_POST['settle_date'] : null;
    $status = ($type === 'pending') ? 'pending' : 'completed';
    
    $stmt = $db->prepare("
        INSERT INTO transactions 
        (employee_id, client_id, type, amount, description, status, month_year, settle_date, created_by_user_id, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");
    $stmt->execute([
        $employee_id, $client_id, $type, $amount, $description, $status, $month_year, $settle_date, $user['id']
    ]);
    
    set_flash('success', 'Transaction created successfully.');
    header('Location: ?page=transactions&month=' . $month_year);
    exit;
}

// Edit transaction (admin, hr)
function transactions_edit_page($id, $error = null) {
    require_role(['admin', 'hr']);
    $db = get_db();
    
    $stmt = $db->prepare("SELECT * FROM transactions WHERE id = ?");
    $stmt->execute([$id]);
    $transaction = $stmt->fetch();
    
    if (!$transaction) {
        set_flash('error', 'Transaction not found.');
        header('Location: ?page=transactions');
        exit;
    }
    
    // Get employees and clients for dropdowns
    $employees = $db->query("SELECT id, name FROM employees ORDER BY name")->fetchAll();
    $clients = $db->query("SELECT id, name FROM clients ORDER BY name")->fetchAll();
    
    include __DIR__ . '/../views/transactions/edit.php';
}

function transactions_edit_handler($id) {
    require_role(['admin', 'hr']);
    $db = get_db();
    $user = current_user();
    
    $type = $_POST['type'];
    $employee_id = !empty($_POST['employee_id']) ? $_POST['employee_id'] : null;
    $client_id = !empty($_POST['client_id']) ? $_POST['client_id'] : null;
    $amount = floatval($_POST['amount']);
    $description = trim($_POST['description']);
    $month_year = $_POST['month_year'];
    $settle_date = !empty($_POST['settle_date']) ? $_POST['settle_date'] : null;
    $status = $_POST['status'];
    
    $stmt = $db->prepare("
        UPDATE transactions 
        SET employee_id=?, client_id=?, type=?, amount=?, description=?, status=?, 
            month_year=?, settle_date=?, changed_by_user_id=?, changed_at=NOW(), updated_at=NOW()
        WHERE id=?
    ");
    $stmt->execute([
        $employee_id, $client_id, $type, $amount, $description, $status, 
        $month_year, $settle_date, $user['id'], $id
    ]);
    
    set_flash('success', 'Transaction updated successfully.');
    header('Location: ?page=transactions&month=' . $month_year);
    exit;
}

// Delete transaction (admin only)
function transactions_delete_handler($id) {
    require_role(['admin']);
    $db = get_db();
    
    // Get month_year before deleting
    $stmt = $db->prepare("SELECT month_year FROM transactions WHERE id = ?");
    $stmt->execute([$id]);
    $transaction = $stmt->fetch();
    $month_year = $transaction['month_year'] ?? date('Y-m');
    
    $stmt = $db->prepare("DELETE FROM transactions WHERE id = ?");
    $stmt->execute([$id]);
    
    set_flash('success', 'Transaction deleted successfully.');
    header('Location: ?page=transactions&month=' . $month_year);
    exit;
}
