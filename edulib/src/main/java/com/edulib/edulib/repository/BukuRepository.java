package com.edulib.edulib.repository;

import java.util.List;

import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;

import com.edulib.edulib.dto.BukuDipinjamResponse;
import com.edulib.edulib.dto.BukuPopulerDTO;
import com.edulib.edulib.dto.BukuResponse;
import com.edulib.edulib.dto.DendaResponse;
import com.edulib.edulib.dto.RiwayatResponse;
import com.edulib.edulib.models.Buku;

@Repository
public interface BukuRepository extends JpaRepository<Buku, Long> {
    @Query(value = "SELECT DATE_FORMAT(tanggal_pinjam, '%Y-%m') AS bulan, COUNT(*) AS total " +
               "FROM peminjaman GROUP BY bulan ORDER BY bulan", nativeQuery = true)
    List<Object[]> findPeminjamanPerBulan();

    @Query("SELECT new com.edulib.edulib.dto.BukuPopulerDTO(b.judul, COUNT(dp)) " +
       "FROM DetailPeminjaman dp " +
       "JOIN dp.buku b " +
       "GROUP BY b.judul " +
       "ORDER BY COUNT(dp) DESC")
    List<BukuPopulerDTO> findTop10BukuPalingPopuler(Pageable pageable);

    @Query("SELECT COUNT(p) FROM Peminjaman p WHERE p.user.idUser = :idUser AND p.status = 'dipinjam'")
    Long countBukuDipinjamByUser(@Param("idUser") Long idUser);

    @Query("SELECT COUNT(p) FROM Peminjaman p WHERE p.tanggalEstimasiKembali < NOW() AND p.user.idUser = :idUser AND status = 'dipinjam'")
    Long coutBukuTerlambatByUser(@Param("idUser") Long idUser);

    @Query("SELECT new com.edulib.edulib.dto.BukuResponse(b.idBuku, b.judul, b.penulis, b.penerbit, b.tahunTerbit, b.kategori, b.jumlahStok, b.lokasiRak, b.pathGambar) FROM Buku b")
    Page<BukuResponse> findAllBuku(Pageable page); 

    @Query("""
    SELECT new com.edulib.edulib.dto.BukuDipinjamResponse(
        d.buku.judul,
        d.peminjaman.tanggalEstimasiKembali,
        CASE WHEN d.peminjaman.tanggalEstimasiKembali < NOW() THEN true ELSE false END,
        d.buku.pathGambar
    )
    FROM DetailPeminjaman d
    WHERE d.peminjaman.user.id = :userId AND d.peminjaman.status = 'dipinjam'
    """)
    List<BukuDipinjamResponse> findBukuDipinjamUser(Long userId);

    @Query("""
        SELECT new com.edulib.edulib.dto.BukuResponse(b.idBuku, b.judul, b.penulis, b.penerbit, b.tahunTerbit, b.kategori, b.jumlahStok, b.lokasiRak, b.pathGambar) FROM Buku b
        WHERE (:judul IS NULL OR LOWER(b.judul) LIKE LOWER(CONCAT('%', :judul, '%')))
          AND (((:kategoriList IS NULL AND :penulisList IS NULL) OR b.kategori IN :kategoriList) 
          OR ((:penulisList IS NULL AND :kategoriList IS NULL) OR b.penulis IN :penulisList))
    """)
    List<BukuResponse> searchBuku(
        @Param("judul") String judul,
        @Param("kategoriList") List<String> kategoriList,
        @Param("penulisList") List<String> penulisList
    );

    @Query("""
            SELECT new com.edulib.edulib.dto.BukuResponse(d.buku.idBuku, d.buku.judul, d.buku.penulis, d.buku.penerbit, d.buku.tahunTerbit, d.buku.kategori, d.buku.jumlahStok, d.buku.lokasiRak, d.buku.pathGambar) FROM DetailPeminjaman d
            WHERE d.peminjaman.user.idUser = :userId AND d.peminjaman.status = 'menunggu'
            """)
    List<BukuResponse> getBukuDiAjukan(
        @Param("userId") Long id
    );

