<?php require_once("../../config/global.php"); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengembalian Buku - EDULIB</title>
      <link rel="stylesheet" href=<?= BASE_LINK . 'libs/css/bootstrap.css' ?>>
    <link rel="stylesheet" href=<?= BASE_LINK . 'libs/css/spin.css' ?>>
    <link rel="stylesheet" href=<?= BASE_LINK . 'static/pustakawanbuku.css' ?>>
    <script src=<?= BASE_LINK . 'libs/js/jquery.js' ?>></script>
    <script src=<?= BASE_LINK . "static/cekLoginPustaka.js" ?>></script>
    <link rel="stylesheet" href="../buku/pustakawan_style.css">
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>
<body>
    <div class="container-p">
        <aside class="sidebar">
            <h2>EDULIB</h2>
            <nav class="sidebar-nav">
                <a href="../buku/index.php">Buku</a>
                <a href="../peminjaman-buku/index.php">Peminjaman Buku</a>
                <a href="index.php" class="active">Pengembalian Buku</a>
                <a href="../laporan-perpustakaan/index.php">Laporan Perpustakaan</a>
            </nav>
            <div class="logout-btn" id="logout">Logout</div>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>Pengembalian & Denda</h1>
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
                            <th>NISN</th>
                            <th>Judul Buku</th>
                            <th>Tgl Kembali</th>
                            <th>Status Buku</th>
                            <th>Denda</th>
                            <th>Status Denda</th>
                        </tr>
                    </thead>
                    <tbody id="pengembalian-table-body">
                        <tr><td colspan="6" style="text-align:center;">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <div id="return-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Konfirmasi Pengembalian</h2>
                <button class="modal-close-btn" data-close-modal>&times;</button>
            </div>
            <p>Anda akan menandai buku ini sebagai "Dikembalikan". Lanjutkan?</p>
            <form id="return-form">
                <input type="hidden" id="return-peminjaman-id">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-close-modal>Batal</button>
                    <button type="submit" class="btn btn-primary">Ya, Konfirmasi</button>
                </div>
            </form>
        </div>
    </div>

    <div id="payment-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Konfirmasi Pembayaran Denda</h2>
                <button class="modal-close-btn" data-close-modal>&times;</button>
            </div>
            <p>Konfirmasi bahwa denda sebesar <b id="denda-amount-text"></b> telah dibayar lunas.</p>
            <form id="payment-form">
                <input type="hidden" id="payment-peminjaman-id">
                <input type="hidden" id="payment-denda-amount">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-close-modal>Batal</button>
                    <button type="submit" class="btn btn-primary">Ya, Sudah Dibayar</button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
    $(document).ready(function() {
        const API_BASE_URL = "http://localhost:8080/edulib/buku";

        // --- FUNGSI-FUNGSI HELPER UNTUK KEBERSIHAN KODE ---

        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
        }

        function calculateDenda(tglEstimasi, tglKembali, statusPeminjaman) {
            const estimasi = new Date(tglEstimasi);
            // Jika belum dikembalikan, bandingkan dengan hari ini
            const kembali = (statusPeminjaman !== "kembali") ? new Date() : new Date(tglKembali);
            
            // Hitung selisih hari, hanya jika tanggal kembali > tanggal estimasi
            const diffTime = Math.max(0, kembali - estimasi);
            const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
            
            return diffDays * 500; // Denda 500 per hari
        }

        // --- FUNGSI RENDER TAMPILAN ---

        function renderTableRow(p) {
            // [MODIFIKASI] Destructuring array agar lebih mudah dibaca
            const [
                idPeminjaman, nisn, judulBuku, tglPinjam, 
                tglEstimasi, statusPeminjaman, idPembayaran, tglKembali
            ] = p;

            const denda = calculateDenda(tglEstimasi, tglKembali, statusPeminjaman);
            
            // Logika untuk menampilkan tombol atau status
            let kolomAksiHtml = '';
            if (statusPeminjaman !== 'kembali') {
                kolomAksiHtml = `<button class="btn btn-primary btn-kembalikan" data-id="${idPeminjaman}">Kembalikan</button>`;
            } else {
                kolomAksiHtml = `<span class="status-badge status-dikembalikan">Dikembalikan</span>`;
            }

            let kolomDendaStatusHtml = '';
            if (denda > 0) {
                if (statusPeminjaman === 'kembali') {
                    if (idPembayaran) {
                        kolomDendaStatusHtml = `<span class="status-badge status-dipinjam">Lunas</span>`; // Pakai style hijau
                    } else {
                        kolomDendaStatusHtml = `<button class="btn btn-danger btn-bayar-denda" data-id="${idPeminjaman}" data-denda="${denda}">Bayar Denda</button>`;
                    }
                } else {
                    kolomDendaStatusHtml = `<span class="status-badge status-terlambat">Belum Lunas</span>`;
                }
            } else {
                kolomDendaStatusHtml = `-`;
            }

            return `
                <tr>
                    <td>${nisn}</td>
                    <td>${judulBuku}</td>
                    <td>${tglKembali ? new Date(tglKembali).toLocaleDateString('id-ID') : 'Belum Kembali'}</td>
                    <td>${kolomAksiHtml}</td>
                    <td>${formatCurrency(denda)}</td>
                    <td>${kolomDendaStatusHtml}</td>
                </tr>
            `;
        }

        // --- FUNGSI UTAMA & AJAX ---

        function loadPengembalian() {
            $.get(`${API_BASE_URL}/peminjaman`)
                .done(function(response) {
                    const tableBody = $('#pengembalian-table-body');
                    tableBody.empty();
                    if (response.peminjaman && response.peminjaman.length > 0) {
                        // Hanya tampilkan yang sudah disetujui
                        const dipinjamList = response.peminjaman.filter(p => p[5] !== 'menunggu' && p[5] !== 'ditolak');
                        if(dipinjamList.length > 0) {
                            dipinjamList.forEach(p => {
                                tableBody.append(renderTableRow(p));
                            });
                        } else {
                             tableBody.html('<tr><td colspan="6" style="text-align:center;">Tidak ada buku yang sedang dipinjam.</td></tr>');
                        }
                    } else {
                        tableBody.html('<tr><td colspan="6" style="text-align:center;">Tidak ada data peminjaman.</td></tr>');
                    }
                })
                .fail(function() {
                    $('#pengembalian-table-body').html('<tr><td colspan="6" style="text-align:center;color:red;">Gagal memuat data.</td></tr>');
                });
        }

        // --- EVENT HANDLERS ---
        
        // Membuka modal pengembalian
        $(document).on('click', '.btn-kembalikan', function() {
            const id = $(this).data('id');
            $('#return-peminjaman-id').val(id);
            $('#return-modal').fadeIn(200);
        });

        // Membuka modal pembayaran denda
        $(document).on('click', '.btn-bayar-denda', function() {
            const id = $(this).data('id');
            const denda = $(this).data('denda');
            $('#payment-peminjaman-id').val(id);
            $('#payment-denda-amount').val(denda);
            $('#denda-amount-text').text(formatCurrency(denda));
            $('#payment-modal').fadeIn(200);
        });
        
        // Menutup semua modal
        $('[data-close-modal]').on('click', function() {
            $('.modal').fadeOut(200);
        });

        // Aksi form pengembalian
        $('#return-form').on('submit', function(e) {
            e.preventDefault();
            const id = $('#return-peminjaman-id').val();
            $.get(`${API_BASE_URL}/kembalikan/${id}`)
                .done(() => window.location.href = 'index.php?status=success&pesan=Buku berhasil ditandai sebagai dikembalikan.')
                .fail(() => alert('Gagal memproses pengembalian.'));
        });

        // Aksi form pembayaran denda
        $('#payment-form').on('submit', function(e) {
            e.preventDefault();
            const id = $('#payment-peminjaman-id').val();
            const denda = $('#payment-denda-amount').val();
            $.get(`${API_BASE_URL}/bayar/${id}/${denda}`)
                .done(() => window.location.href = `index.php?status=success&pesan=Pembayaran denda untuk peminjaman ID ${id} berhasil.`)
                .fail(() => alert('Gagal memproses pembayaran denda.'));
        });

        // Logout
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
        loadPengembalian();
    });
    </script>
</body>
</html>