<?php
require __DIR__ . '/../vendor/autoload.php';
use App\Controllers\AuthController;

session_start();
if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$auth = new AuthController();
$loginUrl = $auth->getLoginUrl();
?><!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymBro - Akıllı Antrenman Takip Sistemi</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="landing-bg">
    <div class="landing-container">
        <div class="landing-logo">GymBro</div>
        <div class="landing-desc">
            Akıllı antrenman planı oluştur, geçmişini takip et, gelişimini grafiklerle gör!<br>
            Google ile güvenli giriş, mobil uyumlu ve modern arayüz.
        </div>
        <ul class="features">
            <li>Google ile hızlı ve güvenli giriş</li>
            <li>Kendi antrenman planını gün/gün ve hareket/hareket oluştur</li>
            <li>Her hareket için set/tekrar/kilo kaydı</li>
            <li>Geçmiş antrenmanlarını ve gelişimini grafiklerle takip et</li>
            <li>Mobil ve masaüstü uyumlu modern tasarım</li>
        </ul>
        <a href="<?php echo htmlspecialchars($loginUrl); ?>" class="google-btn">
            <img src="https://developers.google.com/identity/images/g-logo.png" class="google-icon" alt="Google"> Google ile Giriş Yap
        </a>
    </div>
</body>
</html>
