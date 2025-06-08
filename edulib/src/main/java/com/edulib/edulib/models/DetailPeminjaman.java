package com.edulib.edulib.models;

import com.fasterxml.jackson.annotation.JsonBackReference;

import jakarta.persistence.Entity;
import jakarta.persistence.EnumType;
import jakarta.persistence.Enumerated;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.persistence.JoinColumn;
import jakarta.persistence.ManyToOne;
import jakarta.persistence.OneToOne;
import jakarta.persistence.Table;
import lombok.Getter;
import lombok.Setter;

@Entity
@Table(name = "detail_peminjaman")
@Getter
@Setter
public class DetailPeminjaman {
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Id
    private Integer idDetail;

    @ManyToOne
    @JsonBackReference
    @JoinColumn(name = "id_buku")
    private Buku buku;

    @ManyToOne
    @JsonBackReference
    @JoinColumn(name = "id_peminjaman")
    private Peminjaman peminjaman;

    @Enumerated(EnumType.STRING)
    private StatusDetail statusDetail;

    public enum StatusDetail {
        dipinjam, kembali
    }

    @OneToOne(mappedBy = "detail")
    private Pengembalian pengembalian;

    public DetailPeminjaman(Buku buku, Peminjaman peminjaman) {
        this.buku = buku;
        this.peminjaman = peminjaman;
        this.statusDetail = StatusDetail.dipinjam;
    }

    public DetailPeminjaman() {
        
    }

}

