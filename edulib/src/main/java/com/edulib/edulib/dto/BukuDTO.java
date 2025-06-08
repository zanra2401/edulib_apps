package com.edulib.edulib.dto;



import jakarta.validation.constraints.Min;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import lombok.Getter;
import lombok.Setter;


@Getter
@Setter
public class BukuDTO {

    private Long idBuku;

    @NotBlank(message = "Judul tidak boleh kosong")
    private String judul;

    @NotBlank(message = "Penulis tidak boleh kosong")
    private String penulis;

    @NotBlank(message = "Penerbit tidak boleh kosong")
    private String penerbit;

    @NotNull(message = "Tahun terbit tidak boleh kosong")
    private Long tahunTerbit;

    @NotBlank(message = "Kategori tidak boleh kosong")
    private String kategori;

    @NotNull(message = "Jumlah stok tidak boleh kosong")
    @Min(value = 0, message = "Jumlah stok tidak boleh negatif")
    private Integer jumlahStok;

    @NotBlank(message = "Lokasi rak tidak boleh kosong")
    private String lokasiRak;
}

