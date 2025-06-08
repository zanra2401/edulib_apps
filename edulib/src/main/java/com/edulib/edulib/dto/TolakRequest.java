package com.edulib.edulib.dto;

import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;

public class TolakRequest {
    @NotBlank
    private String alasan;

    @NotNull
    private Long idPeminjaman;

    public Long getIdPeminjaman() {
        return idPeminjaman;
    }

    public String getAlasan() {
        return alasan;
    }

    
}
