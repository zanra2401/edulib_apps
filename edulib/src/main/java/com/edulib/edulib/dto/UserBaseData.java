package com.edulib.edulib.dto;

public class UserBaseData {
    private Long id;
    private String nama;
    private String identity;

    public UserBaseData(Long id, String identity, String nama) {
        this.id = id;
        this.identity = identity;
        this.nama = nama;
    }   

    public UserBaseData(Long id, String identity) {
        this.id = id;
        this.identity = identity;
        this.nama = "Tidak Diketahui";
    }
}
