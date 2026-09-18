const adminApi = {
    async request(path, options = {}) {
        const response = await fetch(`/api/v1${path}`, { ...options, headers: { Accept:'application/json', ...(options.body ? {'Content-Type':'application/json'} : {}), ...(localStorage.getItem('crm_token') ? {Authorization:`Bearer ${localStorage.getItem('crm_token')}`} : {}), ...(options.headers || {}) }});
        const data = await response.json().catch(() => ({}));
        if (response.status === 401) { localStorage.removeItem('crm_token'); window.location.href = '/admin/dashboard'; throw new Error('Sesi berakhir.'); }
        if (!response.ok) throw new Error(data.message || Object.values(data.errors || {}).flat().join(' ') || 'Permintaan gagal.');
        return data;
    }
};
const escapeHtml = (value) => String(value ?? '').replace(/[&<>'"]/g, (c) => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[c]));
const payload = (form) => Object.fromEntries(new FormData(form).entries());
const openModal = (id) => document.getElementById(id)?.classList.remove('hidden');
const closeModal = (id) => document.getElementById(id)?.classList.add('hidden');
const formMode = (form, id = null) => { form.dataset.id = id || ''; const button = form.querySelector('[type=submit]'); if (button) button.textContent = id ? 'Simpan perubahan' : 'Simpan'; };

async function loadGuests() {
    const result = await adminApi.request(`/guests?search=${encodeURIComponent(document.getElementById('guestSearch')?.value || '')}`);
    document.getElementById('guestRows').innerHTML = (result.data || []).map(g => `<tr><td>${escapeHtml(g.name)}</td><td>${escapeHtml(g.identity_number)}</td><td>${escapeHtml(g.phone || '-')}</td><td>${escapeHtml(g.email || '-')}</td><td>${escapeHtml(g.notes || '-')}</td><td><button class="btn ghost small" data-edit-guest='${JSON.stringify(g).replace(/'/g, '&#39;')}'>Edit</button> <button class="btn danger small" data-delete-guest="${g.id}">Hapus</button></td></tr>`).join('') || '<tr><td colspan="6">Belum ada data tamu.</td></tr>';
}
async function loadRooms() {
    const result = await adminApi.request(`/rooms?search=${encodeURIComponent(document.getElementById('roomSearch')?.value || '')}`);
    document.getElementById('roomRows').innerHTML = (result.data || []).map(r => `<tr><td>${escapeHtml(r.code)}</td><td>${escapeHtml(r.name)}</td><td>${r.capacity}</td><td><span class="chip ${r.status === 'available' ? 'success' : r.status === 'maintenance' ? 'warning' : 'danger'}">${escapeHtml(r.status)}</span></td><td><button class="btn ghost small" data-edit-room='${JSON.stringify(r).replace(/'/g, '&#39;')}'>Edit</button></td></tr>`).join('') || '<tr><td colspan="5">Belum ada data kamar.</td></tr>';
}
async function loadBookings() {
    const result = await adminApi.request('/bookings');
    document.getElementById('bookingRows').innerHTML = (result.data || []).map(b => `<tr><td>${escapeHtml(b.booking_code)}</td><td>${escapeHtml(b.guest?.name || '-')}</td><td>${escapeHtml(b.room?.code || '-')}</td><td>${new Date(b.check_in).toLocaleString('id-ID')}</td><td>${new Date(b.check_out).toLocaleString('id-ID')}</td><td><span class="chip info">${escapeHtml(b.status)}</span></td><td><button class="btn ghost small" data-edit-booking='${JSON.stringify(b).replace(/'/g, '&#39;')}'>Edit</button> <button class="btn danger small" data-delete-booking="${b.id}">Hapus</button></td></tr>`).join('') || '<tr><td colspan="7">Belum ada data booking.</td></tr>';
}
async function loadUsers() {
    const result = await adminApi.request(`/users?search=${encodeURIComponent(document.getElementById('userSearch')?.value || '')}`);
    document.getElementById('userRows').innerHTML = (result.data || []).map(u => `<tr><td>${escapeHtml(u.name)}</td><td>${escapeHtml(u.email)}</td><td><span class="chip success">${escapeHtml(u.role)}</span></td><td>${new Date(u.created_at).toLocaleString('id-ID')}</td><td><button class="btn ghost small" data-edit-user='${JSON.stringify(u).replace(/'/g, '&#39;')}'>Edit / Reset Password</button> <button class="btn danger small" data-delete-user="${u.id}">Hapus</button></td></tr>`).join('') || '<tr><td colspan="5">Belum ada data user.</td></tr>';
}
function readData(button) { return JSON.parse(button.getAttribute(button.dataset.editGuest ? 'data-edit-guest' : button.dataset.editRoom ? 'data-edit-room' : button.dataset.editBooking ? 'data-edit-booking' : 'data-edit-user').replace(/&#39;/g, "'")); }

document.addEventListener('DOMContentLoaded', async () => {
    if (!localStorage.getItem('crm_token')) { window.location.href = '/admin/dashboard'; return; }
    document.querySelectorAll('.close-modal').forEach(b => b.onclick = () => closeModal(b.dataset.close));
    document.getElementById('addGuestBtn')?.addEventListener('click', () => { const f=document.getElementById('guestForm'); f.reset(); formMode(f); openModal('guestModal'); });
    document.getElementById('addRoomBtn')?.addEventListener('click', () => { const f=document.getElementById('roomForm'); f.reset(); formMode(f); openModal('roomModal'); });
    document.getElementById('addBookingBtn')?.addEventListener('click', () => { const f=document.getElementById('bookingForm'); f.reset(); formMode(f); openModal('bookingModal'); });
    document.getElementById('addUserBtn')?.addEventListener('click', () => { const f=document.getElementById('userForm'); f.reset(); formMode(f); openModal('userModal'); });
    document.getElementById('searchGuestBtn')?.addEventListener('click', loadGuests); document.getElementById('searchRoomBtn')?.addEventListener('click', loadRooms); document.getElementById('searchUserBtn')?.addEventListener('click', loadUsers);
    document.getElementById('guestForm')?.addEventListener('submit', async e => { e.preventDefault(); try { const id=e.target.dataset.id; await adminApi.request(id ? `/guests/${id}` : '/guests',{method:id?'PUT':'POST',body:JSON.stringify(payload(e.target))}); closeModal('guestModal'); e.target.reset(); await loadGuests(); } catch(x) { alert(x.message); } });
    document.getElementById('roomForm')?.addEventListener('submit', async e => { e.preventDefault(); try { const p=payload(e.target); p.capacity=Number(p.capacity); const id=e.target.dataset.id; await adminApi.request(id?`/rooms/${id}`:'/rooms',{method:id?'PUT':'POST',body:JSON.stringify(p)}); closeModal('roomModal'); e.target.reset(); await loadRooms(); } catch(x) { alert(x.message); } });
    document.getElementById('bookingForm')?.addEventListener('submit', async e => { e.preventDefault(); try { const p=payload(e.target); p.guest_id=Number(p.guest_id); p.room_id=Number(p.room_id); const id=e.target.dataset.id; await adminApi.request(id?`/bookings/${id}`:'/bookings',{method:id?'PUT':'POST',body:JSON.stringify(p)}); closeModal('bookingModal'); e.target.reset(); await loadBookings(); } catch(x) { alert(x.message); } });
    document.getElementById('userForm')?.addEventListener('submit', async e => { e.preventDefault(); try { const p=payload(e.target); p.password_confirmation=p.password; const id=e.target.dataset.id; await adminApi.request(id?`/users/${id}`:'/users',{method:id?'PUT':'POST',body:JSON.stringify(p)}); closeModal('userModal'); e.target.reset(); await loadUsers(); } catch(x) { alert(x.message); } });
    document.addEventListener('click', async e => {
        const guest=e.target.closest('[data-delete-guest]'), room=e.target.closest('[data-edit-room]'), booking=e.target.closest('[data-delete-booking]'), user=e.target.closest('[data-delete-user]'), editGuest=e.target.closest('[data-edit-guest]'), editBooking=e.target.closest('[data-edit-booking]'), editUser=e.target.closest('[data-edit-user]');
        try {
            if (editGuest) { const d=readData(editGuest), f=document.getElementById('guestForm'); Object.entries(d).forEach(([k,v])=>{if(f.elements[k])f.elements[k].value=v||''}); formMode(f,d.id); openModal('guestModal'); }
            if (room) { const d=readData(room), f=document.getElementById('roomForm'); Object.entries(d).forEach(([k,v])=>{if(f.elements[k])f.elements[k].value=v||''}); formMode(f,d.id); openModal('roomModal'); }
            if (editBooking) { const d=readData(editBooking), f=document.getElementById('bookingForm'); f.elements.guest_id.value=d.guest_id; f.elements.room_id.value=d.room_id; f.elements.check_in.value=d.check_in?.slice(0,16); f.elements.check_out.value=d.check_out?.slice(0,16); f.elements.notes.value=d.notes||''; f.elements.status.value=d.status; formMode(f,d.id); openModal('bookingModal'); }
            if (editUser) { const d=readData(editUser), f=document.getElementById('userForm'); f.elements.name.value=d.name; f.elements.email.value=d.email; f.elements.role.value=d.role; formMode(f,d.id); openModal('userModal'); }
            if (guest && confirm('Hapus tamu ini?')) { await adminApi.request(`/guests/${guest.dataset.deleteGuest}`,{method:'DELETE'}); await loadGuests(); }
            if (booking && confirm('Hapus booking ini?')) { await adminApi.request(`/bookings/${booking.dataset.deleteBooking}`,{method:'DELETE'}); await loadBookings(); }
            if (user && confirm('Hapus user ini?')) { await adminApi.request(`/users/${user.dataset.deleteUser}`,{method:'DELETE'}); await loadUsers(); }
        } catch(x) { alert(x.message); }
    });
    if(document.getElementById('guestRows')) await loadGuests(); if(document.getElementById('roomRows')) await loadRooms(); if(document.getElementById('bookingRows')) await loadBookings(); if(document.getElementById('userRows')) await loadUsers();
});
