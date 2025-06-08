package com.edulib.edulib.dto;


import lombok.Getter;
import lombok.Setter;

@Setter
@Getter
public class BukuUpdateDTO {
    private Long idBuku;
    private String judul;
    private String penulis;
    private String penerbit;
    private Long tahunTerbit;
    private String kategori;
    private Integer jumlahStok;
    private String lokasiRak;
    private String pathGambar;
}

