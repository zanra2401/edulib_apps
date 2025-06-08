package com.edulib.edulib.dto;

import java.util.List;

public class SearchRequest {
    private String judul;
    private List<String> kategori;
    private List<String> penulis;

    public String getJudul() {
        return judul;
    }

    public List<String> getKategori() {
        return kategori;
    }

    public List<String> getPenulis() {
        return penulis;
    }
}
