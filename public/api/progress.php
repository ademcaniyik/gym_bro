<?php
require_once '../../vendor/autoload.php';
require_once 'api_helper.php';
use App\Helpers\Database;
$user = api_require_login();
$db = Database::getConnection();
$exercise = isset($_GET['exercise']) ? $_GET['exercise'] : null;
if (!$exercise) {
    api_json(['error' => 'exercise parametre gerekli']);
    exit;
}
$stmt = $db->prepare("SELECT l.log_date, s.rep_count, s.weight FROM workout_logs l
    JOIN workout_log_sets s ON l.id = s.log_id
    JOIN workout_exercises e ON s.exercise_id = e.id
    WHERE l.user_id = :user_id AND e.exercise = :exercise
    ORDER BY l.log_date ASC, s.set_number ASC");
$stmt->bindParam(':user_id', $user['db_id']);
$stmt->bindParam(':exercise', $exercise);
$stmt->execute();
api_json($stmt->fetchAll(PDO::FETCH_ASSOC));
