<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | CRM Camp Resident MUTU</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-mark">M</div>
                <div>
                    <strong>Camp Resident</strong>
                    <small>MUTU</small>
                </div>
            </div>

            <nav class="nav">
                <a class="nav-item active" href="#">Dashboard</a>
                <a class="nav-item" href="#">Tamu</a>
                <a class="nav-item" href="#">Kamar</a>
                <a class="nav-item" href="#">Booking</a>
                <a class="nav-item" href="#">Kalender</a>
                <a class="nav-item" href="#">Laporan</a>
                <a class="nav-item" href="#">Pengaturan</a>
            </nav>

            <div class="sidebar-card">
                <span class="chip success">Online</span>
                <strong>Operasional Aktif</strong>
                <p>Semua sistem berjalan stabil dan siap melayani tamu.</p>
            </div>
        </aside>

        <main class="main-panel">
            <header class="topbar">
                <div>
                    <p class="eyebrow">Overview</p>
                    <h1>Admin Dashboard</h1>
                </div>

                <div class="topbar-actions">
                    <button class="btn secondary" id="themeToggle">Dark Mode</button>
                    <button class="btn primary">+ Booking Baru</button>
                </div>
            </header>

            <section class="stats-grid">
                <article class="stat-card accent-teal">
                    <div class="stat-top">
                        <span>Total Booking</span>
                        <span class="badge info">Hari ini</span>
                    </div>
                    <h2>248</h2>
                    <small>+18.4% vs kemarin</small>
                </article>

                <article class="stat-card accent-blue">
                    <div class="stat-top">
                        <span>Tamu Check-in</span>
                        <span class="badge success">Aktif</span>
                    </div>
                    <h2>96</h2>
                    <small>28 tamu menunggu konfirmasi</small>
                </article>

                <article class="stat-card accent-gold">
                    <div class="stat-top">
                        <span>Kamar Tersedia</span>
                        <span class="badge warn">Prioritas</span>
                    </div>
                    <h2>24</h2>
                    <small>6 kamar siap untuk booking cepat</small>
                </article>

                <article class="stat-card accent-rose">
                    <div class="stat-top">
                        <span>Pending</span>
                        <span class="badge danger">Perhatian</span>
                    </div>
                    <h2>13</h2>
                    <small>3 request butuh konfirmasi manual</small>
                </article>
            </section>

            <section class="content-grid">
                <article class="panel panel-wide">
                    <div class="panel-header">
                        <h3>Aktivitas Booking</h3>
                        <button class="btn ghost">Lihat Semua</button>
                    </div>

                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Tamu</th>
                                    <th>Kamar</th>
                                    <th>Check-in</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>MUTU-1832</td>
                                    <td>Rizki Pratama</td>
                                    <td>A-101</td>
                                    <td>18 Sep 2026</td>
                                    <td><span class="chip success">Confirmed</span></td>
                                </tr>
                                <tr>
                                    <td>MUTU-1823</td>
                                    <td>Jihan Safira</td>
                                    <td>B-205</td>
                                    <td>18 Sep 2026</td>
                                    <td><span class="chip warning">Pending</span></td>
                                </tr>
                                <tr>
                                    <td>MUTU-1818</td>
                                    <td>Andi Putra</td>
                                    <td>C-304</td>
                                    <td>19 Sep 2026</td>
                                    <td><span class="chip info">Checked-in</span></td>
                                </tr>
                                <tr>
                                    <td>MUTU-1809</td>
                                    <td>Salsabila</td>
                                    <td>A-120</td>
                                    <td>20 Sep 2026</td>
                                    <td><span class="chip danger">Cancelled</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </article>

                <article class="panel">
                    <div class="panel-header">
                        <h3>Ringkasan Operasional</h3>
                    </div>

                    <div class="mini-metric">
                        <div>
                            <span>Occupancy</span>
                            <strong>76%</strong>
                        </div>
                        <div class="progress"><span style="width: 76%"></span></div>
                    </div>

                    <div class="mini-metric">
                        <div>
                            <span>Customer Satisfaction</span>
                            <strong>94%</strong>
                        </div>
                        <div class="progress"><span style="width: 94%"></span></div>
                    </div>

                    <div class="mini-metric">
                        <div>
                            <span>Check-out On Time</span>
                            <strong>89%</strong>
                        </div>
                        <div class="progress"><span style="width: 89%"></span></div>
                    </div>
                </article>
            </section>

            <section class="bottom-grid">
                <article class="panel">
                    <div class="panel-header">
                        <h3>Kalender Booking</h3>
                    </div>
                    <div class="calendar">
                        <div class="calendar-head">
                            <span>Sep 2026</span>
                            <div class="calendar-nav">
                                <button>‹</button>
                                <button>›</button>
                            </div>
                        </div>
                        <div class="calendar-grid">
                            <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
                            <button class="day muted">30</button>
                            <button class="day">1</button>
                            <button class="day">2</button>
                            <button class="day">3</button>
                            <button class="day">4</button>
                            <button class="day">5</button>
                            <button class="day">6</button>
                            <button class="day">7</button>
                            <button class="day">8</button>
                            <button class="day active">9</button>
                            <button class="day">10</button>
                            <button class="day">11</button>
                            <button class="day">12</button>
                            <button class="day">13</button>
                            <button class="day">14</button>
                            <button class="day">15</button>
                            <button class="day">16</button>
                            <button class="day">17</button>
                            <button class="day">18</button>
                            <button class="day">19</button>
                            <button class="day">20</button>
                            <button class="day">21</button>
                            <button class="day">22</button>
                            <button class="day">23</button>
                            <button class="day">24</button>
                            <button class="day">25</button>
                            <button class="day">26</button>
                            <button class="day">27</button>
                            <button class="day">28</button>
                            <button class="day">29</button>
                            <button class="day">30</button>
                            <button class="day muted">1</button>
                        </div>
                    </div>
                </article>

                <article class="panel">
                    <div class="panel-header">
                        <h3>Notifikasi & Reminder</h3>
                    </div>

                    <ul class="notification-list">
                        <li>
                            <span class="dot success"></span>
                            <div>
                                <strong>Konfirmasi booking diterima</strong>
                                <small>2 menit yang lalu</small>
                            </div>
                        </li>
                        <li>
                            <span class="dot warning"></span>
                            <div>
                                <strong>Pengingat check-out</strong>
                                <small>Jam 9:00</small>
                            </div>
                        </li>
                        <li>
                            <span class="dot info"></span>
                            <div>
                                <strong>Review tamu baru</strong>
                                <small>1 jam yang lalu</small>
                            </div>
                        </li>
                    </ul>
                </article>
            </section>
        </main>
    </div>

    <script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>
