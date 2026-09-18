<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Booking | CRM Camp Resident MUTU</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<div id="appShell" class="app-shell">
    <aside class="sidebar">
        <div class="brand"><div class="brand-mark">M</div><div><strong>Camp Resident</strong><small>MUTU</small></div></div>
        <nav class="nav"><a class="nav-item" href="{{ route('admin.dashboard') }}">Dashboard</a><a class="nav-item" href="{{ route('admin.guests') }}">Tamu</a><a class="nav-item" href="{{ route('admin.rooms') }}">Kamar</a><a class="nav-item active" href="{{ route('admin.bookings') }}">Booking</a><a class="nav-item" href="{{ route('admin.users') }}">User</a></nav>
        <div class="sidebar-card"><span class="chip success">Booking</span><strong>Status Reservasi</strong><p>Kelola pemesanan, konfirmasi, check-in, dan check-out.</p></div>
    </aside>
    <main class="main-panel">
        <header class="topbar"><div><p class="eyebrow">Operasional</p><h1>Kelola Booking</h1></div><div class="topbar-actions"><button class="btn secondary" id="themeToggle">Dark Mode</button><button class="btn primary" id="addBookingBtn">+ Tambah Booking</button></div></header>
        <section class="panel"><div class="panel-header"><h3>Daftar Booking</h3><a class="btn ghost" href="/api/v1/reports/bookings?format=xlsx">Export Excel</a></div><div class="toolbar"><input id="bookingSearch" type="text" placeholder="Cari kode booking / nama tamu"><button class="btn secondary" id="searchBookingBtn">Cari</button></div><div class="table-wrap"><table><thead><tr><th>Kode</th><th>Tamu</th><th>Kamar</th><th>Check-in</th><th>Check-out</th><th>Status</th><th>Aksi</th></tr></thead><tbody id="bookingRows"></tbody></table></div></section>
    </main>
</div>
<div id="bookingModal" class="modal hidden"><div class="modal-card"><div class="panel-header"><h3>Form Booking</h3><button class="btn ghost close-modal" data-close="bookingModal">Tutup</button></div><form id="bookingForm"><input name="guest_id" type="number" placeholder="ID Tamu" required><input name="room_id" type="number" placeholder="ID Kamar" required><input name="check_in" type="datetime-local" required><input name="check_out" type="datetime-local" required><textarea name="notes" placeholder="Catatan"></textarea><select name="status"><option value="pending">Pending</option><option value="confirmed">Confirmed</option><option value="checked_in">Checked In</option><option value="checked_out">Checked Out</option><option value="cancelled">Cancelled</option></select><button class="btn primary" type="submit">Simpan</button></form></div></div>
<script src="{{ asset('js/admin.js') }}"></script>
<script src="{{ asset('js/admin-crud.js') }}"></script>
</body>
</html>
