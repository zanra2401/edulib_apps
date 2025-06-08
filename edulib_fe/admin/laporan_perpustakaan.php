<?php
    require_once("../config/global.php");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDULIB - Laporan Perpustakaan</title>
        <script src=<?= BASE_LINK . "libs/js/jquery.js" ?>></script>
    <script src=<?= BASE_LINK . "static/cekLoginAdmin.js" ?>></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ==========================================================================
           File CSS Utama untuk Aplikasi EDULIB
           ========================================================================== */

        /* --------------------------------------------------------------------------
           1. Variabel Global (Warna, Font, Bayangan)
           -------------------------------------------------------------------------- */
        :root {
            --primary-color: #8E44AD;
            --primary-hover: #7D3C98;
            --secondary-color: #F4F6F7;
            --font-color: #34495E;
            --font-color-light: #797979;
            --border-color: #EAECEE;
            --white: #FFFFFF;
            --success-color: #2ECC71;
            --danger-color: #E74C3C;
            --warning-color: #F39C12;
            --font-primary: 'Inter', sans-serif;
            --font-headings: 'EB Garamond', serif;
            --shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        /* --------------------------------------------------------------------------
           2. Reset & Gaya Dasar
           -------------------------------------------------------------------------- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-primary);
            background-color: var(--secondary-color);
            color: var(--font-color);
            display: flex;
            font-size: 16px;
        }

        /* --------------------------------------------------------------------------
           3. Layout Utama (Sidebar & Konten)
           -------------------------------------------------------------------------- */
        .sidebar {
            width: 260px;
            background-color: var(--primary-color);
            color: var(--white);
            display: flex;
            flex-direction: column;
            padding: 1.5rem;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
        }

        .main-content {
            margin-left: 260px;
            flex-grow: 1;
            padding: 2rem 3rem;
            width: calc(100% - 260px);
        }

        /* --------------------------------------------------------------------------
           4. Komponen Sidebar
           -------------------------------------------------------------------------- */
        .logo-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2.5rem;
        }
        .logo { width: 40px; height: 40px; border-radius: 8px; overflow: hidden; }
        .logo img { width: 100%; height: 100%; object-fit: cover; }
        .brand-name { font-family: var(--font-headings); font-size: 1.8rem; font-weight: 700; }
        .menu-items .nav-item { background-color: rgba(255, 255, 255, 0.2); font-weight: 700; }
        .nav-item, .menu-item { padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 0.5rem; cursor: pointer; transition: all 0.3s ease; }
        .menu-item:hover { background-color: rgba(255, 255, 255, 0.1); transform: translateX(5px); }
        .logout { margin-top: auto; display: flex; align-items: center; gap: 0.8rem; padding: 1rem 1.5rem; border-radius: 8px; cursor: pointer; transition: background-color 0.3s ease; }
        .logout span { font-weight: 500; }
        .logout:hover { background-color: rgba(0,0,0,0.2); }

        /* --------------------------------------------------------------------------
           5. Komponen Konten Utama
           -------------------------------------------------------------------------- */
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .page-title { font-family: var(--font-headings); font-size: 2.5rem; color: var(--primary-color); }

        .table-container, .chart-container {
            background-color: var(--white);
            padding: 2rem;
            border-radius: 16px;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }
        .table-container h2, .chart-container h3 { margin-bottom: 1.5rem; font-size: 1.5rem; font-weight: 600; }
        .chart-grid { display: grid; grid-template-columns: 1fr; gap: 2rem; }
        @media (min-width: 1024px) { .chart-grid { grid-template-columns: repeat(2, 1fr); } }

        /* --------------------------------------------------------------------------
           6. Komponen Notifikasi (Toast)
           -------------------------------------------------------------------------- */
        #toast-container { position: fixed; top: 20px; right: 20px; z-index: 2000; display: flex; flex-direction: column; gap: 1rem; }
        .toast { padding: 1rem 1.5rem; border-radius: 8px; color: var(--white); box-shadow: var(--shadow); opacity: 0; transform: translateX(100%); animation: slideIn 0.5s forwards; display: flex; align-items: center; gap: 0.5rem; font-weight: 500; }
        @keyframes slideIn { to { opacity: 1; transform: translateX(0); } }
        .toast.success { background-color: var(--success-color); }
        .toast.error { background-color: var(--danger-color); }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo-section">
            <div class="logo"><img src="Logo.png" alt="Logo EDULIB"></div>
            <div class="brand-name">EDULIB</div>
        </div>
        <div class="menu-items">
            <div class="menu-item" data-url="akun.php">Akun</div>
            <div class="nav-item">Laporan Perpustakaan</div>
            <div class="menu-item" data-url="denda_siswa.php">Denda Siswa</div>
        </div>
        <div class="logout" id="logout-btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16,17 21,12 16,7"/><line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            <span>Logout</span>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1 class="page-title">Laporan Perpustakaan</h1>
        </div>
        
        <div class="chart-grid">
            <div class="chart-container">
                <h3>Laporan Peminjaman Buku per Bulan</h3>
                <div id="peminjaman-chart-wrapper">
                    </div>
            </div>
            <div class="chart-container">
                <h3>Buku Paling Populer</h3>
                <div id="populer-chart-wrapper">
                    </div>
            </div>
        </div>
    </div>
    
    <div id="toast-container"></div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        $(document).ready(function() {
            const API_BASE_URL = "http://localhost:8080/edulib";
            let peminjamanChart = null;
            let populerChart = null;

            // --- UTILITIES ---
            function showToast(message, type = 'success') {
                const toast = $(`<div class="toast ${type}">${message}</div>`);
                $('#toast-container').append(toast);
                setTimeout(() => toast.remove(), 4000);
            }

            // --- RENDER FUNCTIONS (Satu fungsi untuk setiap chart) ---
            function renderPeminjamanChart(laporanData) {
                const chartWrapper = $('#peminjaman-chart-wrapper');
                
                // Hancurkan dan buat ulang canvas untuk mencegah bug render
                if (peminjamanChart) peminjamanChart.destroy();
                chartWrapper.empty().append('<canvas id="peminjaman-chart"></canvas>');
                
                const ctx = document.getElementById('peminjaman-chart');
                if (!ctx) return;

                peminjamanChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: laporanData.map(el => el.bulan),
                        datasets: [{
                            label: 'Jumlah Peminjaman',
                            data: laporanData.map(el => el.total),
                            borderColor: '#8E44AD', // Warna tema primer
                            backgroundColor: 'rgba(142, 68, 173, 0.1)',
                            fill: true,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'top' } }
                    }
                });
            }

            function renderPopulerChart(bukuPopulerData) {
                const chartWrapper = $('#populer-chart-wrapper');
                
                // Hancurkan dan buat ulang canvas untuk mencegah bug render
                if (populerChart) populerChart.destroy();
                chartWrapper.empty().append('<canvas id="populer-chart"></canvas>');

                const ctx = document.getElementById('populer-chart');
                if (!ctx) return;

                populerChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: bukuPopulerData.map(el => el.judul),
                        datasets: [{
                            label: 'Jumlah Peminjaman',
                            data: bukuPopulerData.map(el => el.jumlahPeminjaman),
                            backgroundColor: [ // Berikan warna berbeda untuk setiap bar
                                'rgba(142, 68, 173, 0.7)',
                                'rgba(41, 128, 185, 0.7)',
                                'rgba(39, 174, 96, 0.7)',
                                'rgba(243, 156, 18, 0.7)',
                                'rgba(211, 84, 0, 0.7)'
                            ],
                            borderColor: [
                                '#8E44AD',
                                '#2980B9',
                                '#27AE60',
                                '#F39C12',
                                '#D35400'
                            ],
                            borderWidth: 1,
                            borderRadius: 5
                        }]
                    },
                    options: {
                        indexAxis: 'y', // Membuat bar chart menjadi horizontal agar judul buku panjang bisa terbaca
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } } // Sembunyikan legenda karena tidak perlu
                    }
                });
            }

            // --- MAIN DATA FETCH ---
            function fetchReportData() {
                // Tampilkan state loading awal
                $('#peminjaman-chart-wrapper').html('<p style="text-align:center;">Memuat data...</p>');
                $('#populer-chart-wrapper').html('<p style="text-align:center;">Memuat data...</p>');

                $.ajax({
                    url: `${API_BASE_URL}/buku/statistik`,
                    type: "GET",
                    xhrFields: { withCredentials: true },
                    success: function(response) {
                        if (response && response.laporan && response.bukuPopuler) {
                            renderPeminjamanChart(response.laporan);
                            renderPopulerChart(response.bukuPopuler);
                        } else {
                            showToast('Format data dari server tidak sesuai.', 'error');
                            $('#peminjaman-chart-wrapper').html('<p style="text-align:center;color:red;">Gagal memuat chart.</p>');
                            $('#populer-chart-wrapper').html('<p style="text-align:center;color:red;">Gagal memuat chart.</p>');
                        }
                    },
                    error: function() {
                        showToast('Gagal terhubung ke server.', 'error');
                        $('#peminjaman-chart-wrapper').html('<p style="text-align:center;color:red;">Gagal memuat chart.</p>');
                        $('#populer-chart-wrapper').html('<p style="text-align:center;color:red;">Gagal memuat chart.</p>');
                    }
                });
            }

            // --- SIDEBAR & LOGOUT ---
            $('.menu-item').on('click', function() {
                const url = $(this).data('url');
                if (url) window.location.href = url;
            });

            $('#logout-btn').on('click', function() {
                $.ajax({
                    url: `${API_BASE_URL}/auth/logout`,
                    type: "GET",
                    xhrFields: { withCredentials: true },
                    success: function() {
                        showToast('Logout berhasil!', 'success');
                        setTimeout(() => window.location.reload(), 1500);
                    },
                    error: function() {
                        showToast('Logout gagal.', 'error');
                    }
                });
            });

            // Initial load
            fetchReportData();
        });
    </script>

</body>
</html>