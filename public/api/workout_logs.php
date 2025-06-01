<?php
require_once '../../vendor/autoload.php';
require_once 'api_helper.php';
use App\Helpers\Database;
$user = api_require_login();
$db = Database::getConnection();
$stmt = $db->prepare("SELECT l.id, l.day_name, l.log_date, e.exercise, s.set_number, s.rep_count, s.weight FROM workout_logs l
    JOIN workout_log_sets s ON l.id = s.log_id
    JOIN workout_exercises e ON s.exercise_id = e.id
    WHERE l.user_id = :user_id
    ORDER BY l.log_date DESC, l.id DESC, e.exercise ASC, s.set_number ASC");
$stmt->bindParam(':user_id', $user['db_id']);
$stmt->execute();
api_json($stmt->fetchAll(PDO::FETCH_ASSOC));
