(() => {
  const $ = window.jQuery;
  const parse = (value, fallback) => { try { return JSON.parse(value); } catch { return fallback; } };

  // Mobile sidebar
  const side = document.getElementById('admin-side');
  const backdrop = document.querySelector('.side-backdrop');
  const setSide = open => { side.classList.toggle('is-open', open); backdrop.hidden = !open; };
  document.querySelector('[data-side-toggle]')?.addEventListener('click', () => setSide(true));
  backdrop?.addEventListener('click', () => setSide(false));
  document.addEventListener('keydown', e => { if (e.key === 'Escape') setSide(false); });

  // Toasts
  const icons = {
    success: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8 12 3 3 5-6"/></svg>',
    error: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>',
  };
  const toastRoot = document.getElementById('toast-root');
  const toast = (type, text) => {
    const el = document.createElement('div');
    el.className = 'toast toast-' + type;
    el.setAttribute('role', type === 'error' ? 'alert' : 'status');
    el.innerHTML = icons[type];
    const span = document.createElement('span');
    span.textContent = text;
    el.appendChild(span);
    toastRoot.appendChild(el);
    setTimeout(() => el.remove(), 5000);
  };
  Object.entries(parse(toastRoot.dataset.config, {})).forEach(([type, text]) => toast(type, text));

  // Confirm dialog: <form data-confirm='{"title":"...","text":"..."}'>
  const dialog = document.getElementById('confirm-dialog');
  document.querySelectorAll('form[data-confirm]').forEach(form => {
    form.addEventListener('submit', e => {
      e.preventDefault();
      const cfg = parse(form.dataset.confirm, { title: form.dataset.confirm, text: '' });
      document.getElementById('confirm-title').textContent = cfg.title || 'Are you sure?';
      document.getElementById('confirm-text').textContent = cfg.text || '';
      dialog.returnValue = '';
      dialog.showModal();
      dialog.addEventListener('close', () => { if (dialog.returnValue === 'ok') form.submit(); }, { once: true });
    });
  });

    $.extend(true, $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: "<'dt-top'<'dt-left'l><'dt-right'Bf>>r" +
            "<'table-responsive't>" +
            "<'dt-bottom'ip>"
    });

// Adds the classes to every table before it initialises
    $(document).on('preInit.dt', function (e, settings) {
        $(settings.nTable).addClass('table table-striped table-sm fs-sm');
    });

  // DataTables: <table class="dt" data-config='{...}'>
  const tables = {};
  if ($ && $.fn.DataTable) {
    document.querySelectorAll('table.dt').forEach(el => {
      const cfg = parse(el.dataset.config, {});
      const opts = {
        pageLength: cfg.pageLength || 100,
        lengthMenu: [10, 25, 50, 100, 250, 500, 1000, 5000, 10000],
        order: cfg.order || [],
        autoWidth: false,
        dom: '<"dt-top"<"dt-left"l><"dt-right"fB>>rt<"dt-bottom"ip>',
        language: {
          search: '', searchPlaceholder: 'Search…', lengthMenu: 'Show _MENU_',
          info: 'Showing _START_ to _END_ of _TOTAL_', infoEmpty: 'Nothing to show',
          infoFiltered: '(filtered from _MAX_)', zeroRecords: 'No matching records found',
          emptyTable: 'No data yet', processing: 'Loading…',
          paginate: { previous: 'Previous', next: 'Next' },
        },
        columnDefs: (cfg.noSort || []).map(i => ({ targets: i, orderable: false, searchable: false })),
        buttons: cfg.export ? [
          { extend: 'excelHtml5', text: 'Excel', title: document.title, exportOptions: { columns: cfg.exportCols } },
          { extend: 'csvHtml5', text: 'CSV', exportOptions: { columns: cfg.exportCols } },
          { extend: 'print', text: 'Print', exportOptions: { columns: cfg.exportCols } },
        ] : [],
      };
      if (cfg.serverSide) {
        opts.serverSide = true;
        opts.processing = true;
        opts.ajax = cfg.ajax;
        opts.columns = Array.from({ length: cfg.columns }, (_, i) => ({ data: i }));
      }
      const api = $(el).DataTable(opts);
      if (el.id) tables['#' + el.id] = api;
    });

    // Status filter chips: <div data-dt-filter="#table-id" data-column="6">
    document.querySelectorAll('[data-dt-filter]').forEach(bar => {
      const table = tables[bar.dataset.dtFilter];
      if (!table) return;
      bar.addEventListener('click', e => {
        const chip = e.target.closest('.chip');
        if (!chip) return;
        bar.querySelectorAll('.chip').forEach(c => c.classList.toggle('is-active', c === chip));
        const value = chip.dataset.value;
        table.column(Number(bar.dataset.column)).search(value ? '^' + value + '$' : '', true, false).draw();
      });
    });
  }

  // Live visitors chip: polls every 30 seconds
  const live = document.querySelector('[data-live-url]');
  if (live) {
    const tick = () => fetch(live.dataset.liveUrl, { headers: { Accept: 'application/json' } })
      .then(r => r.ok ? r.json() : null)
      .then(d => {
        if (!d) return;
        live.querySelector('[data-live-count]').textContent = d.online;
        live.classList.toggle('is-live', d.online > 0);
      })
      .catch(() => {});
    tick();
    setInterval(tick, 30000);
  }
})();
