package com.edulib.edulib.service;

import java.math.BigDecimal;
import java.time.LocalDate;
import java.time.temporal.ChronoUnit;
import java.util.List;
import java.util.Map;
import java.util.Optional;
import java.util.stream.Collectors;

import org.springframework.data.domain.Page;
import org.springframework.data.domain.PageRequest;
import org.springframework.data.domain.Pageable;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.stereotype.Service;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.multipart.MultipartFile;

import com.edulib.edulib.dto.BukuDTO;
import com.edulib.edulib.dto.BukuPopulerDTO;
import com.edulib.edulib.dto.BukuResponse;
import com.edulib.edulib.dto.BukuUpdateDTO;
import com.edulib.edulib.dto.PeminjamanPerBulanDTO;
import com.edulib.edulib.dto.SearchRequest;
import com.edulib.edulib.dto.TolakRequest;
import com.edulib.edulib.models.Buku;
import com.edulib.edulib.models.Denda;
import com.edulib.edulib.models.DetailPeminjaman;
import com.edulib.edulib.models.Peminjaman;
import com.edulib.edulib.models.Pengembalian;
import com.edulib.edulib.models.Pengembalian.StatusDenda;
import com.edulib.edulib.models.User;
import com.edulib.edulib.repository.BukuRepository;
import com.edulib.edulib.repository.DendaRepository;
import com.edulib.edulib.repository.DetailPeminjamanRepository;
import com.edulib.edulib.repository.PeminjamanRepository;
import com.edulib.edulib.repository.PengembalianRepository;
import com.edulib.edulib.repository.UserRepository;

import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;

@Service
@RequiredArgsConstructor
public class BukuService {
    private final BukuRepository bukuRepository;
    private final UserRepository userRepository;
    private final DetailPeminjamanRepository detailPeminjamanRepository;
    private final PeminjamanRepository peminjamanRepository;
    private final DendaRepository dendaRepository;
    private final PengembalianRepository pengembalianRepository;
    private final FileStorageService fileStorageService;

    public List<PeminjamanPerBulanDTO> getPeminjamanPerBulan() {
        return bukuRepository.findPeminjamanPerBulan().stream()
            .map(obj -> new PeminjamanPerBulanDTO((String) obj[0], ((Number) obj[1]).longValue()))
            .collect(Collectors.toList());
    }

    public List<BukuPopulerDTO> getTop10BukuPopuler() {
        return bukuRepository.findTop10BukuPalingPopuler(PageRequest.of(0, 10));
    }

    public Buku saveBuku(BukuDTO bukuDTO, MultipartFile gambarFile) {
        Buku buku = new Buku(bukuDTO);
        
        // Proses dan simpan gambar jika ada
        if (gambarFile != null && !gambarFile.isEmpty()) {
            String fileName = fileStorageService.storeFile(gambarFile);
            buku.setPathGambar(fileName);
        }

        return bukuRepository.save(buku);
    }

    public ResponseEntity<?> deleteBuku(Long id) {

        Optional<Buku> optionalBuku = bukuRepository.findById(id);
        
        if (optionalBuku.isEmpty()) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body(Map.of(
                "message", "buku tidak di temukan"
            )); 
        }

        Buku buku = optionalBuku.get();

