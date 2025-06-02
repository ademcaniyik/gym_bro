<?php
/**
 * Antrenman planı oluşturma ekranı (Workout Plan View)
 * Kullanıcı yeni bir gün ve hareketler ekleyebilir.
 * Tüm stiller assets/style.css üzerinden alınır.
 *
 * @var string $day Gün adı
 */
// workout.view.php
// Bir güne birden fazla hareket ekleme desteği
if (!isset($day)) $day = '';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrenman Planı Oluştur</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
    function addExerciseRow() {
        const container = document.getElementById('exercises');
        const exIndex = container.children.length;
        const row = document.createElement('div');
        row.className = 'exercise-row';
        row.innerHTML = `
            <input type="text" name="exercise[${exIndex}][name]" required placeholder="Hareket">
            <div class="sets" id="sets-${exIndex}"></div>
            <button type="button" onclick="addSetRow(${exIndex})">+ Set Ekle</button>
            <button type="button" class="remove-ex" onclick="this.parentNode.remove()">Hareketi Kaldır</button>
        `;
        container.appendChild(row);
        addSetRow(exIndex); // Varsayılan 1 set
    }
    function addSetRow(exIndex) {
        const setsDiv = document.getElementById('sets-' + exIndex);
        const setIndex = setsDiv ? setsDiv.children.length : 0;
        const setRow = document.createElement('div');
        setRow.className = 'set-row';
        setRow.innerHTML = `
            <span>${setIndex + 1}. set:</span>
            <input type="number" name="exercise[${exIndex}][sets][${setIndex}][rep]" min="1" required placeholder="Tekrar">
            <input type="number" name="exercise[${exIndex}][sets][${setIndex}][weight]" min="0" step="0.1" required placeholder="Kilo (kg)">
            <button type="button" class="remove-ex" onclick="this.parentNode.remove()">Seti Kaldır</button>
        `;
        setsDiv.appendChild(setRow);
    }
    window.onload = function() { addExerciseRow(); };
    </script>
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
            <li><a href="workout_list.php"><i class="fa-solid fa-list"></i> <span>Planlarım</span></a></li>
            <li><a href="workout.php" class="active"><i class="fa-solid fa-plus"></i> <span>Plan Oluştur</span></a></li>
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
            <h1>Yeni Antrenman Planı Oluştur</h1>
            <?php if (isset($success)) echo '<div class="success">'.$success.'</div>'; ?>
            <?php if (isset($error)) echo '<div class="error">'.$error.'</div>'; ?>
            <form method="post" action="workout.php">
                <label>Gün:</label>
                <input type="text" name="day" required placeholder="İtiş günü, Çekiş günü" value="<?=htmlspecialchars($day)?>">
                <div id="exercises" class="exercises"></div>
                <button type="button" onclick="addExerciseRow()">+ Hareket Ekle</button>
                <button type="submit">Planı Kaydet</button>
            </form>
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