<?php
require_once '../../vendor/autoload.php';
require_once 'api_helper.php';
use App\Helpers\Database;

// Mobil uygulama Google'dan aldığı id_token'ı POST ile gönderir
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['id_token'])) {
    http_response_code(400);
    api_json(['error' => 'id_token gerekli']);
    exit;
}

$client = new Google_Client(['client_id' => $_ENV['GOOGLE_CLIENT_ID']]);
$payload = $client->verifyIdToken($data['id_token']);
if (!$payload) {
    http_response_code(401);
    api_json(['error' => 'Geçersiz Google token']);
    exit;
}

// Kullanıcıyı DB'de bul veya oluştur
$db = Database::getConnection();
$stmt = $db->prepare("INSERT INTO users (google_id, name, email, profile_picture) VALUES (:google_id, :name, :email, :profile_picture)
    ON DUPLICATE KEY UPDATE name = :name, email = :email, profile_picture = :profile_picture");
$stmt->bindParam(':google_id', $payload['sub']);
$stmt->bindParam(':name', $payload['name']);
$stmt->bindParam(':email', $payload['email']);
$stmt->bindParam(':profile_picture', $payload['picture']);
$stmt->execute();
$stmt2 = $db->prepare("SELECT * FROM users WHERE google_id = :google_id LIMIT 1");
$stmt2->bindParam(':google_id', $payload['sub']);
$stmt2->execute();
$user = $stmt2->fetch(PDO::FETCH_ASSOC);

// JWT üret ve döndür
$jwt = api_generate_jwt($user);
api_json(['token' => $jwt, 'user' => [
    'id' => $user['id'],
    'name' => $user['name'],
    'email' => $user['email'],
    'picture' => $user['profile_picture']
]]);
