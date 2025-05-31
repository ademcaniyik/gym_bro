<?php
// PHP hata ayıklama için hata gösterimi etkinleştiriliyor
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Veritabanı sınıfını dahil et
require __DIR__ . '/../src/Helpers/Database.php';

use App\Helpers\Database;

try {
    // Veritabanı bağlantısını al
    $db = Database::getConnection();

    // Basit bir sorgu yap
    $query = $db->query("SHOW TABLES");
    $tables = $query->fetchAll(PDO::FETCH_ASSOC);

    // Tabloları ekrana yazdır
    echo "Veritabanındaki tablolar:<br>";
    print_r($tables);
} catch (Exception $e) {
    // Hata mesajını error.log dosyasına yaz
    $logFile = __DIR__ . '/error.log'; // Bulunduğu dizine log dosyasını yaz
    $errorMessage = "[" . date('Y-m-d H:i:s') . "] " . $e->getMessage() . PHP_EOL;
    file_put_contents($logFile, $errorMessage, FILE_APPEND);

    // Kullanıcıya basit bir hata mesajı göster
    echo "Bir hata meydana geldi. Detaylar 'error.log' dosyasına yazıldı.";
}

// Log dosyasını yazma izni olmadığında bu hatayı test etmek için kontrol edebilirsin
