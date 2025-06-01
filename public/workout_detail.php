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
$stmt = $db->prepare("SELECT day_name, created_at FROM workouts WHERE id = :id AND user_id = :user_id");
$stmt->bindParam(':id', $workout_id);
$stmt->bindParam(':user_id', $user['db_id']);
$stmt->execute();
$plan = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$plan) {
    echo 'Plan bulunamadı veya yetkiniz yok.';
    exit;
}
$stmt2 = $db->prepare("SELECT exercise, set_count, rep_count, weight FROM workout_exercises WHERE workout_id = :workout_id");
$stmt2->bindParam(':workout_id', $workout_id);
$stmt2->execute();
$exercises = $stmt2->fetchAll(PDO::FETCH_ASSOC);
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
            <strong>Gün:</strong> <?=htmlspecialchars($plan['day_name'])?> <br>
            <strong>Oluşturulma:</strong> <?=htmlspecialchars($plan['created_at'])?>
        </div>
        <table>
            <tr>
                <th>Hareket</th>
                <th>Set</th>
                <th>Tekrar</th>
                <th>Kilo (kg)</th>
            </tr>
            <?php foreach ($exercises as $ex): ?>
            <tr>
                <td><?=htmlspecialchars($ex['exercise'])?></td>
                <td><?=htmlspecialchars($ex['set_count'])?></td>
                <td><?=htmlspecialchars($ex['rep_count'])?></td>
                <td><?=htmlspecialchars($ex['weight'])?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <a class="back" href="workout_list.php">&larr; Antrenman Listesine Dön</a>
    </div>
</body>
</html>
