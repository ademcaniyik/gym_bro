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
    <style>
    .landing-desc { color: var(--text-main, #1a2330); background: none; }
    [data-theme="dark"] .landing-desc { color: #e3e9f7; }
    </style>
</head>
<body class="landing-bg">
    <button id="darkModeToggle" style="position:fixed;top:18px;right:18px;z-index:99;padding:8px 16px;border-radius:8px;border:none;background:#232a36;color:#fff;cursor:pointer;opacity:0.85;">🌙</button>
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
    <script>
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
    // On load, set theme
    if(localStorage.getItem('theme')==='dark') {
      document.body.setAttribute('data-theme','dark');
      btn.textContent = '☀️';
    }
    </script>
</body>
</html>
