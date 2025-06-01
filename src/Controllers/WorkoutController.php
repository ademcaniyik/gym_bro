<?php

namespace App\Controllers;

use App\Helpers\Database;
use App\Models\Workout;

class WorkoutController
{
    // Antreman listesi
    public function listWorkouts($userId)
    {
        $workouts = Workout::getByUserId($userId);
        include __DIR__ . '/../Views/workout.view.php';
    }

    // Yeni antreman oluştur
    public function createWorkout($userId, $data)
    {
        return Workout::create($userId, $data);
    }

    // Antreman güncelle
    public function updateWorkout($workoutId, $data)
    {
        return Workout::update($workoutId, $data);
    }

    // Antreman sil
    public function deleteWorkout($workoutId)
    {
        return Workout::delete($workoutId);
    }
}
