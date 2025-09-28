<?php
// close_month.php
// Month closing script: CLI or web POST (admin only) for HPLink CRM

require_once __DIR__ . '/lib/database.php';
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/services/SalaryService.php';
require_once __DIR__ . '/services/CsvService.php';

function usage() {
    echo "Usage: php close_month.php YYYY-MM\n";
    exit(1);
}

// CLI or web POST
$is_cli = (php_sapi_name() === 'cli');
if ($is_cli) {
    $month = $argv[1] ?? null;
    if (!$month || !preg_match('/^\d{4}-\d{2}$/', $month)) usage();
    $user_id = 1; // CLI: default to admin user id 1
} else {
    // Web: must be POST, admin only
    start_session();
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !is_logged_in() || ($_SESSION['user_role'] ?? '') !== 'admin') {
        http_response_code(403);
        echo "Forbidden";
        exit;
    }
    $month = $_POST['month'] ?? '';
    if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
        http_response_code(400);
        echo "Invalid month format";
        exit;
    }
    $user_id = $_SESSION['user_id'];
}

// Get all employees
$db = get_db();
$employees = $db->query("SELECT id, name, base_salary FROM employees")->fetchAll();
$summary = [];
foreach ($employees as $emp) {
    // Ensure salary tracker exists
    $tracker = SalaryService::ensureSalaryTracker($emp['id'], $month);

    // Calculate totals
    $totals = SalaryService::getTotals($emp['id'], $month);

    // Prepare summary row
    $summary[] = [
        $emp['id'],
        $emp['name'],
        $tracker['base_salary'],
        $tracker['deducted'],
        $tracker['final_salary'],
        $totals['total_received'],
        $totals['total_pending'],
        $totals['total_expenses'],
    ];

    // Create salary tracker for next month if not exists
    $next_month = date('Y-m', strtotime("$month-01 +1 month"));
    SalaryService::ensureSalaryTracker($emp['id'], $next_month);
}

// Mark/copy pending transactions to next month
$stmt = $db->prepare("UPDATE transactions SET month_year = ? WHERE month_year = ? AND status = 'pending'");
$stmt->execute([$next_month, $month]);

// Create month closing record
$stmt = $db->prepare("INSERT INTO month_closings (month_year, closed_by_user_id, closed_at, notes) VALUES (?, ?, NOW(), ?) ON DUPLICATE KEY UPDATE closed_by_user_id = VALUES(closed_by_user_id), closed_at = VALUES(closed_at), notes = VALUES(notes)");
$notes = "Month closed by user $user_id";
$stmt->execute([$month, $user_id, $notes]);

// Export CSV
$header = ['employee_id', 'name', 'base_salary', 'deducted', 'final_salary', 'total_received', 'total_pending', 'total_expenses'];
$csv_path = __DIR__ . '/storage/exports/closing-' . $month . '.csv';
CsvService::writeCsvFile($csv_path, $header, $summary);

if ($is_cli) {
    echo "Month closed for $month. CSV exported to $csv_path\n";
} else {
    echo "Month closed for $month. CSV exported.";
}
