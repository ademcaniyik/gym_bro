// main.js - Ortak JS (sidebar, dark mode, hamburger, logo)
document.addEventListener('DOMContentLoaded', function() {
  // Sidebar toggle (mobil ve masaüstü)
  const sidebar = document.getElementById('sidebar');
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebarHamburger = document.getElementById('sidebarHamburger');
  const sidebarOverlay = document.getElementById('sidebarOverlay');
  if (sidebarToggle) {
    sidebarToggle.onclick = function(e) {
      e.stopPropagation();
      if(window.innerWidth > 600) {
        sidebar.classList.toggle('collapsed');
      } else {
        sidebar.classList.toggle('open');
        if(sidebarOverlay) sidebarOverlay.style.display = sidebar.classList.contains('open') ? 'block' : 'none';
      }
      updateSidebarLogo();
    };
  }
  if (sidebarHamburger) {
    sidebarHamburger.onclick = function(e) {
      e.stopPropagation();
      sidebar.classList.add('open');
      if(sidebarOverlay) sidebarOverlay.style.display = 'block';
      updateSidebarLogo();
    };
  }
  if (sidebarOverlay) {
    sidebarOverlay.onclick = function() {
      sidebar.classList.remove('open');
      sidebarOverlay.style.display = 'none';
      updateSidebarLogo();
    };
  }
  document.body.addEventListener('click', function(e) {
    if(window.innerWidth <= 600 && sidebar && sidebar.classList.contains('open')) {
      if(!sidebar.contains(e.target) && (!sidebarHamburger || !sidebarHamburger.contains(e.target))) {
        sidebar.classList.remove('open');
        if(sidebarOverlay) sidebarOverlay.style.display = 'none';
        updateSidebarLogo();
      }
    }
  });
  function updateSidebarLogo() {
    const logoText = document.querySelector('.sidebar-logo-text');
    if (!logoText) return;
    if ((window.innerWidth <= 600 && !sidebar.classList.contains('open')) || (window.innerWidth > 600 && sidebar.classList.contains('collapsed'))) {
      logoText.style.display = 'none';
    } else {
      logoText.style.display = '';
    }
  }
  window.addEventListener('resize', updateSidebarLogo);
  updateSidebarLogo();
  // Dark mode toggle
  // Tüm sayfalarda birden fazla darkModeToggle olabileceği için querySelectorAll ile hepsine event ekle
  document.querySelectorAll('#darkModeToggle').forEach(function(btn) {
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
  });
});
