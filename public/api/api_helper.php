<?php
// api_helper.php: API için oturum ve json yanıt fonksiyonları
require_once '../../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function api_get_jwt_secret() {
    return isset($_ENV['JWT_SECRET']) ? $_ENV['JWT_SECRET'] : 'supersecretkey';
}

function api_generate_jwt($user) {
    $payload = [
        'sub' => $user['db_id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'iat' => time(),
        'exp' => time() + 60*60*24*7 // 1 hafta
    ];
    return JWT::encode($payload, api_get_jwt_secret(), 'HS256');
}

function api_require_login() {
    if (isset($_SERVER['HTTP_AUTHORIZATION']) && preg_match('/Bearer\s+(.*)$/i', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
        $jwt = $matches[1];
        try {
            $decoded = JWT::decode($jwt, new Key(api_get_jwt_secret(), 'HS256'));
            // DB'den kullanıcıyı çek
            $userId = $decoded->sub;
            $db = App\Helpers\Database::getConnection();
            $stmt = $db->prepare('SELECT * FROM users WHERE id = :id');
            $stmt->bindParam(':id', $userId);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) return $user;
        } catch (Exception $e) {
            http_response_code(401);
            api_json(['error' => 'Invalid token']);
            exit;
        }
    }
    // Eski session tabanlı kontrol (web için)
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['user'])) {
        http_response_code(401);
        api_json(['error' => 'Unauthorized']);
        exit;
    }
    return $_SESSION['user'];
}
function api_json($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
}
