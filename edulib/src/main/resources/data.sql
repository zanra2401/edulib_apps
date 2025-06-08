-- Data user (admin, pustakawan, dan siswa SMP)
INSERT INTO `user` (`id_user`, `email`, `nama_lengkap`, `nisn`, `password_user`, `username`, `peran`) VALUES
	(1, 'admin@edulib.com', 'Admin Edulib', null, '$2a$10$HELk3x9KJqI2c3Z8gzg5x.IAW.i01i001m.ZmLuG3iDmOHGBckx1G', 'admin', 'ADMIN'),
	(2, 'pustaka@edulib.com', 'Pustakawan Budi', null, '$2a$10$HELk3x9KJqI2c3Z8gzg5x.IAW.i01i001m.ZmLuG3iDmOHGBckx1G', 'pustaka', 'PUSTAKAWAN'),
	(3, 'grexgrub@gmail.com', 'Zanuar Rikza Aditiya', '24012005', '$2a$10$HELk3x9KJqI2c3Z8gzg5x.IAW.i01i001m.ZmLuG3iDmOHGBckx1G', null, 'SISWA');

-- Data buku untuk kategori pelajaran SMP
INSERT INTO `buku` (`id_buku`, `jumlah_stok`, `tahun_terbit`, `judul`, `kategori`, `lokasi_rak`, `path_gambar`, `penerbit`, `penulis`) VALUES
	(1, 5, 2018, 'Kala Itu Langit Biru', 'Fiksi', 'B1', '1.png', 'Pusat Buku Edukasi', 'Ibu Kartika'),
	(2, 4, 2019, 'Anual Repor', 'Ekonomi', 'B3', 'anual_report.png', 'Pendidikan Nusantara', 'Dedi Hidayat'),
	(3, 6, 2017, 'Cerita Dari Negri Dongeng', 'Fiksi', 'C2', 'cerita_dari_negri_dongeng_fiksi.png', 'Ilmu Cendekia', 'Rina Wulandari'),
	(4, 5, 2021, 'Harapan Baru', 'Non Fiksi', 'C3', 'harapan_baru_nf.png', 'Edu Prima', 'Bambang Hartono'),
	(5, 7, 2020, 'Laporan Keuangan', 'Ekonomi', 'D1', 'laporan_keuangan.png', 'Bahasa Bangsa', 'Sri Utami'),
	(6, 6, 2021, 'Merajut Mimpi', 'Cerpen', 'D2', 'merjut_mimpi_cerpen.png', 'Global Education', 'William T. Aji'),
	(7, 6, 2015, 'Modal Ajar MTK ', 'mtk', 'D2', 'modul_ajar_mtk.png', 'Global Education', 'William T. Aji');


-- Data peminjaman (beragam status)
INSERT INTO `peminjaman` (`id_peminjaman`, `id_user`, `tanggal_estimasi_kembali`, `tanggal_pinjam`, `alasan_penolakan`, `status`) VALUES
	(1, 3, '2025-06-01', '2025-05-25', NULL, 'dipinjam'),
	(2, 3, '2025-05-30', '2025-05-23', NULL, 'kembali'),
	(3, 3, '2025-05-26', '2025-05-19', 'Stok tidak tersedia', 'ditolak'),
	(4, 3, '2025-06-03', '2025-05-27', NULL, 'menunggu');


-- Detail peminjaman
INSERT INTO `detail_peminjaman` (`id_buku`, `id_detail`, `id_peminjaman`, `status_detail`) VALUES
	(1, 1, 1, 'dipinjam'),
	(2, 2, 2, 'kembali'),
	(3, 3, 3, 'dipinjam'),
	(4, 4, 4, 'dipinjam');


-- Pengembalian (satu kasus)
INSERT INTO `pengembalian` (`id_detail`, `id_pengembalian`, `tanggal_kembali`, `status_denda`) VALUES
	(2, 1, '2025-05-30', 'dibayar');


-- Denda keterlambatan
INSERT INTO `denda` (`id_denda`, `id_pengembalian`, `jumlah_hari_terlambat`, `total_nominal`) VALUES
	(1, 1, 2, 2000.00);


-- Notifikasi (beragam jenis)
INSERT INTO `notifikasi` (`id_detail`, `id_notifikasi`, `id_user`, `tanggal_kirim`, `isi_pesan`, `jenis_notifikasi`) VALUES
	(1, 1, 3, '2025-05-28 10:00:00', 'Harap segera kembalikan buku Sejarah Indonesia SMP', 'pengingat'),
	(2, 2, 3, '2025-05-30 08:00:00', 'Terlambat mengembalikan buku IPS Terpadu', 'terlambat');
