<?php
// views/layout/header.php
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CRM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" href="/assets/favicon.ico">
</head>
<body class="bg-gray-100 min-h-screen">
  <header class="bg-white shadow px-6 py-3 flex items-center justify-between">
    <div class="flex items-center space-x-3">
      <img src="/assets/logo.png" alt="HPLink CRM Logo" class="h-10 w-10">
      <span class="font-bold text-2xl text-blue-700 tracking-wide">HPLink CRM</span>
    </div>
    <div class="flex items-center space-x-4">
      <button class="relative focus:outline-none group">
        <svg class="w-6 h-6 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
        <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-500 rounded-full"></span>
      </button>
      <?php if (is_logged_in()): 
        $user = current_user(); ?>
        <div class="relative group">
          <button class="flex items-center space-x-2 focus:outline-none">
            <span class="inline-block h-8 w-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-lg">
              <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
            </span>
            <span class="font-semibold text-gray-700"><?= e($user['name'] ?? 'User') ?></span>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
          </button>
          <div class="absolute right-0 mt-2 w-40 bg-white border rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity z-20">
            <div class="px-4 py-2 text-sm text-gray-700 border-b"><?= e($user['role'] ?? '') ?></div>
            <a href="?page=profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
            <a href="?page=logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</a>
          </div>
        </div>
      <?php else: ?>
        <a href="?page=login" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Login</a>
      <?php endif; ?>
    </div>
  </header>
  <div class="flex">
    <?php if (is_logged_in()): ?>
      <?php include __DIR__ . '/sidebar.php'; ?>
    <?php endif; ?>
    <main class="flex-1">
<?php
// Flash messages
$flashes = get_flash();
foreach ($flashes as $type => $msgs) {
    foreach ($msgs as $msg) {
        $color = $type === 'error' ? 'red' : ($type === 'success' ? 'green' : 'blue');
        echo '<div class="mx-4 my-2 px-4 py-2 rounded bg-'.$color.'-100 text-'.$color.'-800">'.$msg.'</div>';
    }
}
?>
