const adminApi = {
    async request(path, options = {}) {
        const token = localStorage.getItem('crm_token');
        const response = await fetch(`/api/v1${path}`, {
            ...options,
            headers: {
                Accept: 'application/json',
                ...(options.body ? { 'Content-Type': 'application/json' } : {}),
                ...(token ? { Authorization: `Bearer ${token}` } : {}),
                ...(options.headers || {})
            }
        });
        const data = await response.json().catch(() => ({}));
        if (response.status === 401) {
            localStorage.removeItem('crm_token');
            window.location.href = '/admin/dashboard';
            throw new Error('Sesi berakhir. Silakan login ulang.');
        }
        if (!response.ok) throw new Error(data.message || 'Permintaan gagal.');
        return data;
    }
};

async function authRequiredCheck() {
    const token = localStorage.getItem('crm_token');
    if (!token) {
        window.location.href = '/admin/dashboard';
        return false;
    }
    return true;
}

function attachModalBehavior() {
    document.querySelectorAll('.close-modal').forEach((button) => {
        button.addEventListener('click', () => {
            const target = button.dataset.close;
            const modal = document.getElementById(target);
            if (modal) modal.classList.add('hidden');
        });
    });
}

async function loadGuests() {
    if (!await authRequiredCheck()) return;
    const { data = [] } = await adminApi.request('/guests');
    const rows = document.getElementById('guestRows');
    rows.innerHTML = data.map((guest) => `
        <tr>
            <td>${guest.name}</td>
            <td>${guest.identity_number}</td>
            <td>${guest.phone || '-'}</td>
            <td>${guest.email || '-'}</td>
            <td>${guest.notes || '-'}</td>
            <td><button class="btn ghost small" data-edit-guest="${guest.id}">Edit</button> <button class="btn danger small" data-delete-guest="${guest.id}">Hapus</button></td>
        </tr>
    `).join('') || '<tr><td colspan="6">Belum ada data tamu.</td></tr>';
}

async function loadRooms() {
    if (!await authRequiredCheck()) return;
    const { data = [] } = await adminApi.request('/rooms');
    const rows = document.getElementById('roomRows');
    rows.innerHTML = data.map((room) => `
        <tr>
            <td>${room.code}</td>
            <td>${room.name}</td>
            <td>${room.capacity}</td>
            <td><span class="chip ${room.status === 'available' ? 'success' : room.status === 'maintenance' ? 'warning' : 'danger'}">${room.status}</span></td>
            <td><button class="btn ghost small" data-edit-room="${room.id}">Edit</button></td>
        </tr>
    `).join('') || '<tr><td colspan="5">Belum ada data kamar.</td></tr>';
}

async function loadBookings() {
    if (!await authRequiredCheck()) return;
    const { data = [] } = await adminApi.request('/bookings');
    const rows = document.getElementById('bookingRows');
    rows.innerHTML = data.map((booking) => `
        <tr>
            <td>${booking.booking_code}</td>
            <td>${booking.guest?.name || '-'}</td>
            <td>${booking.room?.code || '-'}</td>
            <td>${new Date(booking.check_in).toLocaleString('id-ID')}</td>
            <td>${new Date(booking.check_out).toLocaleString('id-ID')}</td>
            <td><span class="chip ${booking.status === 'confirmed' ? 'success' : booking.status === 'pending' ? 'warning' : booking.status === 'cancelled' ? 'danger' : 'info'}">${booking.status}</span></td>
            <td><button class="btn ghost small" data-edit-booking="${booking.id}">Edit</button> <button class="btn danger small" data-delete-booking="${booking.id}">Hapus</button></td>
        </tr>
    `).join('') || '<tr><td colspan="7">Belum ada data booking.</td></tr>';
}

async function loadUsers() {
    if (!await authRequiredCheck()) return;
    const { data = [] } = await adminApi.request('/users');
    const rows = document.getElementById('userRows');
    rows.innerHTML = data.map((user) => `
        <tr>
            <td>${user.name}</td>
            <td>${user.email}</td>
            <td><span class="chip success">${user.role}</span></td>
            <td>${new Date(user.created_at).toLocaleString('id-ID')}</td>
            <td><button class="btn ghost small" data-edit-user="${user.id}">Edit</button></td>
        </tr>
    `).join('') || '<tr><td colspan="5">Belum ada data user.</td></tr>';
}

