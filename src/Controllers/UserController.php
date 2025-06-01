<?php
namespace App\Controllers;

class UserController
{
    public function showProfile()
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: index.php');
            exit;
        }
        $user = $_SESSION['user'];
        include __DIR__ . '/../Views/profile.view.php';
    }
}
