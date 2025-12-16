<?php
// controllers/UsersController.php
// Handles user CRUD for HPLink CRM (admin only)

require_once __DIR__ . '/../lib/database.php';
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/role_checks.php';
require_once __DIR__ . '/../lib/auth.php';

// List users (admin only)
function users_index_page() {
    require_role(['admin']);
    $db = get_db();
    $stmt = $db->query("SELECT u.*, e.name AS employee_name FROM users u LEFT JOIN employees e ON u.employee_id = e.id ORDER BY u.created_at DESC");
    $users = $stmt->fetchAll();
    include __DIR__ . '/../views/users/index.php';
}

// Create user (admin only)
function users_create_page($error = null) {
    require_role(['admin']);
    $db = get_db();
    $employees = $db->query("SELECT id, name FROM employees ORDER BY name")->fetchAll();
    include __DIR__ . '/../views/users/create.php';
}

function users_create_handler() {
    require_role(['admin']);
    $db = get_db();
    
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];
    $employee_id = !empty($_POST['employee_id']) ? $_POST['employee_id'] : null;
    
    // Check if email already exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        set_flash('error', 'Email already exists.');
        header('Location: ?page=users&action=create');
        exit;
    }
    
    $password_hash = password_hash_safe($password);
    
    $stmt = $db->prepare("INSERT INTO users (name, email, password_hash, role, employee_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$name, $email, $password_hash, $role, $employee_id]);
    
    set_flash('success', 'User created successfully.');
    header('Location: ?page=users');
    exit;
}

// Edit user (admin only)
function users_edit_page($id, $error = null) {
    require_role(['admin']);
    $db = get_db();
    
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    
    if (!$user) {
        set_flash('error', 'User not found.');
        header('Location: ?page=users');
        exit;
    }
    
    $employees = $db->query("SELECT id, name FROM employees ORDER BY name")->fetchAll();
    include __DIR__ . '/../views/users/edit.php';
}

function users_edit_handler($id) {
    require_role(['admin']);
    $db = get_db();
    
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $employee_id = !empty($_POST['employee_id']) ? $_POST['employee_id'] : null;
    
    // Check if email already exists for another user
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$email, $id]);
    if ($stmt->fetch()) {
        set_flash('error', 'Email already exists.');
        header('Location: ?page=users&action=edit&id=' . $id);
        exit;
    }
    
    // If password provided, update it
    if (!empty($_POST['password'])) {
        $password_hash = password_hash_safe($_POST['password']);
        $stmt = $db->prepare("UPDATE users SET name=?, email=?, password_hash=?, role=?, employee_id=? WHERE id=?");
        $stmt->execute([$name, $email, $password_hash, $role, $employee_id, $id]);
    } else {
        $stmt = $db->prepare("UPDATE users SET name=?, email=?, role=?, employee_id=? WHERE id=?");
        $stmt->execute([$name, $email, $role, $employee_id, $id]);
    }
    
    set_flash('success', 'User updated successfully.');
    header('Location: ?page=users');
    exit;
}

// Delete user (admin only)
function users_delete_handler($id) {
    require_role(['admin']);
    $db = get_db();
    
    // Prevent deleting yourself
    $current_user = current_user();
    if ($current_user['id'] == $id) {
        set_flash('error', 'You cannot delete your own account.');
        header('Location: ?page=users');
        exit;
    }
    
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    
    set_flash('success', 'User deleted successfully.');
    header('Location: ?page=users');
    exit;
}
