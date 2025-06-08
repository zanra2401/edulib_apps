package com.edulib.edulib.dto;



public class PeminjamanPerBulanDTO {
    private String bulan;
    private Long total;

    public PeminjamanPerBulanDTO(String bulan, Long total) {
        this.bulan = bulan;
        this.total = total;
    }

    public String getBulan() { return bulan; }
    public Long getTotal() { return total; }
}
