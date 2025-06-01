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
if (!isset($data['day']) || !isset($data['exercises']) || !is_array($data['exercises'])) {
    http_response_code(400);
    api_json(['error' => 'Eksik veri']);
    exit;
}
$db = Database::getConnection();
$day = trim($data['day']);
// Gün var mı kontrol et, yoksa ekle
$stmt = $db->prepare("SELECT id FROM workouts WHERE user_id = :user_id AND day_name = :day_name");
$stmt->bindParam(':user_id', $user['db_id']);
$stmt->bindParam(':day_name', $day);
$stmt->execute();
$workout = $stmt->fetch(PDO::FETCH_ASSOC);
if ($workout) {
    $workout_id = $workout['id'];
} else {
    $stmt2 = $db->prepare("INSERT INTO workouts (user_id, day_name, created_at) VALUES (:user_id, :day_name, NOW())");
    $stmt2->bindParam(':user_id', $user['db_id']);
    $stmt2->bindParam(':day_name', $day);
    $stmt2->execute();
    $workout_id = $db->lastInsertId();
}
foreach ($data['exercises'] as $ex) {
    $exName = trim($ex['name']);
    $stmt3 = $db->prepare("INSERT INTO workout_exercises (workout_id, exercise) VALUES (:workout_id, :exercise)");
    $stmt3->bindParam(':workout_id', $workout_id);
    $stmt3->bindParam(':exercise', $exName);
    $stmt3->execute();
    $exercise_id = $db->lastInsertId();
    if (isset($ex['sets']) && is_array($ex['sets'])) {
        foreach ($ex['sets'] as $setIndex => $set) {
            $rep = isset($set['rep']) ? (int)$set['rep'] : 0;
            $weight = isset($set['weight']) ? (float)$set['weight'] : 0;
            if ($rep > 0) {
                $stmt4 = $db->prepare("INSERT INTO workout_sets (exercise_id, set_number, rep_count, weight) VALUES (:exercise_id, :set_number, :rep_count, :weight)");
                $stmt4->bindParam(':exercise_id', $exercise_id);
                $stmt4->bindParam(':set_number', $setIndex);
                $stmt4->bindParam(':rep_count', $rep);
                $stmt4->bindParam(':weight', $weight);
                $stmt4->execute();
            }
        }
    }
}
api_json(['success' => true, 'workout_id' => $workout_id]);
