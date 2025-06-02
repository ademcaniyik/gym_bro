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
            <span class="sidebar-logo"><i class="fa-solid fa-dumbbell"></i> <span class="sidebar-logo-text">GymBro</span></span>
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
