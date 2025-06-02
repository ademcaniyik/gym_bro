<?php
/**
 * Kullanıcı profil sayfası (Profile View)
 * Kullanıcı bilgilerini ve profil fotoğrafını gösterir.
 * Tüm stiller assets/style.css üzerinden alınır.
 *
 * @var array $user Kullanıcı oturum bilgileri
 */

$userName = htmlspecialchars($user['name']);
$profilePic = htmlspecialchars($user['picture']);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilim | GYM BRO</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="profile-container">
        <img src="<?=$profilePic?>" alt="Profil Fotoğrafı" class="profile-pic">
        <h1><?=$userName?></h1>
        <div class="info">Email: <?=htmlspecialchars($user['email'])?></div>
        <div class="info">Google ID: <?=htmlspecialchars($user['id'])?></div>
        <a class="back" href="dashboard.php">&larr; Dashboard'a Dön</a>
    </div>
</body>
</html>