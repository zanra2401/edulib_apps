package com.edulib.edulib.dto;

import java.time.LocalDate;

import lombok.Getter;
import lombok.Setter;

@Getter
@Setter
public class RiwayatResponse {
    private String judulBuku;
    private LocalDate diPinjam;
    private LocalDate kembali;
    private String penulis;
    private String pathGambar;

    public RiwayatResponse(String judul, LocalDate diPinjam, LocalDate dikembalikan, String penulis, String pathGambar) {
        this.judulBuku = judul;
        this.diPinjam = diPinjam;
        this.kembali = dikembalikan;
        this.penulis = penulis;
        this.pathGambar = pathGambar;
    }
}
