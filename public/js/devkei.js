(() => {
  // Mobile navigation
  const toggle = document.querySelector('[data-nav-toggle]');
  const nav = document.getElementById('site-nav');
  if (toggle && nav) {
    const close = () => { nav.classList.remove('is-open'); toggle.setAttribute('aria-expanded', 'false'); };
    toggle.addEventListener('click', () => {
      toggle.setAttribute('aria-expanded', String(nav.classList.toggle('is-open')));
    });
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape' && nav.classList.contains('is-open')) { close(); toggle.focus(); }
    });
  }

  // AJAX lead forms: falls back to a normal POST if JS is unavailable
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

  const setStatus = (box, type, text) => {
    box.innerHTML = '';
    if (!text) return;
    const p = document.createElement('p');
    p.className = 'alert alert-' + type;
    p.textContent = text;
    box.appendChild(p);
    box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  };

  document.querySelectorAll('form[data-ajax]').forEach(form => {
    const status = form.querySelector('[data-status]');
    form.addEventListener('submit', async e => {
      e.preventDefault();
      const btn = form.querySelector('[type="submit"]');
      const label = btn.textContent;
      form.querySelectorAll('.error').forEach(el => el.remove());
      form.querySelectorAll('.has-error').forEach(el => el.classList.remove('has-error'));
      setStatus(status, '', '');
      btn.disabled = true;
      btn.textContent = 'Sending…';

      try {
        const res = await fetch(form.action, {
          method: 'POST',
          headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
          body: new FormData(form),
        });
        const data = await res.json().catch(() => ({}));

        if (res.status === 422) {
          Object.entries(data.errors || {}).forEach(([key, messages]) => {
            const field = form.querySelector('[data-field="' + key + '"]');
            if (!field) return;
            field.classList.add('has-error');
            const p = document.createElement('p');
            p.className = 'error';
            p.textContent = messages[0];
            field.appendChild(p);
          });
          setStatus(status, 'error', 'Some details need fixing. Check the highlighted fields.');
          form.querySelector('.has-error input, .has-error select, .has-error textarea')?.focus();
        } else if (res.ok) {
          form.reset();
          setStatus(status, 'success', data.message || 'Thank you. We have received your request.');
        } else if (res.status === 429) {
          setStatus(status, 'error', 'Too many attempts. Wait a minute and try again.');
        } else {
          setStatus(status, 'error', 'Something went wrong on our side. Email or call us and we will help directly.');
        }
      } catch {
        setStatus(status, 'error', 'Could not reach the server. Check your connection and try again.');
      } finally {
        btn.disabled = false;
        btn.textContent = label;
      }
    });
  });
})();
