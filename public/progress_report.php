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
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <span class="sidebar-logo"><i class="fa-solid fa-dumbbell"></i> GymBro</span>
            <button class="sidebar-toggle" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fa-solid fa-house"></i> <span>Dashboard</span></a></li>
            <li><a href="workout_list.php"><i class="fa-solid fa-list"></i> <span>Planlarım</span></a></li>
            <li><a href="workout.php"><i class="fa-solid fa-plus"></i> <span>Plan Oluştur</span></a></li>
            <li><a href="progress_report.php" class="active"><i class="fa-solid fa-chart-line"></i> <span>Gelişim Raporu</span></a></li>
            <li><a href="logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> <span>Çıkış Yap</span></a></li>
        </ul>
    </div>
    <!-- Mobilde hamburger ve overlay -->
    <button class="sidebar-hamburger" id="sidebarHamburger" aria-label="Menüyü Aç/Kapat">
      <span></span><span></span><span></span>
    </button>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
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
    // Sidebar toggle (mobil ve masaüstü)
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarHamburger = document.getElementById('sidebarHamburger');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    // Masaüstü: daralt/aç
    sidebarToggle.onclick = function(e) {
      e.stopPropagation();
      if(window.innerWidth > 600) {
        sidebar.classList.toggle('collapsed');
      } else {
        sidebar.classList.toggle('open');
        sidebarOverlay.style.display = sidebar.classList.contains('open') ? 'block' : 'none';
      }
    };
    // Mobil: hamburger ile aç/kapat
    sidebarHamburger.onclick = function(e) {
      e.stopPropagation();
      sidebar.classList.add('open');
      sidebarOverlay.style.display = 'block';
    };
    // Overlay veya dışarı tıklayınca kapat
    sidebarOverlay.onclick = function() {
      sidebar.classList.remove('open');
      sidebarOverlay.style.display = 'none';
    };
    document.body.addEventListener('click', function(e) {
      if(window.innerWidth <= 600 && sidebar.classList.contains('open')) {
        if(!sidebar.contains(e.target) && !sidebarHamburger.contains(e.target)) {
          sidebar.classList.remove('open');
          sidebarOverlay.style.display = 'none';
        }
      }
    });
    // Dark mode toggle
    const btn = document.getElementById('darkModeToggle');
    btn.onclick = function() {
      if(document.body.getAttribute('data-theme') === 'dark') {
        document.body.removeAttribute('data-theme');
        localStorage.removeItem('theme');
        btn.textContent = '🌙';
      } else {
        document.body.setAttribute('data-theme','dark');
        localStorage.setItem('theme','dark');
        btn.textContent = '☀️';
      }
    };
    if(localStorage.getItem('theme')==='dark') {
      document.body.setAttribute('data-theme','dark');
      btn.textContent = '☀️';
    }
    // Chart.js kodu (değişmedi)
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
