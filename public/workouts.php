<?php

namespace App\Models;
use App\Helpers\Database;
use PDO;
require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Helpers/Database.php';
require_once __DIR__ . '/../src/Controllers/WorkoutController.php';



class Workout
{
    // Kullanıcıya ait antremanları al
    public static function getByUserId($userId)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM workouts WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Yeni antreman oluştur
    public static function create($userId, $data)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO workouts (user_id, name, date, notes) VALUES (:user_id, :name, :date, :notes)");
        $stmt->execute([
            'user_id' => $userId,
            'name' => $data['name'],
            'date' => $data['date'],
            'notes' => $data['notes'],
        ]);
        return $db->lastInsertId();
    }

    // Antreman güncelle
    public static function update($workoutId, $data)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE workouts SET name = :name, date = :date, notes = :notes WHERE id = :id");
        $stmt->execute([
            'name' => $data['name'],
            'date' => $data['date'],
            'notes' => $data['notes'],
            'id' => $workoutId,
        ]);
    }

    // Antreman sil
    public static function delete($workoutId)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM workouts WHERE id = :id");
        $stmt->execute(['id' => $workoutId]);
    }
}
