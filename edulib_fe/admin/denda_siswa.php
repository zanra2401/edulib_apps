<?php
    require_once("../config/global.php");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDULIB - Denda Siswa</title>
    <script src=<?= BASE_LINK . "libs/js/jquery.js" ?>></script>
    <script src=<?= BASE_LINK . "static/cekLoginAdmin.js" ?>></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ==========================================================================
    File CSS Utama untuk Aplikasi EDULIB
    Didesain untuk konsistensi di seluruh halaman dashboard.
    ========================================================================== */

    /* --------------------------------------------------------------------------
    1. Variabel Global (Warna, Font, Bayangan)
    Ubah nilai di sini untuk mengubah tema seluruh aplikasi.
    -------------------------------------------------------------------------- */
    :root {
        --primary-color: #8E44AD; /* Ungu yang elegan */
        --primary-hover: #7D3C98;
        --secondary-color: #F4F6F7; /* Latar belakang abu-abu sangat muda */
        --font-color: #34495E; /* Biru tua untuk teks */
        --font-color-light: #797979; /* Abu-abu untuk sub-teks */
        --border-color: #EAECEE;
        --white: #FFFFFF;
        
        --success-color: #2ECC71; /* Hijau untuk aksi sukses/lunas */
        --success-hover: #27AE60;
        --danger-color: #E74C3C; /* Merah untuk aksi hapus/error */
        --danger-hover: #C0392B;
        --warning-color: #F39C12; /* Kuning untuk status peringatan/belum lunas */

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
        margin-left: 260px; /* Sesuai lebar sidebar */
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
    .logo {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        overflow: hidden;
    }
    .logo img { width: 100%; height: 100%; object-fit: cover; }
    .brand-name {
        font-family: var(--font-headings);
        font-size: 1.8rem;
        font-weight: 700;
    }
    .menu-items .nav-item {
        background-color: rgba(255, 255, 255, 0.2);
        font-weight: 700;
    }
    .nav-item, .menu-item {
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .menu-item:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateX(5px);
    }
    .logout {
        margin-top: auto;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .logout span { font-weight: 500; }
    .logout:hover { background-color: rgba(0,0,0,0.2); }

    /* --------------------------------------------------------------------------
    5. Komponen Konten Utama
    -------------------------------------------------------------------------- */

    /* Header */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .page-title {
        font-family: var(--font-headings);
        font-size: 2.5rem;
        color: var(--primary-color);
    }
    .header-actions { display: flex; gap: 1rem; }

    /* Wadah "Card" untuk Tabel dan Chart */
    .table-container,
    .chart-container {
        background-color: var(--white);
        padding: 2rem;
        border-radius: 16px;
        box-shadow: var(--shadow);
        margin-bottom: 2rem;
    }
    .table-container h2,
    .chart-container h3 {
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
        font-weight: 600;
    }

    /* Tabel */
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }
    thead th {
        font-weight: 600;
        color: var(--font-color-light);
        text-transform: uppercase;
        font-size: 0.85rem;
    }
    tbody tr:hover { background-color: var(--secondary-color); }

    /* Tombol */
    .action-buttons { display: flex; gap: 0.5rem; }

    .btn,
    .add-button,
    .btn-edit, 
    .btn-delete {
        padding: 0.8rem 1.5rem;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        color: var(--white);
    }
    .add-button:hover,
    .btn:hover {
        transform: translateY(-2px);
        filter: brightness(1.1);
    }
    .add-button {
        background-color: var(--primary-color);
        box-shadow: var(--shadow);
    }
    .btn-edit {
        background-color: var(--success-color);
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    .btn-delete {
        background-color: var(--danger-color);
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    .btn-primary { background-color: var(--primary-color); }
    .btn-secondary { background-color: var(--border-color); color: var(--font-color); }
    .btn-secondary:hover { background-color: #BDC3C7; }

    /* Badge Status */
    .badge {
        padding: 0.3rem 0.8rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--white);
        text-transform: capitalize;
    }
    .badge-success { background-color: var(--success-color); }
    .badge-warning { background-color: var(--warning-color); }

    /* --------------------------------------------------------------------------
    6. Komponen Modal (Form)
    -------------------------------------------------------------------------- */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    .modal-container {
        background: var(--white);
        padding: 2rem 3rem;
        border-radius: 16px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        transform: translateY(-50px);
        transition: transform 0.3s ease;
    }
    .modal-overlay.active .modal-container {
        transform: translateY(0);
    }
    .modal-container h1 { text-align: center; margin-bottom: 2rem; }
    .form-group { margin-bottom: 1.2rem; }
    .form-group label { display: block; margin-bottom: 6px; font-weight: 600; }
    .form-group input {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 1px solid #BDC3C7;
        border-radius: 8px;
        font-family: var(--font-primary);
        font-size: 1rem;
    }
    .form-group input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(142, 68, 173, 0.2);
    }
    .form-group small {
        font-size: 0.8rem;
        color: var(--font-color-light);
        margin-top: 4px;
    }
    .form-buttons {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
    }

    /* --------------------------------------------------------------------------
    7. Komponen Notifikasi (Toast)
    -------------------------------------------------------------------------- */
    #toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 2000;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .toast {
        padding: 1rem 1.5rem;
        border-radius: 8px;
        color: var(--white);
        box-shadow: var(--shadow);
        opacity: 0;
        transform: translateX(100%);
        animation: slideIn 0.5s forwards;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
    }
    @keyframes slideIn {
        to { opacity: 1; transform: translateX(0); }
    }
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
            <div class="menu-item" data-url="laporan_perpustakaan.php">Laporan Perpustakaan</div>
            <div class="nav-item">Denda Siswa</div>
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
            <h1 class="page-title">Denda Siswa</h1>
        </div>

        <div class="table-container">
            <h2>Detail Denda</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID User</th>
                        <th>ID Buku</th>
                        <th>Jumlah Hari Terlambat</th>
                        <th>Nominal Denda</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="denda-table-body">
                    <tr>
                        <td colspan="5" style="text-align: center;">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        

        <div class="chart-container">
            <h3>Statistik Total Denda per Bulan</h3>
            <div id="chart-wrapper">
                </div>
        </div>
    </div>
    
    <div id="toast-container"></div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
    const API_BASE_URL = "http://localhost:8080/edulib";
    let dendaChart = null;

    // --- UTILITIES ---
    function showToast(message, type = 'success') {
        const toast = $(`<div class="toast ${type}">${message}</div>`);
        $('#toast-container').append(toast);
        setTimeout(() => toast.remove(), 4000);
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    }

    // --- RENDER FUNCTIONS ---
    function renderFinesTable(fines) {
        const tableBody = $('#denda-table-body');
        tableBody.empty();

        if (!fines || fines.length === 0) {
            tableBody.append('<tr><td colspan="5" style="text-align: center;">Tidak ada data denda.</td></tr>');
            return;
        }

        fines.forEach(denda => {
            const statusClass = denda.statusDenda.toLowerCase() === 'lunas' ? 'badge-success' : 'badge-warning';
            const row = `
                <tr>
                    <td>${denda.idUser}</td>
                    <td>${denda.idBuku}</td>
                    <td>${denda.jumlahHariTerlambat} Hari</td>
                    <td>${formatCurrency(denda.totalNominal)}</td>
                    <td><span class="badge ${statusClass}">${denda.statusDenda}</span></td>
                </tr>
            `;
            tableBody.append(row);
        });
    }
    
    /**
     * FUNGSI CHART YANG DIKEMBALIKAN KE LOGIKA ASLI ANDA
     * Menggunakan data 'laporan' yang sudah diagregasi dari API.
     * @param {Array} reportData - Array objek dari response.laporan.
     */
    function renderFinesChart(reportData) {
        // === BAGIAN INI TETAP DIPERTAHANKAN UNTUK MENGATASI BUG RENDER KE BAWAH ===
        const chartWrapper = $('#chart-wrapper');
        if (dendaChart) {
            dendaChart.destroy();
        }
        chartWrapper.empty(); 
        chartWrapper.append('<canvas id="denda-chart"></canvas>');
        const ctx = document.getElementById('denda-chart');
        if (!ctx) return;
        // =========================================================================

        // === LOGIKA DATA KEMBALI SEPERTI ASLINYA (MILIK ANDA) ===
        // Tidak ada lagi agregasi data di sisi klien.
        dendaChart = new Chart(ctx, {
            type: 'bar',
            data: {
                // Langsung menggunakan data dari API
                labels: reportData.map(el => el.bulan),
                datasets: [{
                    label: 'Total Denda Terkumpul',
                    data: reportData.map(el => el.totalDenda),
                    backgroundColor: 'rgba(142, 68, 173, 0.7)',
                    borderColor: 'rgba(142, 68, 173, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) { return formatCurrency(value); }
                        }
                    }
                },
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Total: ${formatCurrency(context.raw)}`;
                            }
                        }
                    }
                }
            }
        });
    }


    // --- MAIN DATA FETCH ---
    function fetchFinesData() {
        const tableBody = $('#denda-table-body');
        tableBody.html('<tr><td colspan="5" style="text-align: center;">Memuat data...</td></tr>');

        $.ajax({
            url: `${API_BASE_URL}/denda/`,
            type: "GET",
            xhrFields: { withCredentials: true },
            success: function(response) {
                // Memastikan kedua data ada sebelum render
                if (response && response.denda && response.laporan) {
                    renderFinesTable(response.denda);
                    console.log(response);
                    // Memanggil fungsi chart dengan data `response.laporan`
                    renderFinesChart(response.laporan); 
                } else {
                    showToast('Format data dari server tidak sesuai.', 'error');
                    tableBody.html('<tr><td colspan="5" style="text-align: center;">Gagal memuat data.</td></tr>');
                }
            },
            error: function() {
                showToast('Gagal terhubung ke server.', 'error');
                tableBody.html('<tr><td colspan="5" style="text-align: center;">Gagal memuat data. Silakan coba lagi.</td></tr>');
            }
        });
    }

    // --- SIDEBAR & LOGOUT (Tidak ada perubahan) ---
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
    fetchFinesData();
});
    </script>
    </body>
</html>