        bukuRepository.delete(buku);
        return ResponseEntity.status(HttpStatus.CREATED).body(Map.of(
            "message", "berhasil menghapus buku"
        ));
    }

    public Buku updateBuku(Long idBuku, BukuUpdateDTO dto, MultipartFile gambarFile) {
        Buku buku = bukuRepository.findById(idBuku)
                .orElseThrow(() -> new RuntimeException("Buku tidak ditemukan"));

        // ... update properti lain dari DTO
        buku.setJudul(dto.getJudul());
        buku.setJudul(dto.getJudul());
        buku.setPenulis(dto.getPenulis());
        buku.setPenerbit(dto.getPenerbit());
        buku.setTahunTerbit(dto.getTahunTerbit());
        buku.setKategori(dto.getKategori());
        buku.setJumlahStok(dto.getJumlahStok());
        buku.setLokasiRak(dto.getLokasiRak());

        // Proses dan simpan gambar baru jika ada
        if (gambarFile != null && !gambarFile.isEmpty()) {
            String fileName = fileStorageService.storeFile(gambarFile);
            buku.setPathGambar(fileName);
        }
        
        return bukuRepository.save(buku);
    }

    public ResponseEntity<?> getAllBuku() {
        List<Buku> bukus = bukuRepository.findAll();

        return ResponseEntity.ok(Map.of(
            "message", "berhasil",
            "buku", bukus
        ));
    }

    
    public Long totalBukuDipinjam(Long idUser) {
        return bukuRepository.countBukuDipinjamByUser(idUser);
    }

    public Long  totalBukuTerlambat(Long idUser) {
        return bukuRepository.coutBukuTerlambatByUser(idUser);
    }

    public Page<BukuResponse> getAllBuku(Pageable page) {
        return bukuRepository.findAllBuku(page);
    }

    public ResponseEntity<?> getBukuDipinjam(Long idUser) {
        return ResponseEntity.status(HttpStatus.ACCEPTED).body(Map.of(
            "message", "berhasil",
            "buku", bukuRepository.countBukuDipinjamByUser(idUser)
        ));
    }

    public ResponseEntity<?> getBukuDipinjamUser(Long idUser) {
        return ResponseEntity.status(HttpStatus.ACCEPTED).body(Map.of(
            "message", "berhasil",
            "buku", bukuRepository.findBukuDipinjamUser(idUser)
        ));
    }

    public ResponseEntity<?> searchBuku(SearchRequest searchRequest) {
        System.out.println(searchRequest.getJudul());
        System.out.println(searchRequest.getKategori());
        System.out.println(searchRequest.getPenulis());
        return ResponseEntity.status(HttpStatus.ACCEPTED).body(Map.of(
            "message", "berhasil",
            "buku", bukuRepository.searchBuku(searchRequest.getJudul(), searchRequest.getKategori(), searchRequest.getPenulis())
        ));
    }

    public ResponseEntity<?> pinjamBuku(Long idUser, Long idBuku) {
        LocalDate now = LocalDate.now();
        LocalDate kembali = now.plusDays(7);

        Optional<User> user = userRepository.findById(idUser);
        Optional<Buku> buku = bukuRepository.findById(idBuku);

        if (user.isEmpty() || buku.isEmpty()) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body("404");
        }


        Peminjaman peminjaman = new Peminjaman(user.get(), now, kembali);
        peminjamanRepository.save(peminjaman);
        DetailPeminjaman detailPeminjaman = new DetailPeminjaman(buku.get(), peminjaman);
        detailPeminjamanRepository.save(detailPeminjaman);

        return ResponseEntity.ok("berhasil");
    }

    public ResponseEntity<?> getBukuDiAjukan(Long idUser) {
        Optional<User> user = userRepository.findById(idUser);
        if (user.isEmpty()) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body("404");
        }

        return ResponseEntity.status(HttpStatus.ACCEPTED).body(Map.of(
            "messaeg", "berhasil",
            "buku", bukuRepository.getBukuDiAjukan(idUser)
        ));
    }

    public ResponseEntity<?> getRiwayat(Long idUser) {
        Optional<User> user = userRepository.findById(idUser);
        if (user.isEmpty()) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body("404");
        }

        return ResponseEntity.status(HttpStatus.ACCEPTED).body(Map.of(
            "messaeg", "berhasil",
            "buku", bukuRepository.getRiwayet(idUser)
        ));
    }

    public ResponseEntity<?> getDendaUser(Long idUser) {
        Optional<User> user = userRepository.findById(idUser);
        if (user.isEmpty()) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body("404");
        }

        return ResponseEntity.status(HttpStatus.ACCEPTED).body(Map.of(
            "messaeg", "berhasil",
            "buku", bukuRepository.getDenda(idUser)
        ));
    }

    public ResponseEntity<?> getAllStatusBuku() {
        return ResponseEntity.status(HttpStatus.ACCEPTED).body(Map.of(
            "message", "berhasil",
            "buku", bukuRepository.getStatuskBuku()
        ));
    }

    public ResponseEntity<?> getOneStatusBuku(Long idBuku) {
        return ResponseEntity.status(HttpStatus.ACCEPTED).body(Map.of(
            "message", "berhasil",
            "buku", bukuRepository.getOneStatusBuku(idBuku)
        ));
    }

    public ResponseEntity<?> getPeminjaman() {
        return ResponseEntity.status(HttpStatus.ACCEPTED).body(Map.of(
            "message", "berhasil",
            "peminjaman", bukuRepository.getPeminjaman()
        ));
    }

    public ResponseEntity<?> setujui(Long idPeminjaman) {
        Optional<Peminjaman> peminjamanOpt = peminjamanRepository.findById(idPeminjaman);
        if(peminjamanOpt.isEmpty()) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body("404");
        }
        Peminjaman peminjaman = peminjamanOpt.get();
        peminjaman.setStatus(Peminjaman.StatusPeminjaman.dipinjam);
        peminjamanRepository.save(peminjaman);
        return ResponseEntity.ok("berhasil");
    }

    public ResponseEntity<?> tolakPeminjaman(@RequestBody @Valid TolakRequest tolak) {
        Optional<Peminjaman> peminjamanOpt = peminjamanRepository.findById(tolak.getIdPeminjaman());

        if (peminjamanOpt.isEmpty()) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body("404");
        }

        Peminjaman peminjaman = peminjamanOpt.get();
        peminjaman.setAlasanPenolakan(tolak.getAlasan());
        peminjaman.setStatus(Peminjaman.StatusPeminjaman.ditolak);
        peminjamanRepository.save(peminjaman);
        return ResponseEntity.ok("berhasil");
    }

    public ResponseEntity<?> kembalikan(Long idPeminjaman) {
        Optional<DetailPeminjaman> detailOpt = detailPeminjamanRepository.findById(idPeminjaman);

        if (detailOpt.isEmpty()) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body("404");
        }
        DetailPeminjaman detail = detailOpt.get();
        Peminjaman peminjaman = detail.getPeminjaman();
        peminjaman.setStatus(Peminjaman.StatusPeminjaman.kembali);
        Pengembalian pengembalian = new Pengembalian();
        if (ChronoUnit.DAYS.between(peminjaman.getTanggalEstimasiKembali(), LocalDate.now()) > 0) {
            pengembalian.setStatusDenda(Pengembalian.StatusDenda.belum_dibayar);
        } else {
            pengembalian.setStatusDenda(Pengembalian.StatusDenda.dibayar);
        }
        pengembalian.setTanggalKembali(LocalDate.now());
        pengembalian.setDetail(detail);
        pengembalianRepository.save(pengembalian);
        peminjamanRepository.save(peminjaman);
        return ResponseEntity.ok("ok");
    }

    public ResponseEntity<?> getPenolakan(Long id) {
        return ResponseEntity.status(HttpStatus.ACCEPTED).body(Map.of(
            "message", "berhasil",
            "buku", bukuRepository.getPenolakan(id)
        ));
    }

    public ResponseEntity<?> bayarDenda(Long idDetail, BigDecimal denda) {
        Optional<DetailPeminjaman> detailOpt = detailPeminjamanRepository.findById(idDetail);

        if (detailOpt.isEmpty()) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body("404");
        }

        DetailPeminjaman detail = detailOpt.get();
        Pengembalian pengembalian = pengembalianRepository.findByDetail(detail);
        Peminjaman peminjaman = detail.getPeminjaman();
        Denda dendaNew = new Denda();
        Long selisih = ChronoUnit.DAYS.between(peminjaman.getTanggalEstimasiKembali(), LocalDate.now());
        if (selisih > 0) {
            dendaNew.setJumlahHariTerlambat(selisih);
        } else {
            dendaNew.setJumlahHariTerlambat(Long.valueOf(0));
        }
        dendaNew.setTotalNominal(denda);
        dendaNew.setPengembalian(pengembalian);
        dendaRepository.save(dendaNew);
        pengembalian.setStatusDenda(StatusDenda.dibayar);
        pengembalianRepository.save(pengembalian);
        return ResponseEntity.ok("ok");
    }

    public ResponseEntity<?> getKP() {
        return ResponseEntity.ok(Map.of(
            "kategori", bukuRepository.getKategori(),
            "penulis", bukuRepository.getPenulis()
        ));
    }
}
