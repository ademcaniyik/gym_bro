<?php
require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Helpers/Database.php';

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


    try {
        $db = Database::getConnection();
        // Kullanıcıyı veritabanına kaydetme
        $stmt = $db->prepare("INSERT INTO users (google_id, name, email, picture) VALUES (:google_id, :name, :email, :picture)
                                  ON DUPLICATE KEY UPDATE name = :name, email = :email, picture = :picture");
        $stmt->bindParam(':google_id', $userInfo->id);
        $stmt->bindParam(':name', $userInfo->name);
        $stmt->bindParam(':email', $userInfo->email);
        $stmt->bindParam(':picture', $userInfo->picture);
        $stmt->execute();

    } catch (Exception $e) {
        // Hata loglama
        $logFile = __DIR__ . '/error.log';
        $errorMessage = "[" . date('Y-m-d H:i:s') . "] " . $e->getMessage() . PHP_EOL;
        file_put_contents($logFile, $errorMessage, FILE_APPEND);
        echo "Bir hata meydana geldi. Detaylar 'error.log' dosyasına yazıldı.";
        exit;
    }

    // Giriş sonrası sayfaya yönlendirme
    header('Location: profile.php');
    exit;
}
