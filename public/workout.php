<?php
require __DIR__ . '/../vendor/autoload.php';
use App\Controllers\WorkoutController;

$controller = new WorkoutController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->savePlan();
} else {
    $controller->showPlanForm();
}
