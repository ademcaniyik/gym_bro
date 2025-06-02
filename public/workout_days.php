<?php
// workout_days.php: Kullanıcının antrenman planındaki günleri JSON olarak döner
session_start();
require_once '../src/Controllers/WorkoutController.php';
use App\Controllers\WorkoutController;

if (!isset($_SESSION['db_id'])) {
    http_response_code(401);
    echo json_encode([]);
    exit;
}

$controller = new WorkoutController();
$days = $controller->getUserWorkoutDays($_SESSION['db_id']);
echo json_encode($days);
?>
</body>
<script>
if(localStorage.getItem('theme')==='dark') {
  document.body.setAttribute('data-theme','dark');
}
</script>
</html>
