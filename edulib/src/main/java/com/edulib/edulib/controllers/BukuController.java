package com.edulib.edulib.controllers;

import java.math.BigDecimal;
import java.util.List;
import java.util.Map;

import org.springframework.data.domain.PageRequest;
import org.springframework.data.domain.Pageable;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.CookieValue;
import org.springframework.web.bind.annotation.CrossOrigin;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;
import org.springframework.web.multipart.MultipartFile;

import com.edulib.edulib.dto.BukuDTO;
import com.edulib.edulib.dto.BukuPopulerDTO;
import com.edulib.edulib.dto.BukuUpdateDTO;
import com.edulib.edulib.dto.PeminjamanPerBulanDTO;
import com.edulib.edulib.dto.SearchRequest;
import com.edulib.edulib.dto.TolakRequest;
import com.edulib.edulib.models.Buku;
import com.edulib.edulib.security.JwtUtil;
import com.edulib.edulib.service.AuthService;
import com.edulib.edulib.service.BukuService;
import org.springframework.http.MediaType;


import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;

@RestController
@RequestMapping("/edulib/buku")
@CrossOrigin(origins = {"http://localhost:8282","http://localhost:80","http://localhost:80"}, allowCredentials="true")
@RequiredArgsConstructor
public class BukuController {
    private final BukuService bukuService;
    private final AuthService authService;
    private final JwtUtil jwtUtil;

    @GetMapping("/statistik")
    public ResponseEntity<?> bukuStatistik() {
        List<PeminjamanPerBulanDTO> statistik = bukuService.getPeminjamanPerBulan();
        List<BukuPopulerDTO> bukuPopuler = bukuService.getTop10BukuPopuler();
        return ResponseEntity.status(HttpStatus.ACCEPTED).body(Map.of(
            "message", "berhasil",
            "laporan", statistik,
            "bukuPopuler", bukuPopuler
        ));
    }

    @GetMapping("/data-user")
    public ResponseEntity<?> getTotalBukuDipinjam(@CookieValue("token") String token) {
        if (authService.isLogin(token)) {
            Long id = jwtUtil.extractId(token);
            Long totalDipinjam = bukuService.totalBukuDipinjam(id);
            Long totalTerlambat = bukuService.totalBukuTerlambat(id);
            return ResponseEntity.status(HttpStatus.ACCEPTED).body(Map.of(
                "totalDipinjam", totalDipinjam,
                "totalTerlambat", totalTerlambat
            ));
        } else {
            return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body(Map.of(
                "message", "silakan login terlebih dahuluss"
            ));
        }
    }


    @GetMapping("/buku")
    public ResponseEntity<?> getAllBuku(@CookieValue("token") String token, @RequestParam(defaultValue="0") int page)
    {
        if (authService.isLogin(token)) {
            Pageable pageable = PageRequest.of(page, 15);
            return ResponseEntity.status(HttpStatus.ACCEPTED).body(Map.of(
                "message", "berhasil",
                "buku", bukuService.getAllBuku(pageable)
            ));
        } else {
           return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body(Map.of(
                "message", "silakan login terlebih dahuluss"
            )); 
        }
    }

    @GetMapping("/buku-dipinjam")
    public ResponseEntity<?> getBukuDipinjam(@CookieValue("token") String token) {
        if (authService.isLogin(token)) {
            Long id = jwtUtil.extractId(token);
            return ResponseEntity.status(HttpStatus.ACCEPTED).body(Map.of(
                "message", "berhasil",
                "buku", bukuService.getBukuDipinjamUser(id)
            ));

        } else {
           return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body(Map.of(
                "message", "silakan login terlebih dahuluss"
            )); 

        }
    }

    @PostMapping("/search")
    public ResponseEntity<?> searchBuku(@CookieValue("token") String token, @RequestBody @Valid SearchRequest searchRequest) {
        if (authService.isLogin(token)) {
            return ResponseEntity.status(HttpStatus.ACCEPTED).body(Map.of(
                "message", "berhasil",
                "buku", bukuService.searchBuku(searchRequest)
            ));
        } else {
           return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body(Map.of(
                "message", "silakan login terlebih dahuluss"
            )); 

        }
    }

    @GetMapping("/pinjam/{idBuku}")
    public ResponseEntity<?> pinjamBuku(@CookieValue("token") String token, @PathVariable Long idBuku) {
        Long iduser = jwtUtil.extractId(token);
        if (authService.isLogin(token)) {
            return bukuService.pinjamBuku(iduser, idBuku);
        } else {
           return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body(Map.of(
                "message", "silakan login terlebih dahuluss"
            )); 
        }
    }

