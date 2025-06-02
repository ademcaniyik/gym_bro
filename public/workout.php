<?php
require __DIR__ . '/../vendor/autoload.php';
use App\Controllers\WorkoutController;

$controller = new WorkoutController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->savePlan();
} else {
    $controller->showPlanForm();
}
?>
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
            <li><a href="progress_report.php"><i class="fa-solid fa-chart-line"></i> <span>Gelişim Raporu</span></a></li>
            <li><a href="logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> <span>Çıkış Yap</span></a></li>
        </ul>
    </div>
    <button class="sidebar-hamburger" id="sidebarHamburger" aria-label="Menüyü Aç/Kapat">
      <span></span><span></span><span></span>
    </button>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="dashboard-main">
    <!-- ...existing page content... -->
    <script>
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
