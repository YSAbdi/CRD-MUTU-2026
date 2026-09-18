const adminApi = {
    async request(path, options = {}) {
        const token = localStorage.getItem('crm_token');
        const response = await fetch(`/api/v1${path}`, { ...options, headers: { Accept:'application/json', ...(options.body ? {'Content-Type':'application/json'} : {}), ...(token ? {Authorization:`Bearer ${token}`} : {}), ...(options.headers || {}) }});
        const data = await response.json().catch(() => ({}));
        if (response.status === 401 || response.status === 403) { throw new Error(data.message || 'Anda tidak memiliki akses atau sesi telah berakhir.'); }
        if (!response.ok) throw new Error(data.message || Object.values(data.errors || {}).flat().join(' ') || 'Permintaan gagal.');
        return data;
    }
};
const escapeHtml = (value) => String(value ?? '').replace(/[&<>'"]/g, (c) => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[c]));
const formPayload = (form) => Object.fromEntries(new FormData(form).entries());
const openModal = (id) => document.getElementById(id)?.classList.remove('hidden');
const closeModal = (id) => document.getElementById(id)?.classList.add('hidden');

async function loadGuests() { const result = await adminApi.request(`/guests?search=${encodeURIComponent(document.getElementById('guestSearch')?.value || '')}`); document.getElementById('guestRows').innerHTML = (result.data || []).map(g => `<tr><td>${escapeHtml(g.name)}</td><td>${escapeHtml(g.identity_number)}</td><td>${escapeHtml(g.phone || '-')}</td><td>${escapeHtml(g.email || '-')}</td><td>${escapeHtml(g.notes || '-')}</td><td><button class="btn danger small" data-delete-guest="${g.id}">Hapus</button></td></tr>`).join('') || '<tr><td colspan="6">Belum ada data tamu.</td></tr>'; }
async function loadRooms() { const result = await adminApi.request(`/rooms?search=${encodeURIComponent(document.getElementById('roomSearch')?.value || '')}`); document.getElementById('roomRows').innerHTML = (result.data || []).map(r => `<tr><td>${escapeHtml(r.code)}</td><td>${escapeHtml(r.name)}</td><td>${r.capacity}</td><td><span class="chip ${r.status === 'available' ? 'success' : r.status === 'maintenance' ? 'warning' : 'danger'}">${escapeHtml(r.status)}</span></td><td></td></tr>`).join('') || '<tr><td colspan="5">Belum ada data kamar.</td></tr>'; }
async function loadBookings() { const result = await adminApi.request('/bookings'); document.getElementById('bookingRows').innerHTML = (result.data || []).map(b => `<tr><td>${escapeHtml(b.booking_code)}</td><td>${escapeHtml(b.guest?.name || '-')}</td><td>${escapeHtml(b.room?.code || '-')}</td><td>${new Date(b.check_in).toLocaleString('id-ID')}</td><td>${new Date(b.check_out).toLocaleString('id-ID')}</td><td><span class="chip info">${escapeHtml(b.status)}</span></td><td><button class="btn danger small" data-delete-booking="${b.id}">Hapus</button></td></tr>`).join('') || '<tr><td colspan="7">Belum ada data booking.</td></tr>'; }
async function loadUsers() { const result = await adminApi.request('/users'); document.getElementById('userRows').innerHTML = (result.data || []).map(u => `<tr><td>${escapeHtml(u.name)}</td><td>${escapeHtml(u.email)}</td><td><span class="chip success">${escapeHtml(u.role)}</span></td><td>${new Date(u.created_at).toLocaleString('id-ID')}</td><td><button class="btn danger small" data-delete-user="${u.id}">Hapus</button></td></tr>`).join('') || '<tr><td colspan="5">Belum ada data user.</td></tr>'; }

async function bindPage() {
    document.querySelectorAll('.close-modal').forEach(b => b.onclick = () => closeModal(b.dataset.close));
    document.getElementById('addGuestBtn')?.addEventListener('click', () => openModal('guestModal'));
    document.getElementById('addRoomBtn')?.addEventListener('click', () => openModal('roomModal'));
    document.getElementById('addBookingBtn')?.addEventListener('click', () => openModal('bookingModal'));
    document.getElementById('addUserBtn')?.addEventListener('click', () => openModal('userModal'));
    document.getElementById('searchGuestBtn')?.addEventListener('click', loadGuests); document.getElementById('searchRoomBtn')?.addEventListener('click', loadRooms);
    document.getElementById('guestForm')?.addEventListener('submit', async e => { e.preventDefault(); try { await adminApi.request('/guests',{method:'POST',body:JSON.stringify(formPayload(e.target))}); closeModal('guestModal'); e.target.reset(); await loadGuests(); } catch(x) { alert(x.message); } });
    document.getElementById('roomForm')?.addEventListener('submit', async e => { e.preventDefault(); try { const p=formPayload(e.target); p.capacity=Number(p.capacity); await adminApi.request('/rooms',{method:'POST',body:JSON.stringify(p)}); closeModal('roomModal'); e.target.reset(); await loadRooms(); } catch(x) { alert(x.message); } });
    document.getElementById('bookingForm')?.addEventListener('submit', async e => { e.preventDefault(); try { const p=formPayload(e.target); p.guest_id=Number(p.guest_id); p.room_id=Number(p.room_id); delete p.status; await adminApi.request('/bookings',{method:'POST',body:JSON.stringify(p)}); closeModal('bookingModal'); e.target.reset(); await loadBookings(); } catch(x) { alert(x.message); } });
    document.getElementById('userForm')?.addEventListener('submit', async e => { e.preventDefault(); try { const p=formPayload(e.target); p.password_confirmation=p.password; await adminApi.request('/users',{method:'POST',body:JSON.stringify(p)}); closeModal('userModal'); e.target.reset(); await loadUsers(); } catch(x) { alert(x.message); } });
    document.addEventListener('click', async e => { const guest=e.target.closest('[data-delete-guest]'), booking=e.target.closest('[data-delete-booking]'), user=e.target.closest('[data-delete-user]'); try { if(guest && confirm('Hapus tamu ini?')) { await adminApi.request(`/guests/${guest.dataset.deleteGuest}`,{method:'DELETE'}); await loadGuests(); } if(booking && confirm('Hapus booking ini?')) { await adminApi.request(`/bookings/${booking.dataset.deleteBooking}`,{method:'DELETE'}); await loadBookings(); } if(user && confirm('Hapus user ini?')) { await adminApi.request(`/users/${user.dataset.deleteUser}`,{method:'DELETE'}); await loadUsers(); } } catch(x) { alert(x.message); } });
    if(document.getElementById('guestRows')) await loadGuests(); if(document.getElementById('roomRows')) await loadRooms(); if(document.getElementById('bookingRows')) await loadBookings(); if(document.getElementById('userRows')) await loadUsers();
}
document.addEventListener('DOMContentLoaded', bindPage);