    @Query("""
            SELECT new com.edulib.edulib.dto.RiwayatResponse(p.detail.buku.judul, p.detail.peminjaman.tanggalPinjam, p.tanggalKembali, p.detail.buku.penulis, p.detail.buku.pathGambar) FROM Pengembalian p
            WHERE p.detail.peminjaman.user.idUser = :userId
            """)
    List<RiwayatResponse> getRiwayet(
        @Param("userId") Long id
    );

    @Query("""
        SELECT new com.edulib.edulib.dto.DendaResponse(
            d.buku.judul,
            d.peminjaman.tanggalEstimasiKembali,
            d.buku.pathGambar
        )
        FROM DetailPeminjaman d
        WHERE d.peminjaman.user.idUser = :userId
        AND NOW() > d.peminjaman.tanggalEstimasiKembali AND d.peminjaman.status = 'dipinjam'
    """)
    List<DendaResponse> getD(@Param("userId") Long userId);

    @Query("SELECT b.idBuku, b.judul, b.penulis, b.penerbit, b.tahunTerbit, b.kategori, b.jumlahStok - COALESCE(COUNT(dp), 0), b.lokasiRak, b.pathGambar " +
       "FROM Buku b LEFT JOIN b.detailPeminjamanList dp " +
       "ON dp.statusDetail = 'dipinjam' " +
       "GROUP BY b.idBuku, b.judul, b.jumlahStok, b.penulis, b.penerbit, b.tahunTerbit, b.kategori, b.lokasiRak")
    List<Object[]> getStatuskBuku();

    @Query("""
        SELECT new com.edulib.edulib.dto.DendaResponse(
            d.buku.judul,
            d.peminjaman.tanggalEstimasiKembali,
            d.buku.pathGambar
        )
        FROM DetailPeminjaman d
        WHERE d.peminjaman.user.idUser = :userId
        AND (CURRENT_DATE > d.peminjaman.tanggalEstimasiKembali
        AND d.peminjaman.status = 'dipinjam') OR (d.peminjaman.status = 'kembali' AND NOT EXISTS (
            SELECT 1 FROM Pengembalian p WHERE p.detail.idDetail = d.idDetail AND p.statusDenda = 'dibayar'
        ))
    """)
    List<DendaResponse> getDenda(@Param("userId") Long userId);



    @Query("SELECT b.idBuku, b.judul, b.penulis, b.penerbit, b.tahunTerbit, b.kategori, " +
       "(b.jumlahStok - COALESCE(COUNT(dp), 0)) AS stokTersedia, b.lokasiRak " +
       "FROM Buku b LEFT JOIN b.detailPeminjamanList dp ON dp.statusDetail = 'dipinjam' " +
       "WHERE b.idBuku = :bukuId " +
       "GROUP BY b.idBuku, b.judul, b.penulis, b.penerbit, b.tahunTerbit, b.kategori, b.jumlahStok, b.lokasiRak")
    Object[] getOneStatusBuku(@Param("bukuId") Long bukuId);

    @Query("""
    SELECT dp.idDetail,
           dp.peminjaman.user.nisn,
           dp.buku.judul,
           dp.peminjaman.tanggalPinjam,
           dp.peminjaman.tanggalEstimasiKembali,
           dp.peminjaman.status,
           COALESCE(
               (SELECT d.totalNominal
               FROM Denda d
               WHERE d.pengembalian.idPengembalian = dp.pengembalian.idPengembalian), NULL
           ),
           COALESCE(
                (SELECT k.tanggalKembali
                FROM Pengembalian k
                WHERE k.detail.pengembalian.idPengembalian = dp.pengembalian.idPengembalian), NULL
           ),
           dp.buku.pathGambar
        FROM DetailPeminjaman dp
    """)
    List<Object[]> getPeminjaman();

    @Query("""
            SELECT d.buku.idBuku, d.buku.judul, d.peminjaman.alasanPenolakan, d.buku.pathGambar FROM DetailPeminjaman d
            WHERE d.peminjaman.user.idUser = :userId AND d.peminjaman.status = 'ditolak'
            """)
    List<Object[]> getPenolakan(
        @Param("userId") Long id
    );

    @Query("""
            SELECT DISTINCT b.kategori FROM Buku b
            """)
    List<Object[]> getKategori();

    @Query("""
            SELECT DISTINCT b.penulis FROM Buku b
            """)
    List<Object[]> getPenulis();
}