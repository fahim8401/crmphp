<?php
// controllers/EmployeesController.php
// Handles employee CRUD for HPLink CRM

require_once __DIR__ . '/../lib/database.php';
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/role_checks.php';

// List employees (admin, hr)
function employees_index_page() {
    require_role(['admin', 'hr']);
    $db = get_db();
    $stmt = $db->query("SELECT * FROM employees ORDER BY created_at DESC");
    $employees = $stmt->fetchAll();
    include __DIR__ . '/../views/employees/index.php';
}

// Create employee (admin, hr)
function employees_create_page($error = null) {
    require_role(['admin', 'hr']);
    include __DIR__ . '/../views/employees/create.php';
}

function employees_create_handler() {
    require_role(['admin', 'hr']);
    $db = get_db();
    $stmt = $db->prepare("INSERT INTO employees (name, phone, email, base_salary, joined_at, notes, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([
        trim($_POST['name']),
        trim($_POST['phone']),
        trim($_POST['email']),
        floatval($_POST['base_salary']),
        $_POST['joined_at'],
        trim($_POST['notes'])
    ]);
    header('Location: ?page=employees');
    exit;
}

// Edit employee (admin, hr)
function employees_edit_page($id, $error = null) {
    require_role(['admin', 'hr']);
    $db = get_db();
    $stmt = $db->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->execute([$id]);
    $employee = $stmt->fetch();
    include __DIR__ . '/../views/employees/edit.php';
}

function employees_edit_handler($id) {
    require_role(['admin', 'hr']);
    $db = get_db();
    $stmt = $db->prepare("UPDATE employees SET name=?, phone=?, email=?, base_salary=?, joined_at=?, notes=? WHERE id=?");
    $stmt->execute([
        trim($_POST['name']),
        trim($_POST['phone']),
        trim($_POST['email']),
        floatval($_POST['base_salary']),
        $_POST['joined_at'],
        trim($_POST['notes']),
        $id
    ]);
    header('Location: ?page=employees');
    exit;
}

// Delete employee (admin only)
function employees_delete_handler($id) {
    require_role(['admin']);
    $db = get_db();
    $stmt = $db->prepare("DELETE FROM employees WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: ?page=employees');
    exit;
}
