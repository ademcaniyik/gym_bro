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
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6fa; margin: 0; }
        .dashboard-container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px #0001; padding: 32px; text-align: center; }
        .profile-pic { width: 80px; height: 80px; border-radius: 50%; border: 3px solid #4caf50; margin-bottom: 12px; }
        h1 { color: #333; }
        .welcome { font-size: 1.2em; color: #666; margin-bottom: 24px; }
        .dashboard-actions { display: flex; flex-direction: column; gap: 16px; margin-top: 32px; }
        .dashboard-actions a { display: block; background: #4caf50; color: #fff; text-decoration: none; padding: 14px 0; border-radius: 8px; font-size: 1.1em; font-weight: 500; transition: background 0.2s; }
        .dashboard-actions a:hover { background: #388e3c; }
        .logout { margin-top: 32px; color: #e53935; text-decoration: none; font-size: 1em; }
        .logout:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <img src="<?=$profilePic?>" alt="Profil Fotoğrafı" class="profile-pic">
        <h1>Hoş Geldin, <?=$userName?>!</h1>
        <div class="welcome">GYM BRO'ya hoş geldin. Buradan antrenmanlarını planlayabilir ve gelişimini takip edebilirsin.</div>
        <div class="dashboard-actions">
            <a href="workout.php">+ Yeni Antrenman Planı Oluştur</a>
            <a href="profile.php">Profilini Görüntüle</a>
            <a href="workout_list.php">Oluşturduğun Antrenmanları Gör</a>
        </div>
        <a class="logout" href="logout.php">Çıkış Yap</a>
    </div>
</body>
</html>
