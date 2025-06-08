package com.edulib.edulib.dto;

import java.math.BigDecimal;

import com.edulib.edulib.models.Pengembalian;
import com.edulib.edulib.models.Pengembalian.StatusDenda;

public class DendaInfoDTO {
    private Integer idDenda;
    private Integer idUser;
    private Integer idBuku;
    private Long jumlahHariTerlambat;
    private BigDecimal totalNominal;
    private StatusDenda statusDenda;

    // Konstruktor sesuai query
    public DendaInfoDTO(Integer idDenda, Integer idUser, Integer idBuku,
                        Long jumlahHariTerlambat, BigDecimal totalNominal, Pengembalian.StatusDenda statusDenda) {
        this.idDenda = idDenda;
        this.idUser = idUser;
        this.idBuku = idBuku;
        this.jumlahHariTerlambat = jumlahHariTerlambat;
        this.totalNominal = totalNominal;
        this.statusDenda = statusDenda;
    }

    // Getter dan setter
    public Integer getIdDenda() {
        return idDenda;
    }

    public void setIdDenda(Integer idDenda) {
        this.idDenda = idDenda;
    }

    public Integer getIdUser() {
        return idUser;
    }

    public void setIdUser(Integer idUser) {
        this.idUser = idUser;
    }

    public Integer getIdBuku() {
        return idBuku;
    }

    public void setIdBuku(Integer idBuku) {
        this.idBuku = idBuku;
    }

    public Long getJumlahHariTerlambat() {
        return jumlahHariTerlambat;
    }

    public void setJumlahHariTerlambat(Long jumlahHariTerlambat) {
        this.jumlahHariTerlambat = jumlahHariTerlambat;
    }

    public BigDecimal getTotalNominal() {
        return totalNominal;
    }

    public void setTotalNominal(BigDecimal totalNominal) {
        this.totalNominal = totalNominal;
    }

    public StatusDenda getStatusDenda() {
        return statusDenda;
    }

    public void setStatusDenda(StatusDenda statusDenda) {
        this.statusDenda = statusDenda;
    }
}
