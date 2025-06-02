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
    <link rel="stylesheet" href="assets/style.css">
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
<script>
if(localStorage.getItem('theme')==='dark') {
  document.body.setAttribute('data-theme','dark');
}
</script>
</html>
