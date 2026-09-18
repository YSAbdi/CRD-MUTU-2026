<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Tamu | CRM Camp Resident MUTU</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<div id="loginPanel" class="auth-panel hidden">
    <div class="auth-card">
        <div class="brand-mark">M</div>
        <h1>CRM Camp Resident MUTU</h1>
        <p class="muted">Masuk untuk mengelola data operasional</p>
        <form id="adminLoginForm">
            <input id="loginEmail" type="email" placeholder="Email" required>
            <input id="loginPassword" type="password" placeholder="Password" required>
            <button class="btn primary" type="submit">Masuk</button>
            <p id="loginError" class="error"></p>
        </form>
    </div>
</div>
<div id="appShell" class="app-shell">
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-mark">M</div>
            <div><strong>Camp Resident</strong><small>MUTU</small></div>
        </div>
        <nav class="nav">
            <a class="nav-item" href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a class="nav-item active" href="{{ route('admin.guests') }}">Tamu</a>
            <a class="nav-item" href="{{ route('admin.rooms') }}">Kamar</a>
            <a class="nav-item" href="{{ route('admin.bookings') }}">Booking</a>
            <a class="nav-item" href="{{ route('admin.users') }}">User</a>
        </nav>
        <div class="sidebar-card"><span class="chip success">Online</span><strong>Data Tamu</strong><p>Kelola profil tamu dan riwayat reservasi.</p></div>
    </aside>
    <main class="main-panel">
        <header class="topbar"><div><p class="eyebrow">Master Data</p><h1>Kelola Tamu</h1></div><div class="topbar-actions"><button class="btn secondary" id="themeToggle">Dark Mode</button><button class="btn primary" id="addGuestBtn">+ Tambah Tamu</button></div></header>
        <section class="panel">
            <div class="panel-header"><h3>Daftar Tamu</h3></div>
            <div class="toolbar"><input id="guestSearch" type="text" placeholder="Cari nama / identitas / telepon"><button class="btn secondary" id="searchGuestBtn">Cari</button></div>
            <div class="table-wrap"><table><thead><tr><th>Nama</th><th>Identitas</th><th>Telepon</th><th>Email</th><th>Catatan</th><th>Aksi</th></tr></thead><tbody id="guestRows"></tbody></table></div>
        </section>
    </main>
</div>
<div id="guestModal" class="modal hidden"><div class="modal-card"><div class="panel-header"><h3>Form Tamu</h3><button class="btn ghost close-modal" data-close="guestModal">Tutup</button></div><form id="guestForm"><input name="name" placeholder="Nama lengkap" required><input name="identity_number" placeholder="Nomor identitas" required><input name="phone" placeholder="Telepon"><input name="email" type="email" placeholder="Email"><textarea name="notes" placeholder="Catatan"></textarea><button class="btn primary" type="submit">Simpan</button></form></div></div>
<script src="{{ asset('js/admin.js') }}"></script>
<script src="{{ asset('js/admin-crud.js') }}"></script>
</body>
</html>
