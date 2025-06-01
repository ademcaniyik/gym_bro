// Dashboard.js: Antremandayım butonu ve gün seçimi modalı için JS

document.addEventListener('DOMContentLoaded', function () {
    const startBtn = document.getElementById('start-workout-btn');
    const modal = document.getElementById('day-select-modal');
    const closeModal = document.getElementById('close-modal');
    const daySelect = document.getElementById('day-select');

    startBtn.addEventListener('click', function () {
        // Günleri AJAX ile yükle
        fetch('workout_days.php')
            .then(res => res.json())
            .then(days => {
                daySelect.innerHTML = '';
                if (days.length === 0) {
                    daySelect.innerHTML = '<option value="">Planlı gün yok</option>';
                } else {
                    days.forEach(day => {
                        const opt = document.createElement('option');
                        opt.value = day;
                        opt.textContent = day;
                        daySelect.appendChild(opt);
                    });
                }
                modal.style.display = 'block';
            });
    });

    closeModal.addEventListener('click', function () {
        modal.style.display = 'none';
    });

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
});
