package com.edulib.edulib.repository;

import java.util.List;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;

import com.edulib.edulib.models.Notifikasi;

@Repository
public interface NotifikasiRepository extends JpaRepository<Notifikasi, Long> {
    @Query("""
            SELECT n.isiPesan, n.tanggalKirim FROM Notifikasi n WHERE n.user.id = :userId 
            """)
    List<Object[]> getNotifikasiByUser(@Param("userId") Long userId);
}
