<?php require_once("../../config/global.php"); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Buku - EDULIB</title>
    <link rel="stylesheet" href=<?= BASE_LINK . 'libs/css/bootstrap.css' ?>>
    <link rel="stylesheet" href=<?= BASE_LINK . 'static/pustakawanbuku.css' ?>>
    <script src=<?= BASE_LINK . 'libs/js/jquery.js' ?>></script>
    <script src=<?= BASE_LINK . "static/cekLoginPustaka.js" ?>></script>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
        /* pustakawan_style.css */
        :root {
            --pustaka-primary: #C0392B;
            --pustaka-primary-dark: #A93226;
            --pustaka-bg: #F9F9F9;
            --pustaka-card-bg: #FFFFFF;
            --pustaka-font-dark: #2C3E50;
            --pustaka-font-light: #808B96;
            --pustaka-border: #EAEDED;
            --pustaka-success: #27AE60;
            --pustaka-danger: #E74C3C;
            --shadow: 0 4px 15px rgba(0, 0, 0, 0.07);
        }
        body {
            margin: 0;
            font-family: 'Inter', sans-serif, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: var(--pustaka-bg);
            color: var(--pustaka-font-dark);
        }
        .container-p { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background-color: var(--pustaka-primary); color: white; padding: 1.5rem; display: flex; flex-direction: column; flex-shrink: 0; position: fixed; height: 100%; }
        .main-content { margin-left: 250px; flex-grow: 1; padding: 2rem 3rem; overflow-y: auto; }
        .sidebar h2 { font-family: 'EB Garamond', serif; font-size: 2rem; text-align: center; margin-bottom: 2rem; color: white; }
        .sidebar-nav a { display: block; color: white; text-decoration: none; padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 0.5rem; font-weight: 500; transition: all 0.3s ease; }
        .sidebar-nav a:hover { background-color: rgba(255, 255, 255, 0.1); transform: translateX(5px); }
        .sidebar-nav a.active { background-color: var(--pustaka-card-bg); color: var(--pustaka-primary); font-weight: 700; }
        .logout-btn { margin-top: auto; background-color: rgba(0,0,0,0.2); text-align: center; padding: 1rem; border-radius: 8px; cursor: pointer; font-weight: 600; transition: background-color 0.3s ease; }
        .logout-btn:hover { background-color: rgba(0,0,0,0.4); }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .page-header h1 { font-size: 2.25rem; color: var(--pustaka-font-dark); }
        .btn { padding: 0.75rem 1.5rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 1rem; text-decoration: none; display: inline-block; transition: all 0.3s ease; }
        .btn-primary { background-color: var(--pustaka-primary); color: white; }
        .btn-primary:hover { background-color: var(--pustaka-primary-dark); transform: translateY(-2px); }
        .btn-secondary { background-color: #BDC3C7; color: var(--pustaka-font-dark); padding: 0.5rem 1rem; font-size: 0.9rem; }
        .btn-danger { background-color: var(--pustaka-danger); color: white; padding: 0.5rem 1rem; font-size: 0.9rem; }
        .content-card { background-color: var(--pustaka-card-bg); padding: 2rem; border-radius: 12px; box-shadow: var(--shadow); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid var(--pustaka-border); vertical-align: middle;}
        thead th { background-color: #F8F9F9; font-weight: 600; color: var(--pustaka-font-light); text-transform: uppercase; font-size: 0.8rem; }
        tbody tr:hover { background-color: #FDFEFE; }
        .action-buttons { display: flex; gap: 0.5rem; }
        .alert { padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 2rem; font-weight: 500; border: 1px solid transparent; }
        .alert-success { background-color: #E8F8F5; border-color: #A3E4D7; color: #117A65; }
        .alert-error { background-color: #FDEBD0; border-color: #F5CBA7; color: #AF601A; }
    </style>
</head>
<body>
    <div class="container-p">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>EDULIB</h2>
            <nav class="sidebar-nav">
                <a href="index.php" class="active">Buku</a>
                <a href="../peminjaman-buku/index.php">Peminjaman Buku</a>
                <a href="../pengembalian-buku/index.php">Pengembalian Buku</a>
                <a href="../laporan-perpustakaan/index.php">Laporan Perpustakaan</a>
            </nav>
            <div class="logout-btn" id="logout">Logout</div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-header">
                <h1>Manajemen Buku</h1>
                <a href="tambah.php" class="btn btn-primary">Tambah Buku Baru</a>
            </div>

            <div class="alert-container">
                <?php if(isset($_GET['status']) && isset($_GET['pesan'])): ?>
                    <div class="alert alert-<?= $_GET['status'] ?>">
                        <?= htmlspecialchars($_GET['pesan']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="content-card">
                <table>
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>ID</th><th>Judul</th><th>Penulis</th><th>Kategori</th><th>Stok</th><th>Rak</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="buku-table-body">
                        <tr><td colspan="8" style="text-align:center;">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            const API_BASE_URL = "http://localhost:8080/edulib/buku"; // Sesuaikan dengan API Anda
            const IMAGE_BASE_URL = "http://localhost:8080/gambar/buku/"; // Sesuaikan dengan path gambar Anda

            function loadBooks() {
                $.get(`${API_BASE_URL}/buku-status`)
                    .done(function(response) {
                        const tableBody = $('#buku-table-body');
                        tableBody.empty();
                        if (response.buku && response.buku.length > 0) {
                            response.buku.forEach(b => {
                                console.log(b);
                                const imageUrl = b[8] ? IMAGE_BASE_URL + b[8] : 'https://placehold.co/80x120/e0e0e0/757575?text=No+Image';
                                const row = `
                                    <tr>
                                        <td><img src="${imageUrl}" alt="Cover ${b[1]}" style="width: 60px; height: 90px; object-fit: cover; border-radius: 4px;"></td>
                                        <td>${b[0]}</td>
                                        <td>${b[1]}</td>
                                        <td>${b[2]}</td>
                                        <td>${b[5]}</td>
                                        <td>${b[6]}</td>
                                        <td>${b[7]}</td>
                                        <td class="action-buttons">
                                            <a href="edit.php?id=${b[0]}" class="btn btn-secondary">Edit</a>
                                            <button class="btn btn-danger delete-btn" data-id="${b[0]}">Hapus</button>
                                        </td>
                                    </tr>`;
                                tableBody.append(row);
                            });
                        } else {
                            tableBody.html('<tr><td colspan="8" style="text-align:center;">Tidak ada data buku.</td></tr>');
                        }
                    })
                    .fail(function() {
                        $('#buku-table-body').html('<tr><td colspan="8" style="text-align:center;color:red;">Gagal memuat data buku.</td></tr>');
                    });
            }
            
            $(document).on('click', '.delete-btn', function() {
                const bookId = $(this).data('id');
                if (confirm(`Apakah Anda yakin ingin menghapus buku dengan ID ${bookId}?`)) {
                    // Menggunakan AJAX untuk DELETE
                    $.ajax({
                        url: `${API_BASE_URL}/deletebuku/${bookId}`,
                        type: 'DELETE', // Menggunakan method DELETE yang lebih sesuai
                        xhrFields: { withCredentials: true },
                        success: function() {
                            window.location.href = 'index.php?status=success&pesan=Buku berhasil dihapus.';
                        },
                        error: function() {
                            alert('Gagal menghapus buku. Cek konsol untuk detail.');
                        }
                    });
                }
            });

            $('#logout').on('click', function() {
                 $.ajax({
                    url: "http://localhost:8080/edulib/auth/logout", // Sesuaikan dengan API Anda
                    type: "get",
                    xhrFields: { withCredentials: true },
                    success: function () {
                        window.location.href = "../login/index.php"; // Arahkan ke halaman login
                    }
                });
            });

            loadBooks();
        });
    </script>
</body>
</html>
