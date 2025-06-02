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
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymBro - Akıllı Antrenman Takip Sistemi</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body.landing-bg {
            background: linear-gradient(120deg, #e3e9f7 0%, #f8fafc 100%);
        }
        .landing-container {
            max-width: 480px;
            margin: 48px auto 0 auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px #0002;
            padding: 36px 28px 28px 28px;
            text-align: center;
        }
        .landing-logo {
            font-size: 2.2em;
            font-weight: 800;
            color: #2196f3;
            margin-bottom: 10px;
            letter-spacing: 0.03em;
        }
        .landing-desc {
            color: #333;
            font-size: 1.08em;
            margin-bottom: 18px;
        }
        .features {
            text-align: left;
            margin: 0 auto 22px auto;
            padding: 0 0 0 12px;
            color: #1769aa;
        }
        .features li {
            margin-bottom: 7px;
            font-size: 1em;
            list-style: disc;
        }
        .google-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #fff;
            color: #222;
            border: 1.5px solid #2196f3;
            border-radius: 8px;
            font-size: 1.08em;
            font-weight: 600;
            padding: 12px 28px;
            cursor: pointer;
            box-shadow: 0 2px 8px #2196f320;
            transition: background 0.2s, box-shadow 0.2s;
            margin-top: 18px;
        }
        .google-btn:hover {
            background: #e3e9f7;
            box-shadow: 0 4px 16px #2196f340;
        }
        .google-icon {
            width: 22px;
            height: 22px;
        }
        @media (max-width: 600px) {
            .landing-container {
                max-width: 98vw;
                padding: 18px 4vw 18px 4vw;
            }
            .landing-logo {
                font-size: 1.5em;
            }
        }
    </style>
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
            <img src="https://developers.google.com/identity/images/g-logo.png" class="google-icon" alt="Google" style="vertical-align:middle;display:inline-block;"> Google ile Giriş Yap
        </a>
    </div>
</body>
</html>
