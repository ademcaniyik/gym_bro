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
    <button id="darkModeToggle" style="position:fixed;top:18px;right:18px;z-index:99;padding:8px 16px;border-radius:8px;border:none;background:#232a36;color:#fff;cursor:pointer;opacity:0.85;">🌙</button>
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
