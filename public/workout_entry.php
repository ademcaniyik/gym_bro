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
// Son yapılan antrenmanlardan, her hareket için en son logu çek
$lastLogs = [];
foreach ($exercises as $ex) {
    $stmtLast = $db->prepare("SELECT s.set_number, s.rep_count, s.weight FROM workout_logs l
        JOIN workout_log_sets s ON l.id = s.log_id
        WHERE l.user_id = :user_id AND l.workout_id = :workout_id AND s.exercise_id = :exercise_id
        ORDER BY l.log_date DESC, l.id DESC, s.set_number ASC");
    $stmtLast->bindParam(':user_id', $user['db_id']);
    $stmtLast->bindParam(':workout_id', $workout_id);
    $stmtLast->bindParam(':exercise_id', $ex['id']);
    $stmtLast->execute();
    $lastLogs[$ex['id']] = $stmtLast->fetchAll(PDO::FETCH_ASSOC);
}
// --- YAPILAN ANTRENMANI KAYDETME ---
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['entry']) && is_array($_POST['entry']) && isset($_POST['save_ex'])
) {
    $exercise_id = (int)$_POST['save_ex'];
    // Yeni log oluştur
    $stmtLog = $db->prepare("INSERT INTO workout_logs (user_id, workout_id, day_name, log_date) VALUES (:user_id, :workout_id, :day_name, NOW())");
    $stmtLog->bindParam(':user_id', $user['db_id']);
    $stmtLog->bindParam(':workout_id', $workout_id);
    $stmtLog->bindParam(':day_name', $day);
    $stmtLog->execute();
    $log_id = $db->lastInsertId();
    // Sadece ilgili hareketin setlerini kaydet
    if (isset($_POST['entry'][$exercise_id])) {
        foreach ($_POST['entry'][$exercise_id] as $set_number => $values) {
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
    $successMsg = 'Hareket başarıyla kaydedildi!';
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrenman Girişi</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="plan-container">
        <h1><?=htmlspecialchars($day)?> Antrenmanı</h1>
        <?php if (!empty($successMsg)) echo '<div class="success">'.$successMsg.'</div>'; ?>
        <form method="post" action="workout_entry.php?day=<?=urlencode($day)?>">
            <?php foreach ($exercises as $ex): ?>
                <div class="exercise-row exercise-box">
                    <strong class="exercise-title"><?=htmlspecialchars($ex['exercise'])?></strong>
                    <div class="sets">
                        <?php foreach ($exerciseSets[$ex['id']] as $set):
                            $last = isset($lastLogs[$ex['id']][$set['set_number']]) ? $lastLogs[$ex['id']][$set['set_number']] : null;
                        ?>
                            <form method="post" action="workout_entry.php?day=<?=urlencode($day)?>#ex<?=$ex['id']?>" class="set-row">
                                <span class="set-label"><?=$set['set_number']+1?>. set:</span>
                                <input type="number" name="entry[<?=$ex['id']?>][<?=$set['set_number']?>][rep]" min="1" required placeholder="<?=(isset($last['rep_count']) ? 'Son: '.$last['rep_count'] : $set['rep_count'])?>">
                                <input type="number" name="entry[<?=$ex['id']?>][<?=$set['set_number']?>][weight]" min="0" step="0.1" required placeholder="<?=(isset($last['weight']) ? 'Son: '.$last['weight'] : $set['weight'])?>">
                                <button type="submit" name="save_ex" value="<?=$ex['id']?>" class="primary-btn">Kaydet</button>
                            </form>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </form>
        <a class="back" href="dashboard.php">&larr; Dashboard'a Dön</a>
    </div>
</body>
</html>
