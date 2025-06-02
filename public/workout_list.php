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
?><!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrenman Planlarım</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <span class="sidebar-logo"><i class="fa-solid fa-dumbbell"></i> GymBro</span>
            <button class="sidebar-toggle" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
        </div>
        <button id="darkModeToggle" class="darkmode-btn">🌙</button>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><i class="fa-solid fa-house"></i> <span>Dashboard</span></a></li>
            <li><a href="workout_list.php" class="active"><i class="fa-solid fa-list"></i> <span>Planlarım</span></a></li>
            <li><a href="workout.php"><i class="fa-solid fa-plus"></i> <span>Plan Oluştur</span></a></li>
            <li><a href="progress_report.php"><i class="fa-solid fa-chart-line"></i> <span>Gelişim Raporu</span></a></li>
            <li><a href="logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> <span>Çıkış Yap</span></a></li>
        </ul>
    </div>
    <button class="sidebar-hamburger" id="sidebarHamburger" aria-label="Menüyü Aç/Kapat">
      <span></span><span></span><span></span>
    </button>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="dashboard-main">
        <div class="dashboard-card">
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
    </div>
    <script>
    // Sidebar toggle
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    sidebarToggle.onclick = function() {
      sidebar.classList.toggle('collapsed');
    };
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
    </script>
</body>
</html>
