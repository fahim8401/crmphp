<?php
// views/dashboard.php
// Dashboard with statistics
$user = current_user();
$current_month = date('F Y');
?>
<div class="p-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
        <p class="text-gray-600 mt-1">Welcome back, <?= e($user['name']) ?>! Here's what's happening.</p>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Employees Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Total Employees</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?= e($stats['total_employees']) ?></p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 3.13a4 4 0 010 7.75M8 3.13a4 4 0 000 7.75"></path>
                    </svg>
                </div>
            </div>
            <a href="?page=employees" class="text-sm text-blue-600 hover:text-blue-700 mt-4 inline-block">View All &rarr;</a>
        </div>

        <!-- Clients Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Total Clients</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?= e($stats['total_clients']) ?></p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M16 7a4 4 0 01-8 0 4 4 0 018 0zM12 14v7m0 0H5a2 2 0 01-2-2v-5a2 2 0 012-2h14a2 2 0 012 2v5a2 2 0 01-2 2h-7z"></path>
                    </svg>
                </div>
            </div>
            <a href="?page=clients" class="text-sm text-green-600 hover:text-green-700 mt-4 inline-block">View All &rarr;</a>
        </div>

        <!-- Monthly Received Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Received (<?= $current_month ?>)</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?= format_currency($stats['monthly_received_amount']) ?></p>
                    <p class="text-xs text-gray-500 mt-1"><?= e($stats['monthly_received_count']) ?> transactions</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <a href="?page=transactions" class="text-sm text-purple-600 hover:text-purple-700 mt-4 inline-block">View Transactions &rarr;</a>
        </div>

        <!-- Monthly Pending Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Pending (<?= $current_month ?>)</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?= format_currency($stats['monthly_pending_amount']) ?></p>
                    <p class="text-xs text-gray-500 mt-1"><?= e($stats['monthly_pending_count']) ?> transactions</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <a href="?page=transactions" class="text-sm text-yellow-600 hover:text-yellow-700 mt-4 inline-block">View Pending &rarr;</a>
        </div>
    </div>

    <!-- Quick Actions -->
    <?php if (in_array($user['role'], ['admin', 'hr'])): ?>
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="?page=employees&action=create" class="flex items-center justify-center px-4 py-3 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="text-sm font-medium text-blue-700">Add Employee</span>
            </a>
            <a href="?page=clients&action=create" class="flex items-center justify-center px-4 py-3 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition">
                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="text-sm font-medium text-green-700">Add Client</span>
            </a>
            <a href="?page=transactions&action=create" class="flex items-center justify-center px-4 py-3 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition">
                <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="text-sm font-medium text-purple-700">Add Transaction</span>
            </a>
            <a href="?page=reports" class="flex items-center justify-center px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition">
                <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-6 4h6a2 2 0 002-2v-5a2 2 0 00-2-2h-6a2 2 0 00-2 2v5a2 2 0 002 2z"></path>
                </svg>
                <span class="text-sm font-medium text-gray-700">View Reports</span>
            </a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Recent Activity or Info -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">System Information</h2>
        <div class="space-y-2 text-sm text-gray-600">
            <p><strong>Current Month:</strong> <?= $current_month ?></p>
            <p><strong>User Role:</strong> <?= e(ucfirst($user['role'])) ?></p>
            <p><strong>Timezone:</strong> <?= e(get_config()['timezone']) ?></p>
            <p><strong>Currency:</strong> <?= e(get_config()['currency_symbol']) ?></p>
        </div>
    </div>
</div>
