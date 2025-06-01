<?php // ...existing code...
$userName = htmlspecialchars($user['name']);
$profilePic = htmlspecialchars($user['picture']);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilim | GYM BRO</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6fa; margin: 0; }
        .profile-container { max-width: 500px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px #0001; padding: 32px; text-align: center; }
        .profile-pic { width: 80px; height: 80px; border-radius: 50%; border: 3px solid #2196f3; margin-bottom: 12px; }
        h1 { color: #333; }
        .info { font-size: 1.1em; color: #555; margin-bottom: 18px; }
        .back { display: inline-block; margin-top: 24px; color: #2196f3; text-decoration: none; font-size: 1em; }
        .back:hover { text-decoration: underline; }
    </style>
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