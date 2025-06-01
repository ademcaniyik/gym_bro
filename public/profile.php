<?php
require __DIR__ . '/../vendor/autoload.php';
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}
use App\Controllers\UserController;
$controller = new UserController();
$controller->showProfile();
