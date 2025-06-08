package com.edulib.edulib.repository;

import java.util.List;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;

import com.edulib.edulib.dto.DendaInfoDTO;
import com.edulib.edulib.models.Denda;

public interface DendaRepository extends JpaRepository<Denda, Integer> {
    // Di DendaRepository
    @Query("SELECT new com.edulib.edulib.dto.DendaInfoDTO(" +
        "d.idDenda, u.idUser, b.idBuku, d.jumlahHariTerlambat, d.totalNominal, p.statusDenda) " +
        "FROM Denda d " +
        "JOIN d.pengembalian p " +
        "JOIN p.detail dp " +
        "JOIN dp.buku b " +
        "JOIN dp.peminjaman pm " +
        "JOIN pm.user u")
    List<DendaInfoDTO> findAllDendaInfo();

    // Native Query untuk laporan denda per bulan
    @Query(value = """
        SELECT 
            DATE_FORMAT(p.tanggal_kembali, '%Y-%m') AS bulan,
            SUM(d.total_nominal) AS totalDenda
        FROM denda d
        JOIN pengembalian p ON d.id_pengembalian = p.id_pengembalian
        GROUP BY bulan
        ORDER BY bulan
    """, nativeQuery = true)
    List<Object[]> findLaporanDendaPerBulanRaw();
}

