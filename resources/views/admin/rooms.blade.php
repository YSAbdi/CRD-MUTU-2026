<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kamar | CRM Camp Resident MUTU</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<div id="appShell" class="app-shell">
    <aside class="sidebar">
        <div class="brand"><div class="brand-mark">M</div><div><strong>Camp Resident</strong><small>MUTU</small></div></div>
        <nav class="nav"><a class="nav-item" href="{{ route('admin.dashboard') }}">Dashboard</a><a class="nav-item" href="{{ route('admin.guests') }}">Tamu</a><a class="nav-item active" href="{{ route('admin.rooms') }}">Kamar</a><a class="nav-item" href="{{ route('admin.bookings') }}">Booking</a><a class="nav-item" href="{{ route('admin.users') }}">User</a></nav>
        <div class="sidebar-card"><span class="chip success">Kamar</span><strong>Inventaris Kamar</strong><p>Kelola status, kapasitas, dan ketersediaan kamar.</p></div>
    </aside>
    <main class="main-panel">
        <header class="topbar"><div><p class="eyebrow">Master Data</p><h1>Kelola Kamar</h1></div><div class="topbar-actions"><button class="btn secondary" id="themeToggle">Dark Mode</button><button class="btn primary" id="addRoomBtn">+ Tambah Kamar</button></div></header>
        <section class="panel"><div class="panel-header"><h3>Daftar Kamar</h3></div><div class="toolbar"><input id="roomSearch" type="text" placeholder="Cari kode / nama kamar"><button class="btn secondary" id="searchRoomBtn">Cari</button></div><div class="table-wrap"><table><thead><tr><th>Kode</th><th>Nama</th><th>Kapasitas</th><th>Status</th><th>Aksi</th></tr></thead><tbody id="roomRows"></tbody></table></div></section>
    </main>
</div>
<div id="roomModal" class="modal hidden"><div class="modal-card"><div class="panel-header"><h3>Form Kamar</h3><button class="btn ghost close-modal" data-close="roomModal">Tutup</button></div><form id="roomForm"><input name="code" placeholder="Kode kamar" required><input name="name" placeholder="Nama kamar" required><input name="capacity" type="number" min="1" value="1" required><select name="status"><option value="available">Tersedia</option><option value="maintenance">Pemeliharaan</option><option value="inactive">Nonaktif</option></select><button class="btn primary" type="submit">Simpan</button></form></div></div>
<script src="{{ asset('js/admin.js') }}"></script>
<script src="{{ asset('js/admin-crud.js') }}"></script>
</body>
</html>
