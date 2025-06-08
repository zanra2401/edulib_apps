package com.edulib.edulib.dto;


import lombok.Getter;
import lombok.Setter;


@Getter
@Setter
public class BukuResponse {
    private Integer idBuku;
    private String judul;
    private String penulis;
    private String penerbit;
    private Long tahunTerbit;
    private String kategori;
    private Integer jumlahStok;
    private String lokasiRak;
    private String pathGambar;

    public BukuResponse() {
    }

    public BukuResponse(Integer idBuku, String judul, String penulis, String penerbit,
                        Long tahunTerbit, String kategori, Integer jumlahStok, String lokasiRak, String pathGambar) {
        this.idBuku = idBuku;
        this.judul = judul;
        this.penulis = penulis;
        this.penerbit = penerbit;
        this.tahunTerbit = tahunTerbit;
        this.kategori = kategori;
        this.jumlahStok = jumlahStok;
        this.lokasiRak = lokasiRak;
        this.pathGambar = pathGambar;
    }
}

