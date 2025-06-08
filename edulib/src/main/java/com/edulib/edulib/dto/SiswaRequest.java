package com.edulib.edulib.dto;

import jakarta.validation.constraints.Email;
import jakarta.validation.constraints.NotBlank;


public class SiswaRequest {
    @NotBlank(message="nisn tidak boleh kosong")
    private final String nisn;
    
    private String password;

    @NotBlank(message="email tidak boleh kosong")
    @Email(message="email tidak valid")
    private final String email;

    private String nama;

    private Long id;

    public  SiswaRequest(@NotBlank String nisn, String password, @NotBlank @Email String email, String nama) {
        this.nisn = nisn;
        this.password = password;
        this.email = email;
        this.nama = nama;
    }

    public Long getId() {
        return this.id;
    }

    public String getIdentity() {
        return nisn;
    }

    public void setPassword(String encryptedPassword) {
        this.password = encryptedPassword;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public String getPassword() {
        return password;
    }

    public String getEmail() {
        return this.email;
    }

    public String getNama() {
        return nama;
    }
}
