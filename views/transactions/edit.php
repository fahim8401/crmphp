<?php
// views/transactions/edit.php
if (!isset($transaction) || !$transaction) {
    echo '<div class="p-8"><h1 class="text-2xl font-bold text-red-600">Transaction not found</h1></div>';
    return;
}
?>
<div class="p-8">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Transaction</h1>
        <form method="POST" action="?page=transactions&action=edit&id=<?= e($transaction['id']) ?>" class="bg-white shadow-lg rounded-lg p-6">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="received" <?= $transaction['type'] === 'received' ? 'selected' : '' ?>>Received</option>
                        <option value="pending" <?= $transaction['type'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="expense" <?= $transaction['type'] === 'expense' ? 'selected' : '' ?>>Expense</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Month <span class="text-red-500">*</span></label>
                    <input type="month" name="month_year" required value="<?= e($transaction['month_year']) ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Employee</label>
                    <select name="employee_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Employee</option>
                        <?php foreach ($employees as $emp): ?>
                            <option value="<?= e($emp['id']) ?>" <?= $transaction['employee_id'] == $emp['id'] ? 'selected' : '' ?>><?= e($emp['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Client</label>
                    <select name="client_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Client</option>
                        <?php foreach ($clients as $client): ?>
                            <option value="<?= e($client['id']) ?>" <?= $transaction['client_id'] == $client['id'] ? 'selected' : '' ?>><?= e($client['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" required step="0.01" min="0" value="<?= e($transaction['amount']) ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="pending" <?= $transaction['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="completed" <?= $transaction['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Settle Date</label>
                    <input type="date" name="settle_date" value="<?= e($transaction['settle_date']) ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?= e($transaction['description']) ?></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <a href="?page=transactions" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Transaction</button>
            </div>
        </form>
    </div>
</div>
