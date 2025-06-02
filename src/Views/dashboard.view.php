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
            <a href="progress_report.php">Gelişim Raporu</a>
            <form action="workout_start.php" method="get" style="margin:0;">
                <button type="submit" class="primary-btn">Antremandayım</button>
            </form>
            <a class="logout" href="logout.php">Çıkış Yap</a>
        </div>
    </div>
</body>

</html>