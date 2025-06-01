<?php
require __DIR__ . '/../vendor/autoload.php';
use App\Helpers\Database;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}
$user = $_SESSION['user'];
if (!isset($_GET['day']) || empty($_GET['day'])) {
    echo 'Geçersiz gün.';
    exit;
}
$day = $_GET['day'];
$db = Database::getConnection();
// Günün planını ve hareketlerini çek
$stmt = $db->prepare("SELECT id FROM workouts WHERE user_id = :user_id AND day_name = :day_name");
$stmt->bindParam(':user_id', $user['db_id']);
$stmt->bindParam(':day_name', $day);
$stmt->execute();
$workout = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$workout) {
    echo 'Bu güne ait plan bulunamadı.';
    exit;
}
$workout_id = $workout['id'];
$stmt2 = $db->prepare("SELECT id, exercise FROM workout_exercises WHERE workout_id = :workout_id");
$stmt2->bindParam(':workout_id', $workout_id);
$stmt2->execute();
$exercises = $stmt2->fetchAll(PDO::FETCH_ASSOC);
$exerciseSets = [];
foreach ($exercises as $ex) {
    $stmt3 = $db->prepare("SELECT set_number, rep_count, weight FROM workout_sets WHERE exercise_id = :exercise_id ORDER BY set_number ASC");
    $stmt3->bindParam(':exercise_id', $ex['id']);
    $stmt3->execute();
    $exerciseSets[$ex['id']] = $stmt3->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrenman Girişi</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .set-row { display: flex; align-items: center; gap: 8px; margin: 4px 0; justify-content: center; }
        .set-row input { width: 80px; }
        .sets { margin-bottom: 8px; }
    </style>
</head>
<body>
    <div class="plan-container">
        <h1><?=htmlspecialchars($day)?> Antrenmanı</h1>
        <form method="post" action="workout_entry.php?day=<?=urlencode($day)?>">
            <?php foreach ($exercises as $ex): ?>
                <div class="exercise-row">
                    <strong><?=htmlspecialchars($ex['exercise'])?></strong>
                    <div class="sets">
                        <?php foreach ($exerciseSets[$ex['id']] as $set): ?>
                            <div class="set-row">
                                <span><?=$set['set_number']+1?>. set:</span>
                                <input type="number" name="entry[<?=$ex['id']?>][<?=$set['set_number']?>][rep]" min="1" required placeholder="<?=$set['rep_count']?>">
                                <input type="number" name="entry[<?=$ex['id']?>][<?=$set['set_number']?>][weight]" min="0" step="0.1" required placeholder="<?=$set['weight']?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <button type="submit">Kaydet</button>
        </form>
        <a class="back" href="dashboard.php">&larr; Dashboard'a Dön</a>
    </div>
</body>
</html>
