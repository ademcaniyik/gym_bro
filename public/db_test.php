<?php 
require __DIR__ . '/../src/Helpers/Database.php';

use App\Helpers\Database;

try {
    // Veritabanı bağlantısını al
    $db = Database::getConnection();

    // Örnek sorgu
    $query = $db->query("SELECT * FROM users");
    $users = $query->fetchAll(PDO::FETCH_ASSOC);

    // Kullanıcıları yazdır
    print_r($users);
} catch (Exception $e) {
    // Hata mesajını error.log dosyasına yazdır
    $logFile = __DIR__ . '/error.log';
    $errorMessage = "[" . date('Y-m-d H:i:s') . "] " . $e->getMessage() . PHP_EOL;
    file_put_contents($logFile, $errorMessage, FILE_APPEND);

    // Hata mesajını kullanıcıya göstermek isteğe bağlıdır
    echo "Bir hata meydana geldi. Detaylar error.log dosyasına yazıldı.";
}
// Bu dosya, veritabanı bağlantısını test etmek için kullanılır.