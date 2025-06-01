<?php
require __DIR__ . '/../vendor/autoload.php';
use App\Controllers\WorkoutController;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}
$user = $_SESSION['user'];
$controller = new WorkoutController();
$days = $controller->getUserWorkoutDays($user['db_id']);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrenman Günü Seç</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Hangi günün antrenmanını yapmak istiyorsun?</h1>
        <form method="get" action="workout_entry.php">
            <select name="day" required style="font-size:1.1em; padding:8px;">
                <option value="">Gün Seçiniz</option>
                <?php foreach ($days as $day): ?>
                    <option value="<?=htmlspecialchars($day)?>"><?=htmlspecialchars($day)?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="primary-btn" style="margin-left:12px;">Başla</button>
        </form>
        <a class="back" href="dashboard.php">&larr; Dashboard'a Dön</a>
    </div>
</body>
</html>
