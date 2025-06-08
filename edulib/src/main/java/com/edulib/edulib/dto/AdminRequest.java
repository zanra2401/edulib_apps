package com.edulib.edulib.dto;

import jakarta.validation.constraints.NotBlank;

public class AdminRequest  {
    @NotBlank(message="Username tidak boleh kosong")
    private final String username;

    private String password;

    private Long id;

    private String nama;

    public String getNama() {
        return nama;
    }

    private String namaLengkap;

    public AdminRequest(@NotBlank String username, @NotBlank String password, String nama) {
        this.username = username;
        this.password = password;
        this.nama = nama;
    }

    public Long getId() {
        return this.id;
    }

    public String getIdentity() {
        return this.username;
    }

    public String getNamaLengkap() {
        return namaLengkap;
    }

    public String getPassword() {
        return password;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public void setPassword(String encryptedPasswod) {
        this.password = encryptedPasswod;
    }
}
