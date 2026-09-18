<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User | CRM Camp Resident MUTU</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<div id="appShell" class="app-shell">
    <aside class="sidebar">
        <div class="brand"><div class="brand-mark">M</div><div><strong>Camp Resident</strong><small>MUTU</small></div></div>
        <nav class="nav"><a class="nav-item" href="{{ route('admin.dashboard') }}">Dashboard</a><a class="nav-item" href="{{ route('admin.guests') }}">Tamu</a><a class="nav-item" href="{{ route('admin.rooms') }}">Kamar</a><a class="nav-item" href="{{ route('admin.bookings') }}">Booking</a><a class="nav-item active" href="{{ route('admin.users') }}">User</a></nav>
        <div class="sidebar-card"><span class="chip success">Superadmin</span><strong>Manajemen Akses</strong><p>Atur role user, reset password, dan kontrol akses seluruh pengguna.</p></div>
    </aside>
    <main class="main-panel">
        <header class="topbar"><div><p class="eyebrow">Akses</p><h1>Kelola User</h1></div><div class="topbar-actions"><button class="btn secondary" id="themeToggle">Dark Mode</button><button class="btn primary" id="addUserBtn">+ Tambah User</button></div></header>
        <section class="panel"><div class="panel-header"><h3>Daftar User</h3></div><div class="table-wrap"><table><thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Waktu Dibuat</th><th>Aksi</th></tr></thead><tbody id="userRows"></tbody></table></div></section>
    </main>
</div>
<div id="userModal" class="modal hidden"><div class="modal-card"><div class="panel-header"><h3>Form User</h3><button class="btn ghost close-modal" data-close="userModal">Tutup</button></div><form id="userForm"><input name="name" placeholder="Nama lengkap" required><input name="email" type="email" placeholder="Email" required><select name="role"><option value="superadmin">Superadmin</option><option value="admin">Admin</option><option value="operator">Operator</option><option value="viewer">Viewer</option></select><input name="password" type="password" placeholder="Password baru (opsional)"><button class="btn primary" type="submit">Simpan</button></form></div></div>
<script src="{{ asset('js/admin.js') }}"></script>
<script src="{{ asset('js/admin-crud.js') }}"></script>
</body>
</html>
