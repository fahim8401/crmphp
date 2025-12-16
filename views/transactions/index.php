<?php
// views/transactions/index.php
// Transactions listing page
?>
<div class="p-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Transactions</h1>
            <p class="text-gray-600 text-sm mt-1">Month: <?= e($month_year) ?></p>
        </div>
        <div class="flex space-x-3">
            <form method="GET" action="" class="flex items-center space-x-2">
                <input type="hidden" name="page" value="transactions">
                <input type="month" name="month" value="<?= e($month_year) ?>" 
                       class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    Filter
                </button>
            </form>
            <a href="?page=transactions&action=create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                Add Transaction
            </a>
        </div>
    </div>

    <?php if (empty($transactions)): ?>
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
            <p class="text-gray-600 text-lg">No transactions found for this month.</p>
            <a href="?page=transactions&action=create" class="text-blue-600 hover:text-blue-700 font-medium">Add the first transaction</a>
        </div>
    <?php else: ?>
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($transactions as $trans): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= e($trans['id']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 py-1 rounded text-xs font-medium <?= $trans['type'] === 'received' ? 'bg-green-100 text-green-800' : ($trans['type'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                    <?= e(ucfirst($trans['type'])) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= e($trans['employee_name'] ?? 'N/A') ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= e($trans['client_name'] ?? 'N/A') ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= format_currency($trans['amount']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 py-1 rounded text-xs font-medium <?= $trans['status'] === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                    <?= e(ucfirst($trans['status'])) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?= e(substr($trans['description'] ?? '', 0, 30)) ?><?= strlen($trans['description'] ?? '') > 30 ? '...' : '' ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="?page=transactions&action=edit&id=<?= $trans['id'] ?>" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                <a href="?page=transactions&action=delete&id=<?= $trans['id'] ?>" class="text-red-600 hover:text-red-900">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
