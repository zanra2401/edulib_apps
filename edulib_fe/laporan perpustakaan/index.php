<?php
session_start();

// Data dummy (ganti dengan query database jika ada)
if (!isset($_SESSION['laporan'])) {
    $_SESSION['laporan'] = [
        // Format: [tanggal, jumlah_peminjaman, jumlah_pengembalian, total_denda]
        ['2024-06-01', 5, 3, 2000],
        ['2024-06-02', 7, 5, 5000],
        ['2024-06-03', 4, 6, 0],
        ['2024-06-04', 8, 7, 1000],
        ['2024-06-05', 2, 2, 0],
        // dst...
    ];
}

// Kirim data ke JS
$laporan = $_SESSION['laporan'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perpustakaan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Inter', sans-serif; background: #f9fafb; color: #333; }
        .container { display: flex; height: 100vh; }
        .sidebar {
            width: 250px; background: #b23b3b; color: #fff; padding: 30px 20px;
            display: flex; flex-direction: column;
        }
        .sidebar h2 { font-size: 26px; margin-bottom: 40px; text-align: center; font-weight: 700; }
        .sidebar a {
            display: block; padding: 12px 16px; margin: 8px 0; background: transparent;
            color: #fff; text-decoration: none; border-radius: 6px; font-weight: 500; transition: background 0.3s;
        }
        .sidebar a.active, .sidebar a:hover { background: #fff; color: #b23b3b; }
        .logout { margin-top: auto; padding: 12px; background: #fff; color: #b23b3b; border-radius: 6px; text-align: center; font-weight: bold; cursor: pointer; }
        .main { flex: 1; padding: 45px; overflow-y: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        h1 { font-size: 28px; margin: 0; }
        .chart-row {
            display: flex;
            align-items: flex-start;
            gap: 32px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }
        .chart-col {
            flex: 1 1 400px;
            min-width: 320px;
        }
        .chart-title {
            background: #b23b3b;
            color: #fff;
            font-size: 20px;
            font-weight: bold;
            padding: 12px 0;
            text-align: center;
            border-radius: 8px 8px 0 0;
            margin-bottom: 0;
            letter-spacing: 1px;
        }
        .chart-container {
            background: #fff;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            padding: 30px;
        }
        .filter-side {
            min-width: 220px;
            max-width: 260px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            padding: 24px 20px;
            border-left: 4px solid #b23b3b;
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-top: 32px;
        }
        .filter-side label {
            color: #b23b3b;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 4px;
        }
        .filter-side input[type=date] {
            padding: 8px 12px;
            border: 2px solid #b23b3b;
            border-radius: 6px;
            font-size: 14px;
            color: #b23b3b;
            background: #fff;
            font-weight: 600;
        }
        .filter-side input[type=date]:focus {
            outline: none;
            border-color: #b23b3b;
            box-shadow: 0 0 0 2px rgba(178, 59, 59, 0.1);
        }
        @media (max-width: 1100px) {
            .chart-row { flex-direction: column; gap: 20px; }
            .filter-side { margin-top: 0; max-width: 100%; }
        }
        @media (max-width: 768px) {
            .container { flex-direction: column; }
            .sidebar { width: 100%; height: auto; padding: 20px; }
            .main { padding: 20px; }
            .chart-container { padding: 10px; }
            .filter-side { padding: 12px 8px; }
        }
    </style>
</head>
<body>
<div class="container">
    <aside class="sidebar">
        <h2>EDULIB</h2>
        <a href="../buku">Buku</a>
        <a href="../peminjaman buku">Peminjaman Buku</a>
        <a href="../pengembalian buku">Pengembalian Buku</a>
        <a href="../manajemen aturan">Manajemen Aturan</a>
        <a href="../laporan perpustakaan" class="active">Laporan Perpustakaan</a>
        <div class="logout">Logout</div>
    </aside>
    <main class="main">
        <div class="header">
            <h1>Laporan Perpustakaan</h1>
        </div>

        <!-- Grafik Peminjaman -->
        <div class="chart-row">
            <div class="chart-col">
                <div class="chart-title">Grafik Peminjaman Buku</div>
                <div class="chart-container">
                    <canvas id="chartPeminjaman"></canvas>
                </div>
            </div>
            <form class="filter-side" id="filterPeminjaman" onsubmit="return false;">
                <div>
                    <label for="startPeminjaman">Tanggal Awal</label>
                    <input type="date" id="startPeminjaman">
                </div>
                <div>
                    <label for="endPeminjaman">Tanggal Akhir</label>
                    <input type="date" id="endPeminjaman">
                </div>
            </form>
        </div>

        <!-- Grafik Pengembalian -->
        <div class="chart-row">
            <div class="chart-col">
                <div class="chart-title">Grafik Pengembalian Buku</div>
                <div class="chart-container">
                    <canvas id="chartPengembalian"></canvas>
                </div>
            </div>
            <form class="filter-side" id="filterPengembalian" onsubmit="return false;">
                <div>
                    <label for="startPengembalian">Tanggal Awal</label>
                    <input type="date" id="startPengembalian">
                </div>
                <div>
                    <label for="endPengembalian">Tanggal Akhir</label>
                    <input type="date" id="endPengembalian">
                </div>
            </form>
        </div>

        <!-- Grafik Denda -->
        <div class="chart-row">
            <div class="chart-col">
                <div class="chart-title">Grafik Denda Siswa</div>
                <div class="chart-container">
                    <canvas id="chartDenda"></canvas>
                </div>
            </div>
            <form class="filter-side" id="filterDenda" onsubmit="return false;">
                <div>
                    <label for="startDenda">Tanggal Awal</label>
                    <input type="date" id="startDenda">
                </div>
                <div>
                    <label for="endDenda">Tanggal Akhir</label>
                    <input type="date" id="endDenda">
                </div>
            </form>
        </div>
    </main>
</div>
<script>
const laporan = <?= json_encode($laporan) ?>;

// Fungsi filter data
function filterData(data, start, end, col) {
    return data.filter(row => {
        const date = row[0];
        if (start && date < start) return false;
        if (end && date > end) return false;
        return true;
    }).map(row => [row[0], row[col]]);
}

// --- PEMINJAMAN ---
let chartPeminjaman;
function updateChartPeminjaman() {
    const start = document.getElementById('startPeminjaman').value;
    const end = document.getElementById('endPeminjaman').value;
    const data = filterData(laporan, start, end, 1);
    const labels = data.map(d => d[0]);
    const values = data.map(d => d[1]);
    chartPeminjaman.data.labels = labels;
    chartPeminjaman.data.datasets[0].data = values;
    chartPeminjaman.update();
}

// --- PENGEMBALIAN ---
let chartPengembalian;
function updateChartPengembalian() {
    const start = document.getElementById('startPengembalian').value;
    const end = document.getElementById('endPengembalian').value;
    const data = filterData(laporan, start, end, 2);
    const labels = data.map(d => d[0]);
    const values = data.map(d => d[1]);
    chartPengembalian.data.labels = labels;
    chartPengembalian.data.datasets[0].data = values;
    chartPengembalian.update();
}

// --- DENDA ---
let chartDenda;
function updateChartDenda() {
    const start = document.getElementById('startDenda').value;
    const end = document.getElementById('endDenda').value;
    const data = filterData(laporan, start, end, 3);
    const labels = data.map(d => d[0]);
    const values = data.map(d => d[1]);
    chartDenda.data.labels = labels;
    chartDenda.data.datasets[0].data = values;
    chartDenda.update();
}

window.addEventListener('DOMContentLoaded', function() {
    // Chart Peminjaman (Line, Merah)
    chartPeminjaman = new Chart(document.getElementById('chartPeminjaman'), {
        type: 'line',
        data: {
            labels: laporan.map(d => d[0]),
            datasets: [{
                label: 'Jumlah Peminjaman Buku',
                data: laporan.map(d => d[1]),
                borderColor: '#b23b3b',
                backgroundColor: 'rgba(178,59,59,0.15)',
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#b23b3b',
                pointBorderColor: '#b23b3b'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });

    // Chart Pengembalian (Line, Merah)
    chartPengembalian = new Chart(document.getElementById('chartPengembalian'), {
        type: 'line',
        data: {
            labels: laporan.map(d => d[0]),
            datasets: [{
                label: 'Jumlah Pengembalian Buku',
                data: laporan.map(d => d[2]),
                borderColor: '#b23b3b',
                backgroundColor: 'rgba(178,59,59,0.15)',
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#b23b3b',
                pointBorderColor: '#b23b3b'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });

    // Chart Denda (Bar, Merah)
    chartDenda = new Chart(document.getElementById('chartDenda'), {
        type: 'bar',
        data: {
            labels: laporan.map(d => d[0]),
            datasets: [{
                label: 'Total Denda Siswa (Rp)',
                data: laporan.map(d => d[3]),
                backgroundColor: '#b23b3b'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Event filter otomatis
    document.getElementById('startPeminjaman').addEventListener('change', updateChartPeminjaman);
    document.getElementById('endPeminjaman').addEventListener('change', updateChartPeminjaman);
    document.getElementById('startPengembalian').addEventListener('change', updateChartPengembalian);
    document.getElementById('endPengembalian').addEventListener('change', updateChartPengembalian);
    document.getElementById('startDenda').addEventListener('change', updateChartDenda);
    document.getElementById('endDenda').addEventListener('change', updateChartDenda);
});
</script>
</body>
</html>
