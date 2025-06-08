package com.edulib.edulib.models;

import java.time.LocalDate;
import java.util.List;

import jakarta.persistence.Entity;
import jakarta.persistence.EnumType;
import jakarta.persistence.Enumerated;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.persistence.JoinColumn;
import jakarta.persistence.ManyToOne;
import jakarta.persistence.OneToMany;
import jakarta.persistence.Table;
import lombok.Getter;
import lombok.Setter;

@Entity
@Table(name = "peminjaman")
@Getter
@Setter
public class Peminjaman {
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Id
    private Integer idPeminjaman;

    @ManyToOne
    @JoinColumn(name = "id_user")
    private User user;

    private LocalDate tanggalPinjam;
    private LocalDate tanggalEstimasiKembali;

    @Enumerated(EnumType.STRING)
    private StatusPeminjaman status;

    private String alasanPenolakan;

    @OneToMany(mappedBy = "peminjaman")
    private List<DetailPeminjaman> detailPeminjamanList;

    public enum StatusPeminjaman {
        menunggu, dipinjam, ditolak, kembali
    }

    public Peminjaman() {
    }

    public Peminjaman(User user, LocalDate pinjam, LocalDate kembali) {
        this.user = user;
        this.tanggalPinjam = pinjam;
        this.tanggalEstimasiKembali = kembali;
        this.status = StatusPeminjaman.menunggu;
    }
}

