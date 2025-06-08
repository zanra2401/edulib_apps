package com.edulib.edulib.dto;

import java.time.LocalDate;

import lombok.Getter;
import lombok.Setter;

@Getter
@Setter
public class BukuDipinjamResponse {
    private String judul;
    private LocalDate tanggalTenggat;
    private boolean terlambat;
    private String pathGambar;


    public BukuDipinjamResponse(String judul, LocalDate tanggalTenggat, boolean terlambat, String pathGambar) {
        this.judul = judul;
        this.tanggalTenggat = tanggalTenggat;
        this.terlambat = terlambat;
        this.pathGambar = pathGambar;
    }
}

