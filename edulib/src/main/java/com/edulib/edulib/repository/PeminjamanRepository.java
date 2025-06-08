package com.edulib.edulib.repository;

import org.springframework.data.jpa.repository.JpaRepository;

import com.edulib.edulib.models.Peminjaman;

public interface PeminjamanRepository extends JpaRepository<Peminjaman, Long> {
    
}
