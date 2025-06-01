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
        if (isset($_POST['day'], $_POST['exercise']) && is_array($_POST['exercise'])) {
            $day = trim($_POST['day']);
            $exercises = $_POST['exercise'];
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
                // Her hareket için
                foreach ($exercises as $ex) {
                    $exName = trim($ex['name']);
                    $stmt3 = $db->prepare("INSERT INTO workout_exercises (workout_id, exercise) VALUES (:workout_id, :exercise)");
                    $stmt3->bindParam(':workout_id', $workout_id);
                    $stmt3->bindParam(':exercise', $exName);
                    $stmt3->execute();
                    $exercise_id = $db->lastInsertId();
                    // Her set için
                    if (isset($ex['sets']) && is_array($ex['sets'])) {
                        foreach ($ex['sets'] as $setIndex => $set) {
                            $rep = isset($set['rep']) ? (int)$set['rep'] : 0;
                            $weight = isset($set['weight']) ? (float)$set['weight'] : 0;
                            // Eğer değerler boşsa, kaydetme
                            if ($rep > 0) {
                                $stmt4 = $db->prepare("INSERT INTO workout_sets (exercise_id, set_number, rep_count, weight) VALUES (:exercise_id, :set_number, :rep_count, :weight)");
                                $stmt4->bindParam(':exercise_id', $exercise_id);
                                $stmt4->bindParam(':set_number', $setIndex);
                                $stmt4->bindParam(':rep_count', $rep);
                                $stmt4->bindParam(':weight', $weight);
                                $stmt4->execute();
                            }
                        }
                    }
                }
                $success = 'Tüm hareket ve setler başarıyla kaydedildi!';
            } catch (\Exception $e) {
                $error = 'Bir hata oluştu: ' . $e->getMessage();
            }
        } else {
            $error = 'Lütfen tüm alanları doldurun.';
        }
        $this->showPlanForm($success, $error);
    }

    /**
     * Kullanıcının antrenman planındaki günleri (day_name) dizi olarak döndürür
     */
    public function getUserWorkoutDays($userId)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT DISTINCT day_name FROM workouts WHERE user_id = :user_id ORDER BY id ASC");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $days = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        return $days;
    }
}
