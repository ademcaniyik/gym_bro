<?php
require __DIR__ . '/../vendor/autoload.php';
use App\Controllers\UserController;

$controller = new UserController();
$controller->showDashboard();
?>
<body>
    <button id="darkModeToggle" style="position:fixed;top:18px;right:18px;z-index:99;padding:8px 16px;border-radius:8px;border:none;background:#232a36;color:#fff;cursor:pointer;opacity:0.85;">🌙</button>
    <div class="dashboard-container">
        <!-- ...existing dashboard content... -->
    </div>
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
    // On load, set theme
    if(localStorage.getItem('theme')==='dark') {
      document.body.setAttribute('data-theme','dark');
      btn.textContent = '☀️';
    }
    </script>
