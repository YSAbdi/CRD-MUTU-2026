document.addEventListener('DOMContentLoaded', () => {
    const byId = (id) => document.getElementById(id);
    const setTheme = (dark) => {
        document.body.classList.toggle('dark', dark);
        localStorage.setItem('crm-theme', dark ? 'dark' : 'light');
        const button = byId('themeToggle');
        if (button) button.textContent = dark ? 'Light Mode' : 'Dark Mode';
    };
    setTheme(localStorage.getItem('crm-theme') === 'dark');
    byId('themeToggle')?.addEventListener('click', () => setTheme(!document.body.classList.contains('dark')));

    // Dashboard-only behavior; CRUD pages safely use the same asset.
    if (!byId('dashboardApp')) return;
    const token = () => localStorage.getItem('crm_token');
    const escapeHtml = (value) => String(value ?? '').replace(/[&<>'"]/g, (c) => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[c]));
    const showLogin = () => { byId('loginPanel')?.classList.remove('hidden'); byId('dashboardApp')?.classList.add('hidden'); };
    const showApp = () => { byId('loginPanel')?.classList.add('hidden'); byId('dashboardApp')?.classList.remove('hidden'); loadDashboard(); loadCalendar(); };

    async function api(path, options = {}) {
        const response = await fetch(`/api/v1${path}`, { ...options, headers: { Accept:'application/json', ...(options.body ? {'Content-Type':'application/json'} : {}), Authorization:`Bearer ${token()}`, ...(options.headers || {}) }});
        const data = await response.json().catch(() => ({}));
        if (response.status === 401) { localStorage.removeItem('crm_token'); showLogin(); throw new Error('Sesi berakhir, silakan login lagi.'); }
        if (!response.ok) throw new Error(data.message || Object.values(data.errors || {}).flat().join(' ') || 'Permintaan gagal.');
        return data;
    }
    const chip = (status) => `<span class="chip ${status === 'confirmed' ? 'success' : status === 'pending' ? 'warning' : status === 'cancelled' ? 'danger' : 'info'}">${escapeHtml(String(status).replaceAll('_',' '))}</span>`;
    const metric = (title, value, note, accent) => `<article class="stat-card accent-${accent}"><div class="stat-top"><span>${title}</span><span class="badge info">Live</span></div><h2>${value ?? 0}</h2><small>${note}</small></article>`;
    async function loadDashboard() {
        try {
            const d = await api('/dashboard');
            byId('statsGrid').innerHTML = metric('Booking hari ini',d.bookings_today,`${d.pending_bookings} pending`,'teal') + metric('Check-in hari ini',d.checkins_today,`${d.active_guests} booking aktif`,'blue') + metric('Kamar tersedia',d.available_rooms,'Status kamar saat ini','gold') + metric('Check-out hari ini',d.checkouts_today,'Jadwal keberangkatan','rose');
            byId('bookingRows').innerHTML = (d.recent_bookings || []).map(b => `<tr><td>${escapeHtml(b.booking_code)}</td><td>${escapeHtml(b.guest?.name || '-')}</td><td>${escapeHtml(b.room?.code || '-')}</td><td>${new Date(b.check_in).toLocaleString('id-ID')}</td><td>${chip(b.status)}</td></tr>`).join('') || '<tr><td colspan="5">Belum ada booking.</td></tr>';
            const total = Math.max((d.available_rooms || 0) + (d.active_guests || 0), 1), occupancy = Math.min(100, Math.round((d.active_guests || 0) / total * 100));
            byId('operationalMetrics').innerHTML = `<div class="mini-metric"><div><span>Occupancy</span><strong>${occupancy}%</strong></div><div class="progress"><span style="width:${occupancy}%"></span></div></div><div class="mini-metric"><div><span>Booking pending</span><strong>${d.pending_bookings}</strong></div><div class="progress"><span style="width:${Math.min(100,d.pending_bookings * 10)}%"></span></div></div>`;
            if (byId('lastUpdated')) byId('lastUpdated').textContent = `Terakhir diperbarui ${new Date().toLocaleString('id-ID')}`;
        } catch (error) { if (byId('lastUpdated')) byId('lastUpdated').textContent = error.message; }
    }
    async function loadCalendar() {
        const start = new Date(); start.setDate(1); const end = new Date(start); end.setMonth(end.getMonth() + 1);
        try { const events = await api(`/calendar?start=${encodeURIComponent(start.toISOString())}&end=${encodeURIComponent(end.toISOString())}`); byId('calendarEvents').innerHTML = events.map(e => `<li><span class="dot ${e.status === 'confirmed' ? 'success' : 'warning'}"></span><div><strong>${escapeHtml(e.title)}</strong><small>${new Date(e.start).toLocaleString('id-ID')} · ${escapeHtml(e.status)}</small></div></li>`).join('') || '<li>Tidak ada booking bulan ini.</li>'; } catch (error) { byId('calendarEvents').innerHTML = `<li>${escapeHtml(error.message)}</li>`; }
    }
    byId('adminLoginForm')?.addEventListener('submit', async (event) => {
        event.preventDefault();
        try { const response = await fetch('/api/v1/auth/login', { method:'POST', headers:{'Content-Type':'application/json',Accept:'application/json'}, body:JSON.stringify({email:byId('loginEmail').value,password:byId('loginPassword').value}) }); const data = await response.json(); if (!response.ok) throw new Error(data.message || 'Login gagal.'); localStorage.setItem('crm_token', data.token); showApp(); } catch (error) { byId('loginError').textContent = error.message; }
    });
    byId('logoutButton')?.addEventListener('click', async () => { try { await api('/auth/logout', { method:'POST' }); } catch (_) {} localStorage.removeItem('crm_token'); showLogin(); });
    byId('refreshButton')?.addEventListener('click', loadDashboard); byId('calendarRefresh')?.addEventListener('click', loadCalendar);
    let deferredPrompt;
    window.addEventListener('beforeinstallprompt', (event) => { event.preventDefault(); deferredPrompt = event; if (byId('installButton')) byId('installButton').hidden = false; });
    byId('installButton')?.addEventListener('click', async () => { if (!deferredPrompt) return; deferredPrompt.prompt(); await deferredPrompt.userChoice; deferredPrompt = null; byId('installButton').hidden = true; });
    if ('serviceWorker' in navigator) navigator.serviceWorker.register('/sw.js').catch(() => {});
    token() ? showApp() : showLogin();
});
