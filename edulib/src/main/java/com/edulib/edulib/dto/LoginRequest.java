package com.edulib.edulib.dto;

import jakarta.validation.constraints.NotBlank;

public class LoginRequest {
    @NotBlank(message="Username atau nisn tidak boleh kosong")
    private final String identitas;

    @NotBlank(message="Password tidak boleh kosong")
    private final String password;

    @NotBlank(message="bad request")
    private final String peran;

    private Long id;

    public LoginRequest(@NotBlank String identitas, @NotBlank String password, @NotBlank String peran) {
        this.identitas = identitas;
        this.password = password;
        this.peran = peran;
    }

    public String getPassword() {
        return password;
    }

    public String getIdentity() {
        return this.identitas;
    }

    public Long getId() {
        return id;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public String getPeran() {
        return peran;
    }
}
