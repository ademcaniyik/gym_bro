<?php
require_once '../../vendor/autoload.php';
require_once 'api_helper.php';
use App\Helpers\Database;
$user = api_require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    api_json(['error' => 'POST required']);
    exit;
}
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['workout_id']) || !isset($data['entries']) || !is_array($data['entries'])) {
    http_response_code(400);
    api_json(['error' => 'Eksik veri']);
    exit;
}
$db = Database::getConnection();
$workout_id = (int)$data['workout_id'];
$day_name = isset($data['day_name']) ? $data['day_name'] : '';
$stmtLog = $db->prepare("INSERT INTO workout_logs (user_id, workout_id, day_name, log_date) VALUES (:user_id, :workout_id, :day_name, NOW())");
$stmtLog->bindParam(':user_id', $user['db_id']);
$stmtLog->bindParam(':workout_id', $workout_id);
$stmtLog->bindParam(':day_name', $day_name);
$stmtLog->execute();
$log_id = $db->lastInsertId();
foreach ($data['entries'] as $exercise_id => $sets) {
    foreach ($sets as $set_number => $values) {
        $rep = isset($values['rep']) ? (int)$values['rep'] : null;
        $weight = isset($values['weight']) ? (float)$values['weight'] : null;
        $stmtSet = $db->prepare("INSERT INTO workout_log_sets (log_id, exercise_id, set_number, rep_count, weight) VALUES (:log_id, :exercise_id, :set_number, :rep_count, :weight)");
        $stmtSet->bindParam(':log_id', $log_id);
        $stmtSet->bindParam(':exercise_id', $exercise_id);
        $stmtSet->bindParam(':set_number', $set_number);
        $stmtSet->bindParam(':rep_count', $rep);
        $stmtSet->bindParam(':weight', $weight);
        $stmtSet->execute();
    }
}
api_json(['success' => true, 'log_id' => $log_id]);
