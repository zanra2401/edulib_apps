package com.edulib.edulib.dto;

public class BukuPopulerDTO {
    private String judul;
    private Long jumlahPeminjaman;

    public BukuPopulerDTO(String judul, Long jumlahPeminjaman) {
        this.judul = judul;
        this.jumlahPeminjaman = jumlahPeminjaman;
    }

    public String getJudul() {
        return judul;
    }

    public Long getJumlahPeminjaman() {
        return jumlahPeminjaman;
    }
}

