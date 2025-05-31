<?php

// 1. Autoload dosyasını yükle
require __DIR__ . '/../vendor/autoload.php';

// 2. Dotenv sınıfını kullan
use Dotenv\Dotenv;

try {
    // 3. .env dosyasını yükle
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../../'); // .env dosyası bir üst dizinde
    $dotenv->load();

    // 4. .env dosyasından bir değişkeni oku ve ekrana yaz
    echo "DB_HOST: " . $_ENV['DB_HOST'] . "<br>";
    echo "DB_NAME: " . $_ENV['DB_NAME'] . "<br>";
    echo "DB_USER: " . $_ENV['DB_USER'] . "<br>";
    echo "DB_PASSWORD: " . $_ENV['DB_PASSWORD'] . "<br>";

    echo "<br>Dotenv çalışıyor! 🎉";
} catch (Exception $e) {
    // Hata durumunda hata mesajını yazdır
    echo "Dotenv yüklenirken bir hata oluştu: " . $e->getMessage();
}
