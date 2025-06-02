<?php /* Ortak Sidebar - Her sayfa için aynı */ ?>
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
    <button id="darkModeToggle" class="darkmode-btn" style="position:static;top:auto;right:auto;margin:18px 0 8px 18px;width:auto;z-index:auto;">🌙</button>
</div>
<!-- Mobilde hamburger ve overlay -->
<button class="sidebar-hamburger" id="sidebarHamburger" aria-label="Menüyü Aç/Kapat">
  <span></span><span></span><span></span>
</button>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
