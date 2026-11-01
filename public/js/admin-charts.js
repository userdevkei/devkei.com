(() => {
  if (!window.Chart) return;

  const palette = ['#1D4ED8', '#3DD5F3', '#0E7A55', '#F2B134', '#7C5CFF', '#EF6C5B', '#64748B'];
  Chart.defaults.font.family = "'Hanken Grotesk', system-ui, sans-serif";
  Chart.defaults.color = '#546677';

  document.querySelectorAll('canvas[data-chart]').forEach(canvas => {
    let cfg;
    try { cfg = JSON.parse(canvas.dataset.config); } catch { return; }

    if (cfg.type === 'doughnut') {
      const empty = !cfg.datasets[0].data.some(v => v > 0);
      new Chart(canvas, {
        type: 'doughnut',
        data: {
          labels: empty ? ['No data yet'] : cfg.labels,
          datasets: [{ data: empty ? [1] : cfg.datasets[0].data, backgroundColor: empty ? ['#E5EAF3'] : palette, borderWidth: 2, borderColor: '#fff' }],
        },
        options: {
          responsive: true, maintainAspectRatio: false, cutout: '66%',
          plugins: { legend: { position: 'right', labels: { boxWidth: 10, boxHeight: 10, usePointStyle: true } }, tooltip: { enabled: !empty } },
        },
      });
      return;
    }

    new Chart(canvas, {
      type: 'line',
      data: {
        labels: cfg.labels,
        datasets: cfg.datasets.map((d, i) => ({
          label: d.label, data: d.data,
          borderColor: palette[i], backgroundColor: palette[i] + '1F',
          fill: i === 0, tension: 0.35, borderWidth: 2.5, pointRadius: cfg.labels.length > 40 ? 0 : 3, pointHoverRadius: 5,
        })),
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: { legend: { align: 'end', labels: { boxWidth: 10, boxHeight: 10, usePointStyle: true } } },
        scales: {
          x: { grid: { display: false }, ticks: { maxTicksLimit: 10 } },
          y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#EDF1F7' } },
        },
      },
    });
  });
})();
