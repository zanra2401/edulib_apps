package com.edulib.edulib.dto;

import jakarta.validation.constraints.NotBlank;

public class AturanDTO {

    private Long idAturan;

    @NotBlank
    private String jenisAturan;

    @NotBlank
    private String isiAturan;

    public String getJenisAturan() {
        return this.jenisAturan;
    }

    public String getIsiAturan() {
        return this.isiAturan;
    }

    public Long getId() {
        return this.idAturan;
    }

    public void setJenisAturan(String jenisAturan) {
        this.jenisAturan = jenisAturan;
    }

    public void setIsiAturan(String isiAturan) {
        this.isiAturan = isiAturan;
    }
}
