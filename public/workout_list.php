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
    <?php include __DIR__ . '/../src/Views/sidebar.view.php'; ?>
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
    // Sidebar toggle (mobil ve masaüstü)
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarHamburger = document.getElementById('sidebarHamburger');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    sidebarToggle.onclick = function(e) {
      e.stopPropagation();
      if(window.innerWidth > 600) {
        sidebar.classList.toggle('collapsed');
      } else {
        sidebar.classList.toggle('open');
        sidebarOverlay.style.display = sidebar.classList.contains('open') ? 'block' : 'none';
      }
      updateSidebarLogo();
    };
    sidebarHamburger.onclick = function(e) {
      e.stopPropagation();
      sidebar.classList.add('open');
      sidebarOverlay.style.display = 'block';
      updateSidebarLogo();
    };
    sidebarOverlay.onclick = function() {
      sidebar.classList.remove('open');
      sidebarOverlay.style.display = 'none';
      updateSidebarLogo();
    };
    document.body.addEventListener('click', function(e) {
      if(window.innerWidth <= 600 && sidebar.classList.contains('open')) {
        if(!sidebar.contains(e.target) && !sidebarHamburger.contains(e.target)) {
          sidebar.classList.remove('open');
          sidebarOverlay.style.display = 'none';
          updateSidebarLogo();
        }
      }
    });
    function updateSidebarLogo() {
      const logoText = document.querySelector('.sidebar-logo-text');
      if (!logoText) return;
      if ((window.innerWidth <= 600 && !sidebar.classList.contains('open')) || (window.innerWidth > 600 && sidebar.classList.contains('collapsed'))) {
        logoText.style.display = 'none';
      } else {
        logoText.style.display = '';
      }
    }
    window.addEventListener('resize', updateSidebarLogo);
    updateSidebarLogo();
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
