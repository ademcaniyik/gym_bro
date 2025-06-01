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
$db = Database::getConnection();
$plans = $db->prepare("SELECT w.id, w.day_name FROM workouts w WHERE w.user_id = :user_id ORDER BY w.id DESC");
$plans->bindParam(':user_id', $user['db_id']);
$plans->execute();
$workouts = $plans->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrenman Planlarım</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6fa; margin: 0; }
        .container { max-width: 700px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px #0001; padding: 32px; }
        h1 { color: #333; text-align: center; }
        .plan-list { margin-top: 24px; }
        .plan-item { background: #f4f6fa; border-radius: 8px; margin-bottom: 18px; box-shadow: 0 1px 4px #0001; padding: 18px 24px; }
        .plan-title { font-size: 1.2em; color: #2196f3; font-weight: 600; margin-bottom: 10px; }
        .plan-link { color: #4caf50; text-decoration: none; font-weight: 500; }
        .plan-link:hover { text-decoration: underline; }
        .empty { color: #888; text-align: center; margin-top: 32px; }
        .back { display: inline-block; margin-top: 24px; color: #2196f3; text-decoration: none; font-size: 1em; }
        .back:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Antrenman Planlarım</h1>
        <div class="plan-list">
        <?php if (count($workouts) === 0): ?>
            <div class="empty">Henüz hiç antrenman planı oluşturmadınız.</div>
        <?php else: ?>
            <?php foreach ($workouts as $plan): ?>
                <div class="plan-item">
                    <div class="plan-title"><?=htmlspecialchars($plan['day_name'])?></div>
                    <a class="plan-link" href="workout_detail.php?id=<?=$plan['id']?>">Detayları Gör</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        </div>
        <a class="back" href="dashboard.php">&larr; Dashboard'a Dön</a>
    </div>
</body>
</html>
