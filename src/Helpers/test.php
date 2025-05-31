<?php

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

try {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();

    echo "DB_HOST: " . $_ENV['DB_HOST'] . "<br>";
    echo "DB_NAME: " . $_ENV['DB_NAME'] . "<br>";
    echo "DB_USER: " . $_ENV['DB_USER'] . "<br>";
    echo "DB_PASSWORD: " . $_ENV['DB_PASSWORD'] . "<br>";

    echo "<br>Dotenv çalışıyor! 🎉";
} catch (Exception $e) {
    echo "Dotenv yüklenirken bir hata oluştu: " . $e->getMessage();
}
