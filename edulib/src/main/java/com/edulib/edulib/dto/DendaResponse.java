package com.edulib.edulib.dto;

import java.math.BigDecimal;
import java.time.LocalDate;
import java.time.temporal.ChronoUnit;

import lombok.Getter;
import lombok.Setter;

@Setter
@Getter
public class DendaResponse {
    private String judul;
    private BigDecimal denda;
    private String pathGambar;

    public DendaResponse(String judul, LocalDate day, String pathGambar) {
        this.judul = judul;
        LocalDate now = LocalDate.now();
        this.denda = BigDecimal.valueOf(ChronoUnit.DAYS.between(day, now) * 500);
        this.pathGambar = pathGambar;
    }
}
