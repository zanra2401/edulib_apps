package com.edulib.edulib.dto;


import com.edulib.edulib.models.User.Peran;

public class EditUserRequest {
    private String username;
    private String email;
    private String namaLengkap;
    private String password;
    private String nisn;
    private Peran peran;

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getNamaLengkap() {
        return namaLengkap;
    }

    public void setNamaLengkap(String namaLengkap) {
        this.namaLengkap = namaLengkap;
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }

    public String getNisn() {
        return nisn;
    }

    public void setNisn(String nisn) {
        this.nisn = nisn;
    }

    public Peran getPeran() {
        return peran;
    }

    public void setPeran(Peran peran) {
        this.peran = peran;
    }
}
