<?php
// services/SalaryService.php
// Handles salary tracker logic and salary calculations for HPLink CRM

require_once __DIR__ . '/../lib/database.php';

class SalaryService
{
    public static function ensureSalaryTracker($employee_id, $month_year)
    {
        $db = get_db();
        $stmt = $db->prepare("SELECT * FROM salary_tracker WHERE employee_id = ? AND month_year = ?");
        $stmt->execute([$employee_id, $month_year]);
        $row = $stmt->fetch();
        if ($row) return $row;

        $emp = $db->prepare("SELECT base_salary FROM employees WHERE id = ?");
        $emp->execute([$employee_id]);
        $empRow = $emp->fetch();
        if (!$empRow) return null;

        $base_salary = $empRow['base_salary'];
        $stmt = $db->prepare("INSERT INTO salary_tracker (employee_id, month_year, base_salary, deducted, final_salary, created_at) VALUES (?, ?, ?, 0, ?, NOW())");
        $stmt->execute([$employee_id, $month_year, $base_salary, $base_salary]);
        return self::getSalaryTracker($employee_id, $month_year);
    }

    public static function getSalaryTracker($employee_id, $month_year)
    {
        $db = get_db();
        $stmt = $db->prepare("SELECT * FROM salary_tracker WHERE employee_id = ? AND month_year = ?");
        $stmt->execute([$employee_id, $month_year]);
        return $stmt->fetch();
    }

    public static function addDeduction($employee_id, $month_year, $amount)
    {
        $db = get_db();
        $tracker = self::ensureSalaryTracker($employee_id, $month_year);
        if (!$tracker) return false;
        $deducted = $tracker['deducted'] + $amount;
        $final_salary = $tracker['base_salary'] - $deducted;
        $stmt = $db->prepare("UPDATE salary_tracker SET deducted = ?, final_salary = ? WHERE id = ?");
        $stmt->execute([$deducted, $final_salary, $tracker['id']]);
        return true;
    }

    public static function getTotals($employee_id, $month_year)
    {
        $db = get_db();
        $received = $db->prepare("SELECT SUM(amount) FROM transactions WHERE employee_id = ? AND month_year = ? AND type = 'received' AND status = 'completed'");
        $received->execute([$employee_id, $month_year]);
        $total_received = $received->fetchColumn() ?: 0;

        $pending = $db->prepare("SELECT SUM(amount) FROM transactions WHERE employee_id = ? AND month_year = ? AND type = 'pending'");
        $pending->execute([$employee_id, $month_year]);
        $total_pending = $pending->fetchColumn() ?: 0;

        $expenses = $db->prepare("SELECT SUM(amount) FROM expenses WHERE employee_id = ? AND EXISTS (SELECT 1 FROM transactions t WHERE t.id = expenses.transaction_id AND t.month_year = ?)");
        $expenses->execute([$employee_id, $month_year]);
        $total_expenses = $expenses->fetchColumn() ?: 0;

        return [
            'total_received' => $total_received,
            'total_pending' => $total_pending,
            'total_expenses' => $total_expenses,
        ];
    }
}
