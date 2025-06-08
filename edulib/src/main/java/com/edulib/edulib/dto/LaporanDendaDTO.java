package com.edulib.edulib.dto;

import java.math.BigDecimal;

public class LaporanDendaDTO {
    private String bulan;
    private BigDecimal totalDenda;

    public LaporanDendaDTO(String bulan, BigDecimal totalDenda) {
        this.bulan = bulan;
        this.totalDenda = totalDenda;
    }

    public String getBulan() {
        return bulan;
    }

    public void setBulan(String bulan) {
        this.bulan = bulan;
    }

    public BigDecimal getTotalDenda() {
        return totalDenda;
    }

    public void setTotalDenda(BigDecimal totalDenda) {
        this.totalDenda = totalDenda;
    }
}

