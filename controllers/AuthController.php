<?php
// controllers/AuthController.php
// Handles login, logout, and session for HPLink CRM

require_once __DIR__ . '/../lib/database.php';
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/helpers.php';

function auth_login_page($error = null) {
    include __DIR__ . '/../views/layout/header.php';
    // No role check needed for login page
    ?>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
      <form method="post" action="?page=login" class="bg-white p-8 rounded shadow-md w-full max-w-sm">
        <h2 class="text-2xl font-bold mb-6 text-center">HPLink CRM Login</h2>
        <?php if ($error): ?>
          <div class="mb-4 text-red-600"><?=e($error)?></div>
        <?php endif; ?>
        <div class="mb-4">
          <label class="block mb-1 font-semibold">Email</label>
          <input type="email" name="email" required class="w-full border px-3 py-2 rounded" autofocus>
        </div>
        <div class="mb-6">
          <label class="block mb-1 font-semibold">Password</label>
          <input type="password" name="password" required class="w-full border px-3 py-2 rounded">
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Login</button>
      </form>
    </div>
    <?php
    include __DIR__ . '/../views/layout/footer.php';
}

function auth_login_handler() {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $db = get_db();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify_safe($password, $user['password_hash'])) {
        login($user);
        header('Location: ?page=dashboard');
        exit;
    } else {
        auth_login_page('Invalid email or password.');
    }
}

function auth_logout_handler() {
    // All roles can logout
    logout();
    header('Location: ?page=login');
    exit;
}
