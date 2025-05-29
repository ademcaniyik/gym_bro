<?php
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;


// .env dosyasını yükle
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

session_start();

// Google Client Configuration
$client = new Google\Client();
$client->setClientId($_ENV['GOOGLE_CLIENT_ID']); // .env'den gelen ID
$client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']); // .env'den gelen Secret
$client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']); // .env'den gelen Redirect URI
$client->addScope('email');
$client->addScope('profile');

// Google Login URL
$loginUrl = $client->createAuthUrl();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Google ile Giriş</title>
</head>
<body>
    <h1>Google ile Giriş</h1>
    <a href="<?php echo htmlspecialchars($loginUrl); ?>">Google ile Giriş Yap</a>
</body>
</html>
