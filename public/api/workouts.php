<?php
require_once '../../vendor/autoload.php';
require_once 'api_helper.php';
use App\Helpers\Database;
$user = api_require_login();
$db = Database::getConnection();
$stmt = $db->prepare("SELECT id, day_name FROM workouts WHERE user_id = :user_id ORDER BY id DESC");
$stmt->bindParam(':user_id', $user['db_id']);
$stmt->execute();
api_json($stmt->fetchAll(PDO::FETCH_ASSOC));
