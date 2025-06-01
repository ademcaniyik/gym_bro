<?php
namespace App\Controllers;
use App\Helpers\Database;

class WorkoutController
{
    public function showPlanForm($success = null, $error = null)
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: index.php');
            exit;
        }
        $user = $_SESSION['user'];
        include __DIR__ . '/../Views/workout.view.php';
    }

    public function savePlan()
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: index.php');
            exit;
        }
        $user = $_SESSION['user'];
        $success = null;
        $error = null;
        if (isset($_POST['day'], $_POST['exercise'], $_POST['set'], $_POST['rep'], $_POST['weight'])) {
            $day = trim($_POST['day']);
            $exercise = trim($_POST['exercise']);
            $set = (int)$_POST['set'];
            $rep = (int)$_POST['rep'];
            $weight = (float)$_POST['weight'];
            try {
                $db = Database::getConnection();
                $stmt = $db->prepare("INSERT INTO workouts (user_id, day, exercise, set_count, rep_count, weight, created_at) VALUES (:user_id, :day, :exercise, :set_count, :rep_count, :weight, NOW())");
                $stmt->bindParam(':user_id', $user['id']);
                $stmt->bindParam(':day', $day);
                $stmt->bindParam(':exercise', $exercise);
                $stmt->bindParam(':set_count', $set);
                $stmt->bindParam(':rep_count', $rep);
                $stmt->bindParam(':weight', $weight);
                $stmt->execute();
                $success = 'Antrenman planı başarıyla kaydedildi!';
            } catch (\Exception $e) {
                $error = 'Bir hata oluştu: ' . $e->getMessage();
            }
        } else {
            $error = 'Lütfen tüm alanları doldurun.';
        }
        $this->showPlanForm($success, $error);
    }
}
