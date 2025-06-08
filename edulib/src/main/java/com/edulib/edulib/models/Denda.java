package com.edulib.edulib.models;

import java.math.BigDecimal;

import jakarta.persistence.Entity;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.persistence.JoinColumn;
import jakarta.persistence.OneToOne;
import jakarta.persistence.Table;
import lombok.Getter;
import lombok.Setter;

@Entity
@Table(name = "denda")
@Getter
@Setter
public class Denda {
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Id
    private Integer idDenda;

    @OneToOne
    @JoinColumn(name = "id_pengembalian")
    private Pengembalian pengembalian;

    public void setPengembalian(Pengembalian pengembalian) {
        this.pengembalian = pengembalian;
    }

    private Long jumlahHariTerlambat;

    private BigDecimal totalNominal;

    public void setTotalNominal(BigDecimal totalNominal) {
        this.totalNominal = totalNominal;
    }

    public Denda() {
        
    }
}

