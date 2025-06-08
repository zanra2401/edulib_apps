package com.edulib.edulib.models;

import java.time.LocalDate;

import jakarta.persistence.Entity;
import jakarta.persistence.EnumType;
import jakarta.persistence.Enumerated;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.persistence.JoinColumn;
import jakarta.persistence.OneToOne;
import jakarta.persistence.Table;
import lombok.Getter;
import lombok.Setter;

@Entity
@Table(name = "pengembalian")
@Getter
@Setter
public class Pengembalian {
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Id
    private Integer idPengembalian;

    @OneToOne
    @JoinColumn(name = "id_detail")
    private DetailPeminjaman detail;

    private LocalDate tanggalKembali;


    @Enumerated(EnumType.STRING)
    private StatusDenda statusDenda;
    public enum StatusDenda {
        belum_dibayar, dibayar
    }

    @OneToOne(mappedBy = "pengembalian")
    private Denda denda;

    public Pengembalian() {
    }
}

