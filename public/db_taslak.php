<?php
require_once __DIR__ . '/../src/Helpers/Database.php';

use App\Helpers\Database;

try {
    $db = Database::getConnection();

    // Hazırlıklı sorgu ile veri çekmek
    $stmt = $db->prepare("SELECT * FROM users");
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($users) {
        foreach ($users as $user) {
            echo "Kullanıcı bulundu: " . $user['name'] . "<br>";
        }
    } else {
        echo "Kullanıcı bulunamadı.";
    }
} catch (Exception $e) {
    $logFile = __DIR__ . '/error.log';
    $errorMessage = "[" . date('Y-m-d H:i:s') . "] " . $e->getMessage() . PHP_EOL;
    file_put_contents($logFile, $errorMessage, FILE_APPEND);
    echo "Bir hata meydana geldi. Detaylar 'error.log' dosyasına yazıldı.";
}
