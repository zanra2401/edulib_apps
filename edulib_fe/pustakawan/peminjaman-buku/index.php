<?php require_once("../../config/global.php"); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Buku - EDULIB</title>
    <link rel="stylesheet" href=<?= BASE_LINK . 'libs/css/bootstrap.css' ?>>
    <link rel="stylesheet" href=<?= BASE_LINK . 'libs/css/spin.css' ?>>
    <link rel="stylesheet" href=<?= BASE_LINK . 'static/pustakawanbuku.css' ?>>
    <script src=<?= BASE_LINK . 'libs/js/jquery.js' ?>></script>
    <script src=<?= BASE_LINK . "static/cekLoginPustaka.js" ?>></script>
    <style>
        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container-p">
        <aside class="sidebar">
            <h2>EDULIB</h2>
            <nav class="sidebar-nav">
                <a href="../buku/index.php">Buku</a>
                <a href="index.php" class="active">Peminjaman Buku</a>
                <a href="../pengembalian-buku/index.php">Pengembalian Buku</a>
                <a href="../laporan-perpustakaan/index.php">Laporan Perpustakaan</a>
            </nav>
            <div class="logout-btn" id="logout">Logout</div>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>Daftar Peminjaman Buku</h1>
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
                            <th>NISN</th><th>Judul Buku</th><th>Tgl Pinjam</th><th>Estimasi Kembali</th><th>Status</th><th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="peminjaman-table-body">
                        <tr><td colspan="6" style="text-align:center;">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <div id="reject-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Tolak Peminjaman</h2>
                <button class="modal-close-btn" id="close-modal">&times;</button>
            </div>
            <form id="reject-form">
                <input type="hidden" id="peminjaman-id-reject">
                <div class="form-group">
                    <label for="reject-reason" class="form-label">Alasan Penolakan</label>
                    <textarea id="reject-reason" class="form-control" placeholder="Tuliskan alasan mengapa peminjaman ini ditolak..." rows="4" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancel-reject">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Peminjaman</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const API_BASE_URL = "http://localhost:8080/edulib/buku";

            // Fungsi untuk memuat data peminjaman
            function loadPeminjaman() {
                $.get(`${API_BASE_URL}/peminjaman`)
                    .done(function(response) {
                        const tableBody = $('#peminjaman-table-body');
                        tableBody.empty();
                        if (response.peminjaman && response.peminjaman.length > 0) {
                            response.peminjaman.forEach(p => {
                                const status = p[5].toLowerCase().replace(' ', '-');
                                const isPending = p[5] === 'menunggu';
                                
                                const row = `
                                    <tr>
                                        <td>${p[1]}</td>
                                        <td>${p[2]}</td>
                                        <td>${new Date(p[3]).toLocaleDateString('id-ID')}</td>
                                        <td>${new Date(p[4]).toLocaleDateString('id-ID')}</td>
                                        <td><span class="status-badge status-${status}">${p[5]}</span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-primary btn-approve" data-id="${p[0]}" ${!isPending ? 'disabled' : ''}>Setujui</button>
                                                <button class="btn btn-danger btn-reject" data-id="${p[0]}" ${!isPending ? 'disabled' : ''}>Tolak</button>
                                            </div>
                                        </td>
                                    </tr>
                                `;
                                tableBody.append(row);
                            });
                        } else {
                            tableBody.html('<tr><td colspan="6" style="text-align:center;">Belum ada data peminjaman.</td></tr>');
                        }
                    })
                    .fail(function() {
                        $('#peminjaman-table-body').html('<tr><td colspan="6" style="text-align:center;color:red;">Gagal memuat data.</td></tr>');
                    });
            }

            // --- Event Handlers ---

            // Menyetujui peminjaman
            $(document).on('click', '.btn-approve', function() {
                const peminjamanId = $(this).data('id');
                if (confirm(`Setujui peminjaman ID: ${peminjamanId}?`)) {
                    $.get(`${API_BASE_URL}/setujui/${peminjamanId}`)
                        .done(function() {
                            window.location.href = 'index.php?status=success&pesan=Peminjaman berhasil disetujui.';
                        })
                        .fail(function() {
                            alert('Gagal menyetujui peminjaman.');
                        });
                }
            });

            // Membuka modal penolakan
            $(document).on('click', '.btn-reject', function() {
                const peminjamanId = $(this).data('id');
                $('#peminjaman-id-reject').val(peminjamanId);
                $('#reject-modal').fadeIn(200);
            });

            // Menutup modal
            function closeModal() {
                $('#reject-modal').fadeOut(200);
                $('#reject-form')[0].reset();
            }
            $('#close-modal, #cancel-reject').on('click', closeModal);

            // Mengirim data penolakan dari form modal
            $('#reject-form').on('submit', function(e) {
                e.preventDefault();
                const btn = $(this).find('button[type="submit"]');
                btn.prop('disabled', true).text('Memproses...');

                const dataTolak = {
                    idPeminjaman: $('#peminjaman-id-reject').val(),
                    alasan: $('#reject-reason').val()
                };

                $.ajax({
                    url: `${API_BASE_URL}/tolak`,
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(dataTolak),
                    xhrFields: { withCredentials: true }
                })
                .done(function() {
                    window.location.href = `index.php?status=success&pesan=Peminjaman ID: ${dataTolak.idPeminjaman} telah ditolak.`;
                })
                .fail(function() {
                    alert('Gagal mengirim data penolakan.');
                })
                .always(function() {
                    btn.prop('disabled', false).text('Tolak Peminjaman');
                });
            });

            // Logout
            $('#logout').on('click', function() {
                $.ajax({
                    url: "http://localhost:8080/edulib/auth/logout",
                    type: "get",
                    xhrFields: { withCredentials: true }
                })
                .done(function() {
                    window.location.href = "../login/index.php"; // Arahkan ke halaman login
                })
                .fail(function() {
                    alert('Logout gagal.');
                });
            });

            // Muat data awal
            loadPeminjaman();
        });
    </script>
</body>
</html>