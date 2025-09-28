<?php
// views/layout/sidebar.php
$user = current_user();
$role = $user['role'] ?? '';
?>
<aside class="w-64 bg-white shadow h-screen flex flex-col hidden md:block">
  <div class="p-6 border-b flex items-center space-x-3">
    <img src="/assets/logo.png" alt="Logo" class="h-8 w-8">
    <span class="font-bold text-lg text-blue-700 tracking-wide">HPLink CRM</span>
  </div>
  <nav class="flex-1 p-4">
    <div class="mb-4 text-xs text-gray-400 uppercase tracking-wider">Main</div>
    <ul class="space-y-1">
      <li>
        <a href="?page=dashboard" class="flex items-center px-3 py-2 rounded hover:bg-blue-50 transition">
          <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"></path></svg>
          Dashboard
        </a>
      </li>
      <?php if (in_array($role, ['admin', 'hr'])): ?>
        <li>
          <a href="?page=employees" class="flex items-center px-3 py-2 rounded hover:bg-blue-50 transition">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 3.13a4 4 0 010 7.75M8 3.13a4 4 0 000 7.75"></path></svg>
            Employees
          </a>
        </li>
        <li>
          <a href="?page=clients" class="flex items-center px-3 py-2 rounded hover:bg-blue-50 transition">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 01-8 0 4 4 0 018 0zM12 14v7m0 0H5a2 2 0 01-2-2v-5a2 2 0 012-2h14a2 2 0 012 2v5a2 2 0 01-2 2h-7z"></path></svg>
            Clients
          </a>
        </li>
        <li>
          <a href="?page=transactions" class="flex items-center px-3 py-2 rounded hover:bg-blue-50 transition">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 9V7a5 5 0 00-10 0v2M5 13h14l-1.68 7.39A2 2 0 0115.36 22H8.64a2 2 0 01-1.96-1.61L5 13z"></path></svg>
            Transactions
          </a>
        </li>
        <li>
          <a href="?page=expenses" class="flex items-center px-3 py-2 rounded hover:bg-blue-50 transition">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 14l2-2m0 0l2-2m-2 2v6m0-6V4m0 6a4 4 0 100 8 4 4 0 000-8z"></path></svg>
            Expenses
          </a>
        </li>
        <li>
          <a href="?page=reports" class="flex items-center px-3 py-2 rounded hover:bg-blue-50 transition">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-6 4h6a2 2 0 002-2v-5a2 2 0 00-2-2h-6a2 2 0 00-2 2v5a2 2 0 002 2z"></path></svg>
            Reports
          </a>
        </li>
        <li>
          <a href="?page=month_close" class="flex items-center px-3 py-2 rounded hover:bg-blue-50 transition">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Month Close
          </a>
        </li>
      <?php endif; ?>
      <?php if ($role === 'admin'): ?>
        <li>
          <a href="?page=users" class="flex items-center px-3 py-2 rounded hover:bg-blue-50 transition">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 3.13a4 4 0 010 7.75M8 3.13a4 4 0 000 7.75"></path></svg>
            User Management
          </a>
        </li>
        <li>
          <a href="?page=activity_logs" class="flex items-center px-3 py-2 rounded hover:bg-blue-50 transition">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 7v4a1 1 0 001 1h3m10-5v4a1 1 0 001 1h3m-7 4v4m0 0H5a2 2 0 01-2-2v-5a2 2 0 012-2h14a2 2 0 012 2v5a2 2 0 01-2 2h-7z"></path></svg>
            Activity Logs
          </a>
        </li>
      <?php endif; ?>
      <?php if ($role === 'employee'): ?>
        <li>
          <a href="?page=employee_view&id=<?=e($user['employee_id'])?>" class="flex items-center px-3 py-2 rounded hover:bg-blue-50 transition">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5.121 17.804A13.937 13.937 0 0112 15c2.485 0 4.847.657 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            My Profile
          </a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>
  <div class="p-4 border-t text-sm flex items-center space-x-2">
    <span class="inline-block h-8 w-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-lg">
      <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
    </span>
    <span><?=e($user['name'])?> (<?=e($role)?>)</span>
    <a href="?page=logout" class="ml-auto text-blue-600 hover:underline">Logout</a>
  </div>
</aside>
