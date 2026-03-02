<?php
// views/reports.php
// Reports page with month-wise summary
require_role(['admin', 'hr']);

$db = get_db();
$selected_month = $_GET['month'] ?? date('Y-m');

// Get transactions summary
$stmt = $db->prepare("
    SELECT 
        type,
        status,
        COUNT(*) as count,
        SUM(amount) as total
    FROM transactions
    WHERE month_year = ?
    GROUP BY type, status
");
$stmt->execute([$selected_month]);
$summary = $stmt->fetchAll();

// Calculate totals
$totals = [
    'received' => 0,
    'pending' => 0,
    'expense' => 0
];

foreach ($summary as $row) {
    if ($row['type'] === 'received' && $row['status'] === 'completed') {
        $totals['received'] = $row['total'];
    } elseif ($row['type'] === 'pending') {
        $totals['pending'] = $row['total'];
    } elseif ($row['type'] === 'expense') {
        $totals['expense'] = $row['total'];
    }
}

$net_income = $totals['received'] - $totals['expense'];
?>
<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Reports</h1>
            <p class="text-gray-600 text-sm mt-1">Financial summary for <?= e($selected_month) ?></p>
        </div>
        <form method="GET" action="" class="flex items-center space-x-2">
            <input type="hidden" name="page" value="reports">
            <input type="month" name="month" value="<?= e($selected_month) ?>" 
                   class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Filter
            </button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <p class="text-sm text-gray-600 font-medium">Total Received</p>
            <p class="text-3xl font-bold text-green-600 mt-2"><?= format_currency($totals['received']) ?></p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500">
            <p class="text-sm text-gray-600 font-medium">Total Pending</p>
            <p class="text-3xl font-bold text-yellow-600 mt-2"><?= format_currency($totals['pending']) ?></p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-red-500">
            <p class="text-sm text-gray-600 font-medium">Total Expenses</p>
            <p class="text-3xl font-bold text-red-600 mt-2"><?= format_currency($totals['expense']) ?></p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <p class="text-sm text-gray-600 font-medium">Net Income</p>
            <p class="text-3xl font-bold text-blue-600 mt-2"><?= format_currency($net_income) ?></p>
        </div>
    </div>

    <!-- Detailed Summary Table -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Transaction Summary</h2>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Count</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Amount</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($summary)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No data available for this month</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($summary as $row): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= e(ucfirst($row['type'])) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= e(ucfirst($row['status'])) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= e($row['count']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= format_currency($row['total']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="flex space-x-4">
        <a href="?page=transactions&month=<?= e($selected_month) ?>" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            View Transactions
        </a>
        <a href="?page=month_close" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
            Month Close
        </a>
    </div>
</div>
