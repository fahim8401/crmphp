<?php
// views/activity_logs.php
// Activity logs viewer (admin only)
require_role(['admin']);

$db = get_db();

// Filters
$user_filter = $_GET['user_id'] ?? '';
$model_filter = $_GET['model'] ?? '';
$page = max(1, (int)($_GET['pg'] ?? 1));
$per_page = 50;
$offset = ($page - 1) * $per_page;

// Build query
$where = [];
$params = [];

if ($user_filter) {
    $where[] = "al.user_id = ?";
    $params[] = $user_filter;
}

if ($model_filter) {
    $where[] = "al.model_name = ?";
    $params[] = $model_filter;
}

$where_sql = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

// Get total count
$count_sql = "SELECT COUNT(*) FROM activity_logs al $where_sql";
$stmt = $db->prepare($count_sql);
$stmt->execute($params);
$total = $stmt->fetchColumn();

// Validate pagination parameters
$per_page = 50;
$offset = max(0, ($page - 1) * $per_page);

// Get logs
$sql = "
    SELECT al.*, u.name AS user_name
    FROM activity_logs al
    LEFT JOIN users u ON al.user_id = u.id
    $where_sql
    ORDER BY al.created_at DESC
    LIMIT ? OFFSET ?
";
$stmt = $db->prepare($sql);
$stmt->execute(array_merge($params, [$per_page, $offset]));
$logs = $stmt->fetchAll();

// Get users for filter
$users = $db->query("SELECT id, name FROM users ORDER BY name")->fetchAll();

// Calculate pagination
$total_pages = ceil($total / $per_page);
?>
<div class="p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Activity Logs</h1>
        <p class="text-gray-600 text-sm mt-1">Track all user actions in the system</p>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
        <form method="GET" action="" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="hidden" name="page" value="activity_logs">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                <select name="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Users</option>
                    <?php foreach ($users as $u): ?>
                        <option value="<?= e($u['id']) ?>" <?= $user_filter == $u['id'] ? 'selected' : '' ?>><?= e($u['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                <select name="model" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Models</option>
                    <option value="transaction" <?= $model_filter === 'transaction' ? 'selected' : '' ?>>Transaction</option>
                    <option value="employee" <?= $model_filter === 'employee' ? 'selected' : '' ?>>Employee</option>
                    <option value="client" <?= $model_filter === 'client' ? 'selected' : '' ?>>Client</option>
                    <option value="user" <?= $model_filter === 'user' ? 'selected' : '' ?>>User</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Filter</button>
            </div>
        </form>
    </div>

    <!-- Results -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <p class="text-sm text-gray-600">Showing <?= count($logs) ?> of <?= $total ?> logs</p>
        </div>
        <?php if (empty($logs)): ?>
            <div class="p-8 text-center text-gray-500">No activity logs found.</div>
        <?php else: ?>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Model</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Model ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($logs as $log): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= e($log['id']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= e($log['user_name']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-medium <?= $log['action'] === 'create' ? 'bg-green-100 text-green-800' : ($log['action'] === 'delete' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') ?>">
                                    <?= e(ucfirst($log['action'])) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= e(ucfirst($log['model_name'])) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= e($log['model_id']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= format_date($log['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <div class="flex justify-center space-x-2">
            <?php for ($i = 1; $i <= min($total_pages, 10); $i++): ?>
                <a href="?page=activity_logs&user_id=<?= e($user_filter) ?>&model=<?= e($model_filter) ?>&pg=<?= $i ?>" 
                   class="px-4 py-2 <?= $i === $page ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' ?> border rounded-md">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>
