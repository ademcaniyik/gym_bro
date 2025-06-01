<?php
require __DIR__ . '/../vendor/autoload.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}
use App\Helpers\Database;
$user = $_SESSION['user'];
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo 'Geçersiz antrenman planı.';
    exit;
}
$workout_id = (int)$_GET['id'];
$db = Database::getConnection();
// Planı ve hareketleri çek
$stmt = $db->prepare("SELECT day_name FROM workouts WHERE id = :id AND user_id = :user_id");
$stmt->bindParam(':id', $workout_id);
$stmt->bindParam(':user_id', $user['db_id']);
$stmt->execute();
$plan = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$plan) {
    echo 'Plan bulunamadı veya yetkiniz yok.';
    exit;
}
// Hareketleri ve setlerini çek
$stmt2 = $db->prepare("SELECT e.id, e.exercise FROM workout_exercises e WHERE e.workout_id = :workout_id");
$stmt2->bindParam(':workout_id', $workout_id);
$stmt2->execute();
$exercises = $stmt2->fetchAll(PDO::FETCH_ASSOC);
// Her hareketin setlerini çek
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
    <title>Antrenman Planı Detay</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6fa; margin: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px #0001; padding: 32px; }
        h1 { color: #333; text-align: center; }
        .info { color: #2196f3; margin-bottom: 18px; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 24px; }
        th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
        th { background: #f4f6fa; color: #2196f3; }
        tr:hover { background: #f1f8e9; }
        .back { display: inline-block; margin-top: 24px; color: #2196f3; text-decoration: none; font-size: 1em; }
        .back:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Antrenman Planı Detay</h1>
        <div class="info">
            <strong> <?=htmlspecialchars($plan['day_name'])?> <br> </strong>
        </div>
        <table>
            <tr>
                <th>Hareket</th>
                <th>Set</th>
                <th>Tekrar</th>
                <th>Kilo (kg)</th>
            </tr>
            <?php foreach ($exercises as $ex): ?>
                <?php $first = true; $rowspan = count($exerciseSets[$ex['id']]); ?>
                <?php foreach ($exerciseSets[$ex['id']] as $set): ?>
                <tr>
                    <?php if ($first): ?>
                        <td rowspan="<?=$rowspan?>"><?=htmlspecialchars($ex['exercise'])?></td>
                    <?php $first = false; endif; ?>
                    <td><?=($set['set_number']+1)?></td>
                    <td><?=htmlspecialchars($set['rep_count'])?></td>
                    <td><?=htmlspecialchars($set['weight'])?></td>
                </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </table>
        <a class="back" href="workout_list.php">&larr; Antrenman Listesine Dön</a>
    </div>
</body>
</html>
