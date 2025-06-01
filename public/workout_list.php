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
$plans = $db->prepare("SELECT w.id, w.day_name, w.created_at, COUNT(e.id) as hareket_sayisi FROM workouts w LEFT JOIN workout_exercises e ON w.id = e.workout_id WHERE w.user_id = :user_id GROUP BY w.id ORDER BY w.created_at DESC");
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
        table { width: 100%; border-collapse: collapse; margin-top: 24px; }
        th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
        th { background: #f4f6fa; color: #2196f3; }
        tr:hover { background: #f1f8e9; }
        .back { display: inline-block; margin-top: 24px; color: #2196f3; text-decoration: none; font-size: 1em; }
        .back:hover { text-decoration: underline; }
        .empty { color: #888; text-align: center; margin-top: 32px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Antrenman Planlarım</h1>
        <?php if (count($workouts) === 0): ?>
            <div class="empty">Henüz hiç antrenman planı oluşturmadınız.</div>
        <?php else: ?>
        <table>
            <tr>
                <th>Gün</th>
                <th>Oluşturulma</th>
                <th>Hareket Sayısı</th>
                <th>Detay</th>
            </tr>
            <?php foreach ($workouts as $plan): ?>
            <tr>
                <td><?=htmlspecialchars($plan['day_name'])?></td>
                <td><?=htmlspecialchars($plan['created_at'])?></td>
                <td><?=htmlspecialchars($plan['hareket_sayisi'])?></td>
                <td><a href="workout_detail.php?id=<?=$plan['id']?>">Detay</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
        <a class="back" href="dashboard.php">&larr; Dashboard'a Dön</a>
    </div>
</body>
</html>
