<?php

require __DIR__ . '/../vendor/autoload.php'; // Composer autoload'u dahil et

use App\Helpers\Database;
use App\Models\Workout;

// Database Sınıfını Test Et
try {
    $db = Database::getConnection();
    echo "Database connection successful!<br>";
} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage() . "<br>";
}

// Workout Sınıfını Test Et
if (class_exists(Workout::class)) {
    echo "Workout class loaded successfully!<br>";
} else {
    echo "Failed to load Workout class.<br>";
}

// Namespace Testi
if (class_exists(App\Helpers\Database::class)) {
    echo "App\Helpers\Database namespace loaded successfully!<br>";
} else {
    echo "Failed to load App\Helpers\Database namespace.<br>";
}

