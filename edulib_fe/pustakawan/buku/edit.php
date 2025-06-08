<?php
require_once("../../config/global.php");
if (!isset($_GET['id'])) {
    header('Location: index.php?status=error&pesan=ID buku tidak ditemukan.');
    exit;
}
$id_buku = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku - EDULIB</title>
    <link rel="stylesheet" href=<?= BASE_LINK . 'libs/css/bootstrap.css' ?>>
    <link rel="stylesheet" href=<?= BASE_LINK . 'static/pustakawanbuku.css' ?>>
    <script src=<?= BASE_LINK . 'libs/js/jquery.js' ?>></script>
    <script src=<?= BASE_LINK . "static/cekLoginPustaka.js" ?>></script>
    <link rel="stylesheet" href="pustakawan_style.css">
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
                <h1>Edit Buku (ID: <?= htmlspecialchars($id_buku) ?>)</h1>
            </div>

            <div class="alert-container"></div>

            <div class="content-card">
                <form id="form-buku">
                    <input type="hidden" id="idBuku" value="<?= htmlspecialchars($id_buku) ?>">
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
                            <input type="number" id="tahun" placeholder="Contoh: 2024" min="1000" max="9999" required>
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
                            <label for="gambar">Ganti Gambar Sampul (Opsional)</label>
                            <input type="file" id="gambar" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label>Gambar Saat Ini</label>
                            <img id="gambar-preview" src="https://placehold.co/100x150/e0e0e0/757575?text=Loading..." alt="Gambar saat ini" style="max-width: 100px; margin-top: 10px; border-radius: 4px;">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update Buku</button>
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
            const IMAGE_BASE_URL = "http://localhost:8080/api/v1/images/"; // Sesuaikan dengan path gambar Anda
            const bookId = $('#idBuku').val();

            function loadBookData() {
                $.get(`${API_BASE_URL}/buku-status/${bookId}`) // Pastikan endpoint ini benar
                    .done(function(response) {
                        const b = response.buku[0]; // Asumsi data ada di index 0
                        $("#judul").val(b[1]);
                        $("#penulis").val(b[2]);
                        $("#penerbit").val(b[3]);
                        $("#tahun").val(b[4]);
                        $("#kategori").val(b[5]);
                        $("#stok").val(b[6]);
                        $("#lokasi").val(b[7]);

                        const imageUrl = b[8] ? IMAGE_BASE_URL + b[8] : 'https://placehold.co/100x150/e0e0e0/757575?text=No+Image';
                        $("#gambar-preview").attr('src', imageUrl);
                    })
                    .fail(function() {
                        $('.alert-container').html('<div class="alert alert-error">Gagal memuat data buku untuk diedit.</div>');
                        $('#form-buku button').prop('disabled', true);
                    });
            }

            $('#form-buku').on('submit', function(e) {
                e.preventDefault();
                const submitButton = $(this).find('button[type="submit"]');
                submitButton.prop('disabled', true).text('Menyimpan...');
                
                const formData = new FormData();
                formData.append('idBuku', bookId);
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
                    url: `${API_BASE_URL}/updateBuku`, // Endpoint untuk update
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhrFields: { withCredentials: true },
                    success: function() {
                        window.location.href = `index.php?status=success&pesan=Buku ID ${bookId} berhasil diperbarui.`;
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        const errorMsg = "Gagal memperbarui buku. Silakan coba lagi.";
                        $('.alert-container').html(`<div class="alert alert-error">${errorMsg}</div>`);
                        submitButton.prop('disabled', false).text('Update Buku');
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

            loadBookData();
        });
    </script>
</body>
</html>
