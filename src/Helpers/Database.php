<?php
namespace App\Helpers;

use PDO;
use PDOException;

class Database {
    private static $instance = null;

    private function __construct() {
        // Singleton: dışarıdan nesne oluşturmayı engelle
    }

    public static function getConnection() {
        if (self::$instance === null) {
            try {
                    $dsn = "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'] . ";charset=utf8mb4";
                self::$instance = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
