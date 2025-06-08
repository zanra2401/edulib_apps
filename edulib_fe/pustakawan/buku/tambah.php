<?php require_once("../../config/global.php"); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku - EDULIB</title>
    <link rel="stylesheet" href=<?= BASE_LINK . 'libs/css/bootstrap.css' ?>>
    <link rel="stylesheet" href=<?= BASE_LINK . 'static/pustakawanbuku.css' ?>>
    <script src=<?= BASE_LINK . 'libs/js/jquery.js' ?>></script>
    <script src=<?= BASE_LINK . "static/cekLoginPustaka.js" ?>></script>
    <link rel="stylesheet" href="pustakawan_style.css">
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
 <!-- CSS Terpusat untuk Pustakawan -->
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
        .btn:disabled { background-color: #BDC3C7; cursor: not-allowed; }
        .btn-primary { background-color: var(--pustaka-primary); color: white; }
        .btn-primary:hover:not(:disabled) { background-color: var(--pustaka-primary-dark); transform: translateY(-2px); }
        .btn-secondary { background-color: #BDC3C7; color: var(--pustaka-font-dark); }
        .content-card { background-color: var(--pustaka-card-bg); padding: 2rem; border-radius: 12px; box-shadow: var(--shadow); }
        .alert { padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 2rem; font-weight: 500; border: 1px solid transparent; }
        .alert-error { background-color: #FDEBD0; border-color: #F5CBA7; color: #AF601A; }
        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; }
        .form-group input, .form-group select { width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--pustaka-border); border-radius: 8px; font-size: 1rem; }
        .form-group input:focus, .form-group select:focus { outline: none; border-color: var(--pustaka-primary); box-shadow: 0 0 0 3px rgba(192, 57, 43, 0.15); }
        .form-actions { margin-top: 2rem; display: flex; gap: 1rem; border-top: 1px solid var(--pustaka-border); padding-top: 2rem; }
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
                <h1>Tambah Buku Baru</h1>
            </div>

            <div class="alert-container"></div>
            
            <div class="content-card">
                <form id="form-buku" enctype="multipart/form-data">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="judul">Judul Buku</label>
                            <input type="text" id="judul" required>
                        </div>
                        <div class="form-group">
                            <label for="penulis">Penulis</label>
                            <input type="text" id="penulis" required>
                        </div>
                        <div class="form-group">
                            <label for="penerbit">Penerbit</label>
                            <input type="text" id="penerbit" required>
                        </div>
                        <div class="form-group">
                            <label for="tahun">Tahun Terbit</label>
                            <input type="number" id="tahun" placeholder="Contoh: 2024" required>
                        </div>
                        <div class="form-group">
                            <label for="kategori">Kategori</label>
                            <input type="text" id="kategori" placeholder="Contoh: 2024" required>
                        </div>
                        <div class="form-group">
                            <label for="stok">Jumlah Stok</label>
                            <input type="number" id="stok" required>
                        </div>
                        <div class="form-group">
                            <label for="lokasi">Lokasi Rak</label>
                            <input type="text" id="lokasi" placeholder="Contoh: A1, B2" required>
                        </div>
                        <div class="form-group">
                            <label for="gambar">Gambar Sampul</label>
                            <input type="file" id="gambar" accept="image/*">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Simpan Buku</button>
                        <a href="index.php" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            const API_BASE_URL = "http://localhost:8080/edulib/buku"; // Sesuaikan dengan API Anda

            $('#form-buku').on('submit', function(e) {
                e.preventDefault();
                const submitButton = $(this).find('button[type="submit"]');
                submitButton.prop('disabled', true).text('Menyimpan...');
                
                const formData = new FormData();
                formData.append('judul', $("#judul").val());
                formData.append('penulis', $("#penulis").val());
                formData.append('penerbit', $("#penerbit").val());
                formData.append('tahunTerbit', $("#tahun").val());
                formData.append('kategori', $("#kategori").val());
                formData.append('jumlahStok', $("#stok").val());
                formData.append('lokasiRak', $("#lokasi").val());

                if ($('#gambar')[0].files.length > 0) {
                    formData.append('gambar', $('#gambar')[0].files[0]);
                }

                $.ajax({
                    url: `${API_BASE_URL}/tambah`,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhrFields: { withCredentials: true },
                    success: function() {
                        window.location.href = 'index.php?status=success&pesan=Buku baru berhasil ditambahkan.';
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseText || "Gagal menambahkan buku. Silakan coba lagi.";
                        $('.alert-container').html(`<div class="alert alert-error">${errorMsg}</div>`);
                        submitButton.prop('disabled', false).text('Simpan Buku');
                    }
                });
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
        });
    </script>
</body>
</html>
