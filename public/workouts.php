<?php

require __DIR__ . '/../src/Controllers/WorkoutController.php';

use App\Controllers\WorkoutController;

// Kullanıcı oturumu kontrol edin
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Kullanıcı bilgileri
$user = $_SESSION['user'];

// WorkoutController kullanımı
$workoutController = new WorkoutController();

// Antreman listesi
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $workoutController->listWorkouts($user['id']);
}

// Yeni antreman ekleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $workoutData = [
        'name' => $_POST['name'],
        'date' => $_POST['date'],
        'notes' => $_POST['notes']
    ];
    $workoutController->createWorkout($user['id'], $workoutData);
    header('Location: workouts.php');
}
?>
