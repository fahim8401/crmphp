<?php
// views/profile.php
// User profile page
$user = current_user();
?>
<div class="p-8">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">My Profile</h1>
        
        <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
            <div class="flex items-center space-x-4 mb-6">
                <div class="h-20 w-20 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-3xl font-bold">
                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800"><?= e($user['name']) ?></h2>
                    <p class="text-gray-600"><?= e($user['email']) ?></p>
                    <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <?= e(ucfirst($user['role'])) ?>
                    </span>
                </div>
            </div>

            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Profile Information</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">User ID:</span>
                        <span class="font-medium text-gray-800"><?= e($user['id']) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Role:</span>
                        <span class="font-medium text-gray-800"><?= e(ucfirst($user['role'])) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-medium text-gray-800"><?= e($user['email']) ?></span>
                    </div>
                    <?php if ($user['employee_id']): ?>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Employee ID:</span>
                        <span class="font-medium text-gray-800"><?= e($user['employee_id']) ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Account Created:</span>
                        <span class="font-medium text-gray-800"><?= format_date($user['created_at']) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-yellow-800 mb-2">Password Change</h3>
            <p class="text-sm text-yellow-700">To change your password, please contact your administrator.</p>
        </div>
    </div>
</div>
