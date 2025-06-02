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
$db = Database::getConnection();
// Kullanıcının yaptığı tüm hareketleri ve loglarını çek
$stmt = $db->prepare("SELECT e.exercise, l.log_date, s.rep_count, s.weight FROM workout_log_sets s
    JOIN workout_logs l ON s.log_id = l.id
    JOIN workout_exercises e ON s.exercise_id = e.id
    WHERE l.user_id = :user_id
    ORDER BY e.exercise, l.log_date ASC");
$stmt->bindParam(':user_id', $user['db_id']);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Verileri JS için grupla
$data = [];
foreach ($rows as $row) {
    $ex = $row['exercise'];
    $date = date('Y-m-d', strtotime($row['log_date']));
    if (!isset($data[$ex])) $data[$ex] = [];
    $data[$ex][$date][] = [
        'rep' => $row['rep_count'],
        'weight' => $row['weight']
    ];
}
?><!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelişim Raporu</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <?php include __DIR__ . '/../src/Views/sidebar.view.php'; ?>
    <div class="dashboard-main">
        <div class="dashboard-card">
            <div class="container">
                <h1>Gelişim Raporu</h1>
                <p>Her hareket için zaman içindeki kilo ve tekrar gelişimini aşağıda görebilirsin.</p>
                <div>
                    <label for="exercise-select">Hareket Seç:</label>
                    <select id="exercise-select">
                        <?php foreach (array_keys($data) as $ex): ?>
                            <option value="<?=htmlspecialchars($ex)?>"><?=htmlspecialchars($ex)?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <canvas id="progressChart" height="100"></canvas>
                <a class="back" href="dashboard.php">&larr; Dashboard'a Dön</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      window.progressData = <?=json_encode($data)?>;
    </script>
    <script src="assets/main.js"></script>
    <script src="assets/progress_report.js"></script>
</body>
</html>
