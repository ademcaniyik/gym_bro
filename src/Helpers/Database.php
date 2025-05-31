<?php

namespace App\Helpers;

use PDO;
use PDOException;
use Dotenv\Dotenv;

class Database
{
    private static $instance = null;

    private function __construct()
    {
        // Singleton: dışarıdan nesne oluşturmayı engelle
    }

    public static function getConnection()
    {
        if (self::$instance === null) {
            try {
                // Autoload dosyasını yükle (yalnızca Dotenv bulunamazsa)
                if (!class_exists(Dotenv::class)) {
                    require_once __DIR__ . '/../../vendor/autoload.php';
                }

                // Dotenv'i başlat ve yükle
                $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
                $dotenv->load();

                // PDO bağlantısı
                $dsn = "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'] . ";charset=" . $_ENV['DB_CHARSET'];
                self::$instance = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                file_put_contents(__DIR__ . '/error_log.txt', $e->getMessage(), FILE_APPEND);
                die("Database connection failed. Check error_log.txt for details.");
            }
        }
        return self::$instance;
    }
}
