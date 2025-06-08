package com.edulib.edulib.repository;

import org.springframework.data.jpa.repository.JpaRepository;

import com.edulib.edulib.models.DetailPeminjaman;
import com.edulib.edulib.models.Pengembalian;

public interface PengembalianRepository extends JpaRepository<Pengembalian, Long> {
    Pengembalian findByDetail(DetailPeminjaman detail);
}
