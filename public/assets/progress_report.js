// progress_report.js - Sadece gelişim raporu sayfası için Chart.js kodu
document.addEventListener('DOMContentLoaded', function() {
  const allData = window.progressData;
  const select = document.getElementById('exercise-select');
  const ctx = document.getElementById('progressChart').getContext('2d');
  let chart;
  function renderChart(exName) {
      const exData = allData[exName] || {};
      const labels = Object.keys(exData);
      const weights = labels.map(date => {
          // Aynı gün birden fazla set varsa en yüksek kilo
          return Math.max(...exData[date].map(s => parseFloat(s.weight)));
      });
      const reps = labels.map(date => {
          // Aynı gün birden fazla set varsa toplam tekrar
          return exData[date].reduce((sum, s) => sum + parseInt(s.rep), 0);
      });
      if (chart) chart.destroy();
      chart = new Chart(ctx, {
          type: 'line',
          data: {
              labels,
              datasets: [
                  {
                      label: 'Kilo (kg)',
                      data: weights,
                      borderColor: '#2196f3',
                      backgroundColor: 'rgba(33,150,243,0.1)',
                      yAxisID: 'y',
                  },
                  {
                      label: 'Toplam Tekrar',
                      data: reps,
                      borderColor: '#4caf50',
                      backgroundColor: 'rgba(76,175,80,0.1)',
                      yAxisID: 'y1',
                  }
              ]
          },
          options: {
              responsive: true,
              interaction: { mode: 'index', intersect: false },
              stacked: false,
              plugins: { legend: { position: 'top' } },
              scales: {
                  y: { type: 'linear', display: true, position: 'left', title: { display: true, text: 'Kilo (kg)' } },
                  y1: { type: 'linear', display: true, position: 'right', grid: { drawOnChartArea: false }, title: { display: true, text: 'Toplam Tekrar' } }
              }
          }
      });
  }
  if (select) {
    select.addEventListener('change', e => renderChart(e.target.value));
    if (select.value) renderChart(select.value);
  }
});
