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
            $exercises = $_POST['exercise'];
            $sets = $_POST['set'];
            $reps = $_POST['rep'];
            $weights = $_POST['weight'];
            try {
                $db = Database::getConnection();
                // Önce gün (workout) var mı kontrol et, yoksa ekle
                $stmt = $db->prepare("SELECT id FROM workouts WHERE user_id = :user_id AND day_name = :day_name");
                $stmt->bindParam(':user_id', $user['db_id']);
                $stmt->bindParam(':day_name', $day);
                $stmt->execute();
                $workout = $stmt->fetch(\PDO::FETCH_ASSOC);
                if ($workout) {
                    $workout_id = $workout['id'];
                } else {
                    $stmt2 = $db->prepare("INSERT INTO workouts (user_id, day_name, created_at) VALUES (:user_id, :day_name, NOW())");
                    $stmt2->bindParam(':user_id', $user['db_id']);
                    $stmt2->bindParam(':day_name', $day);
                    $stmt2->execute();
                    $workout_id = $db->lastInsertId();
                }
                // Tüm hareketleri ekle
                for ($i = 0; $i < count($exercises); $i++) {
                    $exercise = trim($exercises[$i]);
                    $set = (int)$sets[$i];
                    $rep = (int)$reps[$i];
                    $weight = (float)$weights[$i];
                    $stmt3 = $db->prepare("INSERT INTO workout_exercises (workout_id, exercise, set_count, rep_count, weight) VALUES (:workout_id, :exercise, :set_count, :rep_count, :weight)");
                    $stmt3->bindParam(':workout_id', $workout_id);
                    $stmt3->bindParam(':exercise', $exercise);
                    $stmt3->bindParam(':set_count', $set);
                    $stmt3->bindParam(':rep_count', $rep);
                    $stmt3->bindParam(':weight', $weight);
                    $stmt3->execute();
                }
                $success = 'Tüm hareketler başarıyla kaydedildi!';
            } catch (\Exception $e) {
                $error = 'Bir hata oluştu: ' . $e->getMessage();
            }
        } else {
            $error = 'Lütfen tüm alanları doldurun.';
        }
        $this->showPlanForm($success, $error);
    }
}
