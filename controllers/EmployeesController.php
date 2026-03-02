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
    
    // Validation
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $base_salary = $_POST['base_salary'] ?? '';
    $joined_at = $_POST['joined_at'] ?? '';
    $notes = trim($_POST['notes'] ?? '');
    
    if (empty($name) || empty($phone) || empty($base_salary) || empty($joined_at)) {
        set_flash('error', 'Please fill in all required fields.');
        header('Location: ?page=employees&action=create');
        exit;
    }
    
    if (!is_numeric($base_salary) || $base_salary < 0) {
        set_flash('error', 'Base salary must be a valid positive number.');
        header('Location: ?page=employees&action=create');
        exit;
    }
    
    try {
        $db = get_db();
        $stmt = $db->prepare("INSERT INTO employees (name, phone, email, base_salary, joined_at, notes, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$name, $phone, $email, floatval($base_salary), $joined_at, $notes]);
        set_flash('success', 'Employee created successfully.');
        header('Location: ?page=employees');
        exit;
    } catch (PDOException $e) {
        set_flash('error', 'Database error: Failed to create employee.');
        header('Location: ?page=employees&action=create');
        exit;
    }
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
    
    // Validation
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $base_salary = $_POST['base_salary'] ?? '';
    $joined_at = $_POST['joined_at'] ?? '';
    $notes = trim($_POST['notes'] ?? '');
    
    if (empty($name) || empty($phone) || empty($base_salary) || empty($joined_at)) {
        set_flash('error', 'Please fill in all required fields.');
        header('Location: ?page=employees&action=edit&id=' . $id);
        exit;
    }
    
    if (!is_numeric($base_salary) || $base_salary < 0) {
        set_flash('error', 'Base salary must be a valid positive number.');
        header('Location: ?page=employees&action=edit&id=' . $id);
        exit;
    }
    
    try {
        $db = get_db();
        $stmt = $db->prepare("UPDATE employees SET name=?, phone=?, email=?, base_salary=?, joined_at=?, notes=? WHERE id=?");
        $stmt->execute([$name, $phone, $email, floatval($base_salary), $joined_at, $notes, $id]);
        set_flash('success', 'Employee updated successfully.');
        header('Location: ?page=employees');
        exit;
    } catch (PDOException $e) {
        set_flash('error', 'Database error: Failed to update employee.');
        header('Location: ?page=employees&action=edit&id=' . $id);
        exit;
    }
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
