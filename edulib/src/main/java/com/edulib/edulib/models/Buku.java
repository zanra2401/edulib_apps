package com.edulib.edulib.models;

import java.util.List;

import com.edulib.edulib.dto.BukuDTO;

import jakarta.persistence.Entity;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.persistence.OneToMany;
import jakarta.persistence.Table;
import lombok.Getter;
import lombok.Setter;
import com.fasterxml.jackson.annotation.JsonManagedReference;


@Entity
@Table(name = "buku")
@Getter
@Setter
public class Buku {
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Id
    private Integer idBuku;

    private String judul;
    private String penulis;
    private String penerbit;
    private Long tahunTerbit;
    private String kategori;
    private Integer jumlahStok;
    private String lokasiRak;
    private String pathGambar;

    public void setPathGambar(String pathGambar) {
        this.pathGambar = pathGambar;
    }

    @JsonManagedReference
    @OneToMany(mappedBy = "buku")
    private List<DetailPeminjaman> detailPeminjamanList;

    public Buku() {

    }

    public Buku(BukuDTO buku) {
        judul = buku.getJudul();
        penulis = buku.getPenulis();
        penerbit = buku.getPenerbit();
        tahunTerbit = buku.getTahunTerbit();
        kategori = buku.getKategori();
        jumlahStok = buku.getJumlahStok();
        lokasiRak = buku.getLokasiRak();
    }
}
