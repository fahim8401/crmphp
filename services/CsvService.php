<?php
// services/CsvService.php
// Handles CSV export for reports and month closing for HPLink CRM

class CsvService
{
    public static function streamCsv($filename, $header, $rows)
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $out = fopen('php://output', 'w');
        fputcsv($out, $header);
        foreach ($rows as $row) {
            fputcsv($out, $row);
        }
        fclose($out);
        exit;
    }

    public static function writeCsvFile($filepath, $header, $rows)
    {
        $out = fopen($filepath, 'w');
        if (!$out) return false;
        fputcsv($out, $header);
        foreach ($rows as $row) {
            fputcsv($out, $row);
        }
        fclose($out);
        return true;
    }
}
