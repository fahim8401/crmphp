<?php
// views/month_close.php
// Month closing web interface (admin only)
require_role(['admin']);

$db = get_db();
$message = '';
$error = '';

// Get list of closed months
$closed_months = $db->query("SELECT month_year, closed_at, notes FROM month_closings ORDER BY month_year DESC LIMIT 12")->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['month_to_close'])) {
    $month_to_close = $_POST['month_to_close'];
    
    if (preg_match('/^\d{4}-\d{2}$/', $month_to_close)) {
        // Check if already closed
        $stmt = $db->prepare("SELECT id FROM month_closings WHERE month_year = ?");
        $stmt->execute([$month_to_close]);
        if ($stmt->fetch()) {
            $error = "Month $month_to_close is already closed.";
        } else {
            // Call the close_month script
            $user = current_user();
            $script_path = __DIR__ . '/../close_month.php';
            
            // Check if exec is available
            if (function_exists('exec')) {
                $php_bin = PHP_BINARY;
                $script_arg = escapeshellarg($month_to_close);
                $cmd = "$php_bin " . escapeshellarg($script_path) . " $script_arg";
                $output = [];
                $return_var = 0;
                exec($cmd, $output, $return_var);
                
                if ($return_var === 0) {
                    $message = "Month $month_to_close closed successfully. " . implode(' ', $output);
                } else {
                    $error = "Failed to close month. " . implode(' ', $output);
                }
            } else {
                $error = "exec() function is disabled. Please run close_month.php via CLI.";
            }
        }
    } else {
        $error = "Invalid month format. Use YYYY-MM.";
    }
}

// Get current month suggestions
$current_month = date('Y-m');
$last_month = date('Y-m', strtotime('-1 month'));
?>
<div class="p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Month Close</h1>
        <p class="text-gray-600 text-sm mt-1">Close a month and generate reports</p>
    </div>

    <?php if ($message): ?>
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            <?= e($message) ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <?= e($error) ?>
        </div>
    <?php endif; ?>

    <!-- Close Month Form -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Close a Month</h2>
        <form method="POST" action="" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Select Month to Close <span class="text-red-500">*</span>
                </label>
                <input type="month" name="month_to_close" required value="<?= e($last_month) ?>"
                       class="w-full md:w-1/2 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Usually close previous month: <?= e($last_month) ?></p>
            </div>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-yellow-800 mb-2">What happens when you close a month?</h3>
                <ul class="text-sm text-yellow-700 space-y-1 list-disc list-inside">
                    <li>Salary trackers are created for all employees</li>
                    <li>Pending transactions are moved to the next month</li>
                    <li>A CSV report is generated with salary details</li>
                    <li>Month closing record is created in the database</li>
                </ul>
            </div>
            <div>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                        onclick="return confirm('Are you sure you want to close this month? This action will process all data for the month.')">
                    Close Month
                </button>
            </div>
        </form>
    </div>

    <!-- Closed Months History -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Closed Months History</h2>
        </div>
        <?php if (empty($closed_months)): ?>
            <div class="p-8 text-center text-gray-500">No months have been closed yet.</div>
        <?php else: ?>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Month</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Closed At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($closed_months as $month): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= e($month['month_year']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= format_date($month['closed_at']) ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900"><?= e($month['notes']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="?page=reports&month=<?= e($month['month_year']) ?>" class="text-blue-600 hover:text-blue-900">View Report</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- CLI Instructions -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-800 mb-2">Prefer CLI?</h3>
        <p class="text-sm text-blue-700 mb-2">You can also close months via command line:</p>
        <code class="block bg-blue-900 text-blue-100 p-3 rounded text-sm">
            php <?= e(__DIR__ . '/../close_month.php') ?> YYYY-MM
        </code>
        <p class="text-xs text-blue-600 mt-2">Example: php close_month.php 2025-09</p>
    </div>
</div>
