<?php
/**
 * Kullanıcı dashboard ekranı (Dashboard View)
 * Modern sidebar, ikonlu menü ve kart yapısı ile.
 * Tüm stiller assets/style.css üzerinden alınır.
 *
 * @var array $user Kullanıcı oturum bilgileri
 */
// Kullanıcı giriş yaptıktan sonra karşılanacağı modern dashboard arayüzü
$userName = htmlspecialchars($user['name']);
$profilePic = htmlspecialchars($user['picture']);
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYM BRO - Dashboard</title>
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
            <img src="<?= $profilePic ?>" alt="Profil Fotoğrafı" class="profile-pic">
            <h1>Hoş Geldin, <?= $userName ?>!</h1>
            <div class="welcome">GYM BRO'ya hoş geldin. Buradan antrenmanlarını planlayabilir ve gelişimini takip edebilirsin.</div>
            <div class="dashboard-actions">
                <a href="workout_list.php"><i class="fa-solid fa-list"></i> Antrenman Planını Gör</a>
                <a href="workout.php"><i class="fa-solid fa-plus"></i> Yeni Antrenman Planı Oluştur</a>
                <a href="progress_report.php"><i class="fa-solid fa-chart-line"></i> Gelişim Raporu</a>
                <form action="workout_start.php" method="get" style="margin:0;">
                    <button type="submit" class="primary-btn"><i class="fa-solid fa-dumbbell"></i> Antremandayım</button>
                </form>
            </div>
        </div>
    </div>
    <script>
    // Sidebar toggle (mobil için de tam ekran açılır menü)
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    sidebarToggle.onclick = function(e) {
      e.stopPropagation();
      sidebar.classList.toggle('open');
    };
    document.body.addEventListener('click', function(e) {
      if(window.innerWidth <= 600 && sidebar.classList.contains('open')) {
        if(!sidebar.contains(e.target)) sidebar.classList.remove('open');
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
    </script>
</body>

</html>