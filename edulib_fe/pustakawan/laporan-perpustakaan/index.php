<?php
require_once("../../config/global.php");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Perpustakaan - EDULIB</title>
        <link rel="stylesheet" href=<?= BASE_LINK . 'libs/css/bootstrap.css' ?>>
    <link rel="stylesheet" href=<?= BASE_LINK . 'libs/css/spin.css' ?>>
    <link rel="stylesheet" href=<?= BASE_LINK . 'static/pustakawanbuku.css' ?>>
    <script src=<?= BASE_LINK . 'libs/js/jquery.js' ?>></script>
    <script src=<?= BASE_LINK . "static/cekLoginPustaka.js" ?>></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- === [ BAGIAN CSS LENGKAP ] === -->
    <style>
        /* pustakawan_style.css */

        /* --- Variabel & Gaya Dasar --- */
        :root {
            --pustaka-primary: #C0392B; /* Merah yang lebih modern dari #b23b3b */
            --pustaka-primary-dark: #A93226;
            --pustaka-bg: #F9F9F9;
            --pustaka-card-bg: #FFFFFF;
            --pustaka-font-dark: #2C3E50;
            --pustaka-font-light: #808B96;
            --pustaka-border: #EAEDED;
            --shadow: 0 4px 15px rgba(0, 0, 0, 0.07);
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: var(--pustaka-bg);
            color: var(--pustaka-font-dark);
        }

        /* --- Layout Utama --- */
        .container-p {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: var(--pustaka-primary);
            color: white;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: fixed;
            height: 100%;
        }

        .main-content {
            margin-left: 250px;
            flex-grow: 1;
            padding: 2rem 3rem;
            overflow-y: auto;
        }

        /* --- Komponen Sidebar --- */
        .sidebar h2 {
            font-family: 'EB Garamond', serif;
            font-size: 2rem;
            text-align: center;
            margin-bottom: 2rem;
            color: white;
        }
        .sidebar-nav a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .sidebar-nav a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        .sidebar-nav a.active {
            background-color: var(--pustaka-card-bg);
            color: var(--pustaka-primary);
            font-weight: 700;
        }
        .logout-btn {
            margin-top: auto;
            background-color: rgba(0,0,0,0.2);
            text-align: center;
            padding: 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .logout-btn:hover { background-color: rgba(0,0,0,0.4); }

        /* --- Komponen Konten --- */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .page-header h1 {
            font-size: 2.25rem;
            color: var(--pustaka-font-dark);
        }
        
        /* Layout untuk Chart */
        .content-card {
            background-color: var(--pustaka-card-bg);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }
         .content-card h3 {
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: 600;
        }
        .chart-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        @media (min-width: 1024px) {
            .chart-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="container-p">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>EDULIB</h2>
            <nav class="sidebar-nav">
                <a href="../buku/index.php">Buku</a>
                <a href="../peminjaman-buku/index.php">Peminjaman Buku</a>
                <a href="../pengembalian-buku/index.php">Pengembalian Buku</a>
                <a href="index.php" class="active">Laporan Perpustakaan</a>
            </nav>
            <div class="logout-btn" id="logout">Logout</div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-header">
                <h1>Laporan Perpustakaan</h1>
            </div>
            
            <div class="chart-grid">
                <!-- Wadah untuk Chart Peminjaman -->
                <div class="content-card">
                    <h3>Grafik Peminjaman Buku per Bulan</h3>
                    <div id="peminjaman-chart-wrapper">
                        <!-- Canvas akan dibuat oleh JavaScript -->
                    </div>
                </div>

                <!-- Wadah untuk Chart Buku Populer -->
                <div class="content-card">
                    <h3>Buku Paling Populer</h3>
                    <div id="populer-chart-wrapper">
                        <!-- Canvas akan dibuat oleh JavaScript -->
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Pustaka JavaScript -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- === [ BAGIAN JAVASCRIPT LENGKAP ] === -->
    <script>
    $(document).ready(function() {
        const API_BASE_URL = "http://localhost:8080/edulib/buku";
        let peminjamanChart = null;
        let populerChart = null;

        // --- FUNGSI RENDER CHART ---

        function renderPeminjamanChart(laporanData) {
            const chartWrapper = $('#peminjaman-chart-wrapper');
            if (peminjamanChart) peminjamanChart.destroy();
            chartWrapper.empty().html('<canvas id="peminjaman-chart"></canvas>');
            
            const ctx = document.getElementById('peminjaman-chart');
            if (!ctx) return;

            peminjamanChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: laporanData.map(el => el.bulan),
                    datasets: [{
                        label: 'Jumlah Peminjaman',
                        data: laporanData.map(el => el.total),
                        borderColor: '#C0392B', // Warna tema primer
                        backgroundColor: 'rgba(192, 57, 43, 0.1)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: true, position: 'bottom' } }
                }
            });
        }

        function renderPopulerChart(bukuPopulerData) {
            const chartWrapper = $('#populer-chart-wrapper');
            if (populerChart) populerChart.destroy();
            chartWrapper.empty().html('<canvas id="populer-chart"></canvas>');

            const ctx = document.getElementById('populer-chart');
            if (!ctx) return;

            populerChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: bukuPopulerData.map(el => el.judul),
                    datasets: [{
                        label: 'Jumlah Dipinjam',
                        data: bukuPopulerData.map(el => el.jumlahPeminjaman),
                        backgroundColor: [
                            'rgba(192, 57, 43, 0.7)',
                            'rgba(44, 62, 80, 0.7)',
                            'rgba(39, 174, 96, 0.7)',
                            'rgba(41, 128, 185, 0.7)',
                            'rgba(243, 156, 18, 0.7)'
                        ],
                        borderRadius: 5
                    }]
                },
                options: {
                    indexAxis: 'y', // Bar horizontal agar judul mudah dibaca
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });
        }

        // --- FUNGSI UTAMA & AJAX ---

        function fetchReportData() {
            // Tampilkan state loading awal
            $('#peminjaman-chart-wrapper').html('<p style="text-align:center;">Memuat data...</p>');
            $('#populer-chart-wrapper').html('<p style="text-align:center;">Memuat data...</p>');

            $.get(`${API_BASE_URL}/statistik`)
                .done(function(response) {
                    if (response && response.laporan && response.bukuPopuler) {
                        renderPeminjamanChart(response.laporan);
                        renderPopulerChart(response.bukuPopuler);
                    } else {
                        $('#peminjaman-chart-wrapper').html('<p style="text-align:center;color:red;">Format data tidak sesuai.</p>');
                        $('#populer-chart-wrapper').html('<p style="text-align:center;color:red;">Format data tidak sesuai.</p>');
                    }
                })
                .fail(function() {
                    $('#peminjaman-chart-wrapper').html('<p style="text-align:center;color:red;">Gagal memuat data laporan.</p>');
                    $('#populer-chart-wrapper').html('<p style="text-align:center;color:red;">Gagal memuat data laporan.</p>');
                });
        }

        // --- EVENT HANDLERS ---

        $('#logout').on('click', function() {
            $.ajax({
                url: "http://localhost:8080/edulib/auth/logout",
                type: "get",
                xhrFields: { withCredentials: true }
            })
            .done(() => window.location.href = "../login/index.php")
            .fail(() => alert('Logout gagal.'));
        });

        // Muat data awal
        fetchReportData();
    });
    </script>
</body>
</html>