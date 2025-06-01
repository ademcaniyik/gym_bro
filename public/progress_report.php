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
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gelişim Raporu</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
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
    <script>
    const allData = <?=json_encode($data)?>;
    const select = document.getElementById('exercise-select');
    const ctx = document.getElementById('progressChart').getContext('2d');
    let chart;
    function renderChart(exName) {
        const exData = allData[exName] || {};
        const labels = Object.keys(exData);
        const weights = labels.map(date => {
            // Aynı gün birden fazla set varsa en yüksek kilo
            return Math.max(...exData[date].map(s => parseFloat(s.weight)));
        });
        const reps = labels.map(date => {
            // Aynı gün birden fazla set varsa toplam tekrar
            return exData[date].reduce((sum, s) => sum + parseInt(s.rep), 0);
        });
        if (chart) chart.destroy();
        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Kilo (kg)',
                        data: weights,
                        borderColor: '#2196f3',
                        backgroundColor: 'rgba(33,150,243,0.1)',
                        yAxisID: 'y',
                    },
                    {
                        label: 'Toplam Tekrar',
                        data: reps,
                        borderColor: '#4caf50',
                        backgroundColor: 'rgba(76,175,80,0.1)',
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                stacked: false,
                plugins: { legend: { position: 'top' } },
                scales: {
                    y: { type: 'linear', display: true, position: 'left', title: { display: true, text: 'Kilo (kg)' } },
                    y1: { type: 'linear', display: true, position: 'right', grid: { drawOnChartArea: false }, title: { display: true, text: 'Toplam Tekrar' } }
                }
            }
        });
    }
    select.addEventListener('change', e => renderChart(e.target.value));
    if (select.value) renderChart(select.value);
    </script>
</body>
</html>
