<?php
require __DIR__ . '/../vendor/autoload.php';
use App\Controllers\WorkoutController;

$controller = new WorkoutController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->savePlan();
} else {
    $controller->showPlanForm();
}
?>
<body>
    <?php include __DIR__ . '/../src/Views/sidebar.view.php'; ?>
    <div class="dashboard-main">
    <!-- ...existing page content... -->
    <script>
    // Dark mode toggle
    const btn = document.getElementById('darkModeToggle');
    btn.onclick = function() {
      if(document.body.getAttribute('data-theme') === 'dark') {
        document.body.removeAttribute('data-theme');
        localStorage.removeItem('theme');
        btn.textContent = '🌙';
      } else {
        document.body.setAttribute('data-theme','dark');
        localStorage.setItem('theme','dark');
        btn.textContent = '☀️';
      }
    };
    if(localStorage.getItem('theme')==='dark') {
      document.body.setAttribute('data-theme','dark');
      btn.textContent = '☀️';
    }
    </script>
