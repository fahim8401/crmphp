<?php
// services/ActivityLogService.php
// Handles activity logging for create/update/delete actions for HPLink CRM

require_once __DIR__ . '/../lib/database.php';

class ActivityLogService
{
    public static function log($user_id, $model, $model_id, $action, $changes = null)
    {
        $db = get_db();
        $stmt = $db->prepare("INSERT INTO activity_logs (user_id, model_name, model_id, action, changes, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([
            $user_id,
            $model,
            $model_id,
            $action,
            $changes ? json_encode($changes, JSON_UNESCAPED_UNICODE) : null
        ]);
    }

    public static function fetch($filters = [])
    {
        $db = get_db();
        $sql = "SELECT l.*, u.name AS user_name FROM activity_logs l LEFT JOIN users u ON l.user_id = u.id WHERE 1=1";
        $params = [];
        if (!empty($filters['user_id'])) {
            $sql .= " AND l.user_id = ?";
            $params[] = $filters['user_id'];
        }
        if (!empty($filters['model_name'])) {
            $sql .= " AND l.model_name = ?";
            $params[] = $filters['model_name'];
        }
        if (!empty($filters['month_year'])) {
            $sql .= " AND DATE_FORMAT(l.created_at, '%Y-%m') = ?";
            $params[] = $filters['month_year'];
        }
        $sql .= " ORDER BY l.created_at DESC LIMIT 100";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
