<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../src/Helpers/Database.php';

use App\Helpers\Database;

try {
    $db = Database::getConnection();
    $query = $db->query("SHOW TABLES");
    $tables = $query->fetchAll(PDO::FETCH_ASSOC);

    echo "Veritabanındaki tablolar:<br>";
    print_r($tables);
} catch (Exception $e) {
    $logFile = __DIR__ . '/error.log';
    $errorMessage = "[" . date('Y-m-d H:i:s') . "] " . $e->getMessage() . PHP_EOL;
    file_put_contents($logFile, $errorMessage, FILE_APPEND);
    echo "Bir hata meydana geldi. Detaylar 'error.log' dosyasına yazıldı.";
}