    @GetMapping("/diajukan")
    public ResponseEntity<?> getBukuDiAjukan(@CookieValue("token") String token) {
        Long idUser = jwtUtil.extractId(token);
        if (authService.isLogin(token)) {
            return bukuService.getBukuDiAjukan(idUser);
        } else {
           return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body(Map.of(
                "message", "silakan login terlebih dahuluss"
            )); 
        }
    }

    @GetMapping("/riwayat")
    public ResponseEntity<?> getRiwayet(@CookieValue("token") String token) {
        Long idUser = jwtUtil.extractId(token);
        if (authService.isLogin(token)) {
            return bukuService.getRiwayat(idUser);
        } else {
           return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body(Map.of(
                "message", "silakan login terlebih dahuluss"
            )); 
        }
    }

    @GetMapping("/denda")
    public ResponseEntity<?> getDendaUser(@CookieValue("token") String token) {
        Long idUser = jwtUtil.extractId(token);
        if (authService.isLogin(token)) {
            return bukuService.getDendaUser(idUser);
        } else {
           return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body(Map.of(
                "message", "silakan login terlebih dahuluss"
            )); 
        }
    }

    @GetMapping("/buku-status")
    public ResponseEntity<?> getAllStatusBuku() {
        return bukuService.getAllStatusBuku();
    }

    @PostMapping(value = "/tambah", consumes = { MediaType.MULTIPART_FORM_DATA_VALUE })
    public ResponseEntity<?> tambahBuku(
            @ModelAttribute BukuDTO bukuDTO, // Gunakan @ModelAttribute untuk data form
            @RequestParam(value = "gambar", required = false) MultipartFile gambar // Gunakan @RequestParam untuk file
    ) {
        try {
            Buku bukuBaru = bukuService.saveBuku(bukuDTO, gambar);
            return ResponseEntity.ok(bukuBaru);
        } catch (Exception e) {
            return ResponseEntity.badRequest().body(e.getMessage());
        }
    }

    @GetMapping("/buku-status/{idBuku}")
    public ResponseEntity<?> getOneStatusBuku(@PathVariable Long idBuku) {
        return bukuService.getOneStatusBuku(idBuku);
    }

    @PostMapping(value = "/updateBuku", consumes = { MediaType.MULTIPART_FORM_DATA_VALUE })
    public ResponseEntity<?> updateBukuKu(
            @ModelAttribute BukuUpdateDTO bukuDTO,
            @RequestParam(value = "gambar", required = false) MultipartFile gambar
    ) {
        try {
            Buku bukuUpdate = bukuService.updateBuku(bukuDTO.getIdBuku(), bukuDTO, gambar);
            return ResponseEntity.ok(bukuUpdate);
        } catch (Exception e) { 
            return ResponseEntity.badRequest().body(e.getMessage());
        }
    }

    @GetMapping("/deletebuku/{idBuku}")
    public ResponseEntity<?> deleteBuku(@PathVariable Long idBuku) {
        return bukuService.deleteBuku(idBuku);
    }

    @GetMapping("/peminjaman")
    public ResponseEntity<?> peminjaman() {
        return bukuService.getPeminjaman();
    }

    @GetMapping("/setujui/{idBuku}")
    public ResponseEntity<?> setujuiBuku(@PathVariable Long idBuku) {
        return bukuService.setujui(idBuku);
    }

    @PostMapping("/tolak")
    public ResponseEntity<?> tolakPeminjaman(@RequestBody @Valid TolakRequest tolak) {
        return bukuService.tolakPeminjaman(tolak);
    }

    @GetMapping("/kembalikan/{idDetail}")
    public ResponseEntity<?> kembalikanBuku(@PathVariable @Valid Long idDetail) {
        return bukuService.kembalikan(idDetail);
    }

    @GetMapping("/bayar/{idDetail}/{denda}")
    public ResponseEntity<?> bayarDenda(@PathVariable Long idDetail, @PathVariable BigDecimal denda) {
        return bukuService.bayarDenda(idDetail, denda);
    }

    @GetMapping("/penolakan")
    public ResponseEntity<?> penolakan(@CookieValue("token") String token) {
        Long idUser = jwtUtil.extractId(token);
        if (authService.isLogin(token)) {
            return bukuService.getPenolakan(idUser);
        } else {
           return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body(Map.of(
                "message", "silakan login terlebih dahuluss"
            )); 
        }
    }

    @GetMapping("/getKP")
    public ResponseEntity<?> getKP(@CookieValue("token") String token) {
        if (authService.isLogin(token)) {
            return bukuService.getKP();
        } else {
           return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body(Map.of(
                "message", "silakan login terlebih dahuluss"
            )); 
        }
    }
}
