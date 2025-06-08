package com.edulib.edulib.models;

import java.time.LocalDateTime;

import jakarta.persistence.Entity;
import jakarta.persistence.EnumType;
import jakarta.persistence.Enumerated;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.persistence.JoinColumn;
import jakarta.persistence.ManyToOne;
import jakarta.persistence.Table;
import lombok.Getter;
import lombok.Setter;

@Entity
@Table(name = "notifikasi")
@Getter
@Setter
public class Notifikasi {
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    @Id
    private Integer idNotifikasi;

    @ManyToOne
    @JoinColumn(name = "id_user")
    private User user;

    @ManyToOne
    @JoinColumn(name = "id_detail")
    private DetailPeminjaman detail;

    private String isiPesan;
    private LocalDateTime tanggalKirim;

    @Enumerated(EnumType.STRING)
    private JenisNotifikasi jenisNotifikasi;

    public Notifikasi() {

    }

    public enum JenisNotifikasi {
        pengingat, terlambat
    }
}