async function initGuestsPage() {
    attachModalBehavior();
    document.getElementById('addGuestBtn')?.addEventListener('click', () => {
        document.getElementById('guestModal').classList.remove('hidden');
    });
    document.getElementById('guestForm')?.addEventListener('submit', async (event) => {
        event.preventDefault();
        const formData = Object.fromEntries(new FormData(event.target).entries());
        await adminApi.request('/guests', { method: 'POST', body: JSON.stringify(formData) });
        event.target.reset();
        document.getElementById('guestModal').classList.add('hidden');
        loadGuests();
    });
    document.getElementById('searchGuestBtn')?.addEventListener('click', async () => {
        const value = document.getElementById('guestSearch').value.trim();
        const { data = [] } = await adminApi.request(`/guests?search=${encodeURIComponent(value)}`);
        const rows = document.getElementById('guestRows');
        rows.innerHTML = data.map((guest) => `<tr><td>${guest.name}</td><td>${guest.identity_number}</td><td>${guest.phone || '-'}</td><td>${guest.email || '-'}</td><td>${guest.notes || '-'}</td><td><button class="btn ghost small" data-edit-guest="${guest.id}">Edit</button></td></tr>`).join('');
    });
    loadGuests();
}

async function initRoomsPage() {
    attachModalBehavior();
    document.getElementById('addRoomBtn')?.addEventListener('click', () => {
        document.getElementById('roomModal').classList.remove('hidden');
    });
    document.getElementById('roomForm')?.addEventListener('submit', async (event) => {
        event.preventDefault();
        const formData = Object.fromEntries(new FormData(event.target).entries());
        await adminApi.request('/rooms', { method: 'POST', body: JSON.stringify(formData) });
        event.target.reset();
        document.getElementById('roomModal').classList.add('hidden');
        loadRooms();
    });
    loadRooms();
}

async function initBookingsPage() {
    attachModalBehavior();
    document.getElementById('addBookingBtn')?.addEventListener('click', () => {
        document.getElementById('bookingModal').classList.remove('hidden');
    });
    document.getElementById('bookingForm')?.addEventListener('submit', async (event) => {
        event.preventDefault();
        const formData = Object.fromEntries(new FormData(event.target).entries());
        await adminApi.request('/bookings', { method: 'POST', body: JSON.stringify({ ...formData, guest_id: Number(formData.guest_id), room_id: Number(formData.room_id) }) });
        event.target.reset();
        document.getElementById('bookingModal').classList.add('hidden');
        loadBookings();
    });
    loadBookings();
}

async function initUsersPage() {
    attachModalBehavior();
    document.getElementById('addUserBtn')?.addEventListener('click', () => {
        document.getElementById('userModal').classList.remove('hidden');
    });
    document.getElementById('userForm')?.addEventListener('submit', async (event) => {
        event.preventDefault();
        const formData = Object.fromEntries(new FormData(event.target).entries());
        const payload = { name: formData.name, email: formData.email, role: formData.role };
        if (formData.password) payload.password = formData.password;
        await adminApi.request('/users', { method: 'POST', body: JSON.stringify(payload) });
        event.target.reset();
        document.getElementById('userModal').classList.add('hidden');
        loadUsers();
    });
    loadUsers();
}

document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.getElementById('themeToggle');
    if (toggleButton) {
        const storedTheme = localStorage.getItem('crm-theme');
        if (storedTheme === 'dark') document.body.classList.add('dark');
        toggleButton.addEventListener('click', () => {
            document.body.classList.toggle('dark');
            localStorage.setItem('crm-theme', document.body.classList.contains('dark') ? 'dark' : 'light');
            toggleButton.textContent = document.body.classList.contains('dark') ? 'Light Mode' : 'Dark Mode';
        });
    }
    if (document.getElementById('guestRows')) initGuestsPage();
    if (document.getElementById('roomRows')) initRoomsPage();
    if (document.getElementById('bookingRows')) initBookingsPage();
    if (document.getElementById('userRows')) initUsersPage();
});
