<?php
// views/clients/edit.php
// Edit client form
if (!isset($client) || !$client) {
    echo '<div class="p-8"><h1 class="text-2xl font-bold text-red-600">Client not found</h1></div>';
    return;
}
?>
<div class="p-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Client</h1>
            <p class="text-gray-600 mt-1">Update client information</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="?page=clients&action=edit&id=<?= e($client['id']) ?>" class="bg-white shadow-lg rounded-lg p-6">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" required value="<?= e($client['name']) ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Phone <span class="text-red-500">*</span>
                </label>
                <input type="text" name="phone" required value="<?= e($client['phone']) ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Notes
                </label>
                <textarea name="notes" rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"><?= e($client['notes']) ?></textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="?page=clients"
                   class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Update Client
                </button>
            </div>
        </form>
    </div>
</div>
