document.addEventListener('DOMContentLoaded', () => {
    const $ = (id) => document.getElementById(id);
    const token = () => localStorage.getItem('crm_token');
    const setTheme = (dark) => { document.body.classList.toggle('dark', dark); localStorage.setItem('crm-theme', dark ? 'dark' : 'light'); $('themeToggle').textContent = dark ? 'Light Mode' : 'Dark Mode'; };
    setTheme(localStorage.getItem('crm-theme') === 'dark');
    $('themeToggle').onclick = () => setTheme(!document.body.classList.contains('dark'));

    async function api(path, options = {}) {
        const response = await fetch(`/api/v1${path}`, { ...options, headers: { Accept: 'application/json', ...(options.body ? {'Content-Type':'application/json'} : {}), Authorization: `Bearer ${token()}`, ...(options.headers || {}) }});
        const data = await response.json().catch(() => ({}));
        if (response.status === 401) { localStorage.removeItem('crm_token'); showLogin(); throw new Error('Sesi berakhir. Silakan masuk kembali.'); }
        if (!response.ok) throw new Error(data.message || 'Gagal mengambil data.');
        return data;
    }
    function showLogin() { $('loginPanel').classList.remove('hidden'); $('dashboardApp').classList.add('hidden'); }
    function showDashboard() { $('loginPanel').classList.add('hidden'); $('dashboardApp').classList.remove('hidden'); loadDashboard(); loadCalendar(); }
    function statusChip(status) { const cls = {confirmed:'success',checked_in:'info',checked_out:'info',pending:'warning',cancelled:'danger'}[status] || 'info'; return `<span class="chip ${cls}">${status.replace('_',' ')}</span>`; }
    function metric(label, value, note, accent) { return `<article class="stat-card accent-${accent}"><div class="stat-top"><span>${label}</span><span class="badge info">Database</span></div><h2>${value}</h2><small>${note}</small></article>`; }
    async function loadDashboard() {
        try {
            const data = await api('/dashboard');
            $('statsGrid').innerHTML = metric('Booking hari ini', data.bookings_today, `${data.pending_bookings} menunggu konfirmasi`, 'teal') + metric('Check-in hari ini', data.checkins_today, `${data.active_guests} booking aktif`, 'blue') + metric('Kamar tersedia', data.available_rooms, 'Status kamar saat ini', 'gold') + metric('Check-out hari ini', data.checkouts_today, 'Jadwal keberangkatan', 'rose');
            $('bookingRows').innerHTML = (data.recent_bookings || []).map((booking) => `<tr><td>${booking.booking_code}</td><td>${booking.guest?.name || '-'}</td><td>${booking.room?.code || '-'}</td><td>${new Date(booking.check_in).toLocaleString('id-ID')}</td><td>${statusChip(booking.status)}</td></tr>`).join('') || '<tr><td colspan="5">Belum ada booking.</td></tr>';
            const total = Math.max(data.available_rooms + data.active_guests, 1), occupancy = Math.min(100, Math.round(data.active_guests / total * 100));
            $('operationalMetrics').innerHTML = `<div class="mini-metric"><div><span>Occupancy</span><strong>${occupancy}%</strong></div><div class="progress"><span style="width:${occupancy}%"></span></div></div><div class="mini-metric"><div><span>Booking pending</span><strong>${data.pending_bookings}</strong></div><div class="progress"><span style="width:${Math.min(100,data.pending_bookings*10)}%"></span></div></div>`;
            $('lastUpdated').textContent = `Terakhir diperbarui ${new Date().toLocaleString('id-ID')}`;
        } catch (error) { $('lastUpdated').textContent = error.message; }
    }
    async function loadCalendar() {
        const start = new Date(); start.setDate(1); const end = new Date(start); end.setMonth(end.getMonth()+1);
        try { const events = await api(`/calendar?start=${encodeURIComponent(start.toISOString())}&end=${encodeURIComponent(end.toISOString())}`); $('calendarEvents').innerHTML = events.map(e => `<li><span class="dot ${e.status === 'confirmed' ? 'success' : 'warning'}"></span><div><strong>${e.title}</strong><small>${new Date(e.start).toLocaleString('id-ID')} — ${e.status}</small></div></li>`).join('') || '<li>Tidak ada booking bulan ini.</li>'; } catch (error) { $('calendarEvents').innerHTML = `<li>${error.message}</li>`; }
    }
    $('adminLoginForm').onsubmit = async (event) => { event.preventDefault(); $('loginError').textContent = ''; try { const response = await fetch('/api/v1/auth/login', {method:'POST', headers:{'Content-Type':'application/json',Accept:'application/json'}, body:JSON.stringify({email:$('loginEmail').value,password:$('loginPassword').value})}); const data = await response.json(); if (!response.ok) throw new Error(data.message || 'Login gagal.'); localStorage.setItem('crm_token', data.token); showDashboard(); } catch (error) { $('loginError').textContent = error.message; } };
    $('logoutButton').onclick = async () => { try { await api('/auth/logout', {method:'POST'}); } catch (_) {} localStorage.removeItem('crm_token'); showLogin(); };
    $('refreshButton').onclick = loadDashboard; $('calendarRefresh').onclick = loadCalendar; $('loadMoreBookings').onclick = () => window.location.href = '/dashboard';
    token() ? showDashboard() : showLogin();
});
