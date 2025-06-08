package com.edulib.edulib.repository;

import org.springframework.data.jpa.repository.JpaRepository;

import com.edulib.edulib.models.DetailPeminjaman;

public interface DetailPeminjamanRepository extends JpaRepository<DetailPeminjaman, Long> {
    
}
