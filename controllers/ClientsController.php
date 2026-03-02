<?php
// controllers/ClientsController.php
// Handles client CRUD for HPLink CRM

require_once __DIR__ . '/../lib/database.php';
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/role_checks.php';

// List clients (admin, hr)
function clients_index_page() {
    require_role(['admin', 'hr']);
    $db = get_db();
    $stmt = $db->query("SELECT * FROM clients ORDER BY created_at DESC");
    $clients = $stmt->fetchAll();
    include __DIR__ . '/../views/clients/index.php';
}

// Create client (admin, hr)
function clients_create_page($error = null) {
    require_role(['admin', 'hr']);
    include __DIR__ . '/../views/clients/create.php';
}

function clients_create_handler() {
    require_role(['admin', 'hr']);
    $db = get_db();
    $stmt = $db->prepare("INSERT INTO clients (name, phone, notes, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([
        trim($_POST['name']),
        trim($_POST['phone']),
        trim($_POST['notes'] ?? '')
    ]);
    set_flash('success', 'Client created successfully.');
    header('Location: ?page=clients');
    exit;
}

// Edit client (admin, hr)
function clients_edit_page($id, $error = null) {
    require_role(['admin', 'hr']);
    $db = get_db();
    $stmt = $db->prepare("SELECT * FROM clients WHERE id = ?");
    $stmt->execute([$id]);
    $client = $stmt->fetch();
    if (!$client) {
        set_flash('error', 'Client not found.');
        header('Location: ?page=clients');
        exit;
    }
    include __DIR__ . '/../views/clients/edit.php';
}

function clients_edit_handler($id) {
    require_role(['admin', 'hr']);
    $db = get_db();
    $stmt = $db->prepare("UPDATE clients SET name=?, phone=?, notes=? WHERE id=?");
    $stmt->execute([
        trim($_POST['name']),
        trim($_POST['phone']),
        trim($_POST['notes'] ?? ''),
        $id
    ]);
    set_flash('success', 'Client updated successfully.');
    header('Location: ?page=clients');
    exit;
}

// Delete client (admin only)
function clients_delete_handler($id) {
    require_role(['admin']);
    $db = get_db();
    $stmt = $db->prepare("DELETE FROM clients WHERE id = ?");
    $stmt->execute([$id]);
    set_flash('success', 'Client deleted successfully.');
    header('Location: ?page=clients');
    exit;
}
