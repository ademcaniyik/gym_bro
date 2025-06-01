<?php // ...existing code...
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
</head>

<body>
    <div class="dashboard-container">
        <img src="<?= $profilePic ?>" alt="Profil Fotoğrafı" class="profile-pic">
        <h1>Hoş Geldin, <?= $userName ?>!</h1>
        <div class="welcome">GYM BRO'ya hoş geldin. Buradan antrenmanlarını planlayabilir ve gelişimini takip edebilirsin.</div>
        <div class="dashboard-actions">
            <a href="workout_list.php">Antrenman Planını Gör</a>
            <a href="workout.php">+ Yeni Antrenman Planı Oluştur</a>
            <button id="start-workout-btn" class="primary-btn">Antremandayım</button>
        </div>
        <a class="logout" href="logout.php">Çıkış Yap</a>
    </div>

    <!-- Gün seçimi için modal -->
    <div id="day-select-modal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close-modal" id="close-modal">&times;</span>
            <h2>Hangi günün antrenmanını yapmak istiyorsun?</h2>
            <form id="day-select-form" method="GET" action="workout_entry.php">
                <select name="day" id="day-select" required>
                    <!-- Günler JS ile yüklenecek -->
                </select>
                <button type="submit" class="primary-btn">Başla</button>
            </form>
        </div>
    </div>
    <script src="assets/dashboard.js"></script>
</body>

</html>