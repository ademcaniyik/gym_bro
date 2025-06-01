<?php
require __DIR__ . '/../vendor/autoload.php'; // Eğer composer autoload kullanıyorsan

use Dotenv\Dotenv;
use App\Helpers\Database;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$clientId = $_ENV['GOOGLE_CLIENT_ID'] ?? null;
$clientSecret = $_ENV['GOOGLE_CLIENT_SECRET'] ?? null;
$redirectUri = $_ENV['GOOGLE_REDIRECT_URI'] ?? null;

if (!$clientId || !$clientSecret || !$redirectUri) {
    die('Google OAuth config missing!');
}

session_start();


// Örneğin Google Client oluşturma
$client = new Google_Client();
$client->setClientId($clientId);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope('email profile');

if (isset($_GET['code'])) {
    // Access Token alma
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // Kullanıcı bilgilerini alma
    $oauth2 = new Google\Service\Oauth2($client);
    $userInfo = $oauth2->userinfo->get();

    // Kullanıcı bilgilerini kaydetme veya oturum açma işlemi
    $_SESSION['user'] = [
        'id' => $userInfo->id,
        'name' => $userInfo->name,
        'email' => $userInfo->email,
        'picture' => $userInfo->picture,
    ];

    // Kullanıcı bilgilerini veritabanına kaydetme işlemi burada yapılabilir
    // callback.php'de kullanıcı bilgilerini veritabanına kaydet
    $user = $_SESSION['user'];

    $pdo = Database::getConnection();
    $stmt = $pdo->prepare("INSERT INTO users (google_id, name, email, profile_picture) VALUES (:id, :name, :email, :profile_picture) ON DUPLICATE KEY UPDATE name = :name");
    $stmt->execute(['id' => $user['id'], 'name' => $user['name'], 'email' => $user['email'], 'profile_picture' => $user['picture']]);

    // Giriş sonrası sayfaya yönlendirme
    header('Location: profile.php');
    exit;
}
