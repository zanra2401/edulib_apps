package com.edulib.edulib.models;

import java.util.List;

import com.edulib.edulib.dto.AdminRequest;
import com.edulib.edulib.dto.EditUserRequest;
import com.edulib.edulib.dto.PustakawanRequest;
import com.edulib.edulib.dto.SiswaRequest;
import com.fasterxml.jackson.annotation.JsonIgnore;

import jakarta.persistence.Column;
import jakarta.persistence.Entity;
import jakarta.persistence.EnumType;
import jakarta.persistence.Enumerated;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.persistence.OneToMany;
import jakarta.persistence.Table;
import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;

@Entity
@Table(name = "user")
@Builder
@NoArgsConstructor
@Getter
@Setter
@AllArgsConstructor
public class User {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Integer idUser;

    @Column(nullable=true, unique=true)
    private String username;
    @Column(nullable=false, name="password_user")
    private String password;
    @Column(nullable=true)
    private String email;
    @Column(nullable=true)
    private String namaLengkap;

    @Column(nullable=true, unique=true)
    private String nisn;

    @Enumerated(EnumType.STRING)
    private Peran peran;

    public enum Peran {
        SISWA, ADMIN, PUSTAKAWAN
    }

    @OneToMany(mappedBy = "user")
    @JsonIgnore
    private List<Peminjaman> peminjamanList;

    @OneToMany(mappedBy = "user")
    @JsonIgnore
    private List<Notifikasi> notifikasiList;

    @JsonIgnore
    public List<Notifikasi> getNotifikasi() {
        return this.notifikasiList;
    }

    public void setAdmin(AdminRequest adminRequest) {
        this.username = adminRequest.getIdentity();
        this.password = adminRequest.getPassword();
        if (adminRequest.getNama() != null) {
            this.namaLengkap = adminRequest.getNama();
        }
    }

    public void setPustakawan(PustakawanRequest pustakawanRequest) {
        this.username = pustakawanRequest.getIdentity();
        this.password = pustakawanRequest.getPassword();
        if (pustakawanRequest.getNama() != null) {
            this.namaLengkap = pustakawanRequest.getNama();
        }
    }

    public void setSiswa(SiswaRequest siswaRequest) {
        this.nisn = siswaRequest.getIdentity();
        this.password = siswaRequest.getPassword();
        this.email = siswaRequest.getEmail();
        if (siswaRequest.getNama() != null) {
            this.namaLengkap = siswaRequest.getNama();
        }
    }

    public void update(EditUserRequest newUser) {
        if (newUser.getUsername() != null && !newUser.getUsername().isBlank()) {
            this.username = newUser.getUsername();
        }
        if (newUser.getPassword() != null && !newUser.getPassword().isBlank()) {
            this.password = newUser.getPassword();
        }
        if (newUser.getEmail() != null && !newUser.getEmail().isBlank()) {
            this.email = newUser.getEmail();
        }
        if (newUser.getNamaLengkap() != null && !newUser.getNamaLengkap().isBlank()) {
            this.namaLengkap = newUser.getNamaLengkap();
        }
        if (newUser.getNisn() != null && !newUser.getNisn().isBlank()) {
            this.nisn = newUser.getNisn();
        }
        if (newUser.getPeran() != null) {
            this.peran = newUser.getPeran();
        }
        if (newUser.getNamaLengkap() != null) {
            this.namaLengkap = newUser.getNamaLengkap();
        }
    }

}
