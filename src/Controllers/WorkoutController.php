<?php
namespace App\Controllers;

class WorkoutController
{
    public function showPlanForm()
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: index.php');
            exit;
        }
        include __DIR__ . '/../Views/workout.view.php';
    }

    public function savePlan()
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: index.php');
            exit;
        }
        // Burada formdan gelen veriler işlenecek ve kaydedilecek
        // (devamı ilerleyen adımda eklenecek)
    }
}
