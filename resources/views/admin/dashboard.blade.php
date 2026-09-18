<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"><title>Admin Dashboard | CRM Camp Resident MUTU</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<div id="loginPanel" class="auth-panel">
    <div class="auth-card"><div class="brand-mark">M</div><h1>CRM Camp Resident MUTU</h1><p class="muted">Masuk ke dashboard administrasi</p>
        <form id="adminLoginForm"><input id="loginEmail" type="email" placeholder="Email" required><input id="loginPassword" type="password" placeholder="Password" required><button class="btn primary" type="submit">Masuk</button><p id="loginError" class="error"></p></form>
    </div>
</div>
<div id="dashboardApp" class="app-shell hidden">
    <aside class="sidebar"><div class="brand"><div class="brand-mark">M</div><div><strong>Camp Resident</strong><small>MUTU</small></div></div>
        <nav class="nav"><a class="nav-item active" href="{{ route('admin.dashboard') }}">Dashboard</a><a class="nav-item" href="#guests">Tamu</a><a class="nav-item" href="#rooms">Kamar</a><a class="nav-item" href="#bookings">Booking</a><a class="nav-item" href="#calendar">Kalender</a><a class="nav-item" href="/api/v1/reports/bookings?format=xlsx">Laporan</a></nav>
        <div class="sidebar-card"><span class="chip success">Online</span><strong>Operasional Aktif</strong><p>Data dashboard diambil langsung dari database.</p></div>
    </aside>
    <main class="main-panel"><header class="topbar"><div><p class="eyebrow">Overview</p><h1>Admin Dashboard</h1><p id="lastUpdated" class="muted">Memuat data...</p></div><div class="topbar-actions"><button class="btn secondary" id="themeToggle">Dark Mode</button><button class="btn secondary" id="refreshButton">Refresh</button><button class="btn danger" id="logoutButton">Keluar</button></div></header>
        <section id="statsGrid" class="stats-grid"></section>
        <section class="content-grid"><article class="panel panel-wide"><div class="panel-header"><h3>Aktivitas Booking</h3><button class="btn ghost" id="loadMoreBookings">Muat booking</button></div><div class="table-wrap"><table><thead><tr><th>Kode</th><th>Tamu</th><th>Kamar</th><th>Check-in</th><th>Status</th></tr></thead><tbody id="bookingRows"><tr><td colspan="5">Memuat...</td></tr></tbody></table></div></article>
            <article class="panel"><div class="panel-header"><h3>Ringkasan Operasional</h3></div><div id="operationalMetrics"></div></article></section>
        <section class="bottom-grid"><article id="calendar" class="panel"><div class="panel-header"><h3>Kalender Booking</h3><button class="btn ghost" id="calendarRefresh">Refresh</button></div><div id="calendarEvents" class="notification-list"><li>Memuat kalender...</li></div></article>
            <article class="panel"><div class="panel-header"><h3>Notifikasi & Reminder</h3></div><ul id="notifications" class="notification-list"><li><span class="dot info"></span><div><strong>Data tersinkronisasi</strong><small>Notifikasi booking akan muncul di sini.</small></div></li></ul></article></section>
    </main>
</div><script src="{{ asset('js/admin.js') }}"></script></body></html>
