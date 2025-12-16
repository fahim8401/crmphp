<?php
// views/employees/edit.php
// Edit employee form
if (!isset($employee) || !$employee) {
    echo '<div class="p-8"><h1 class="text-2xl font-bold text-red-600">Employee not found</h1></div>';
    return;
}
?>
<div class="p-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Employee</h1>
            <p class="text-gray-600 mt-1">Update employee information</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="?page=employees&action=edit&id=<?= e($employee['id']) ?>" class="bg-white shadow-lg rounded-lg p-6">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" required value="<?= e($employee['name']) ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Phone <span class="text-red-500">*</span>
                </label>
                <input type="text" name="phone" required value="<?= e($employee['phone']) ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Email
                </label>
                <input type="email" name="email" value="<?= e($employee['email']) ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Base Salary <span class="text-red-500">*</span>
                </label>
                <input type="number" name="base_salary" required step="0.01" min="0" value="<?= e($employee['base_salary']) ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Joined Date <span class="text-red-500">*</span>
                </label>
                <input type="date" name="joined_at" required value="<?= e($employee['joined_at']) ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Notes
                </label>
                <textarea name="notes" rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?= e($employee['notes']) ?></textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="?page=employees"
                   class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Update Employee
                </button>
            </div>
        </form>
    </div>
</div>
