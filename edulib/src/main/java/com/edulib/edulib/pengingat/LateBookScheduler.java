    package com.edulib.edulib.pengingat;

import java.time.LocalDate;
import java.time.LocalDateTime;
import java.time.temporal.ChronoUnit;
import java.util.List;

import org.springframework.mail.SimpleMailMessage;
import org.springframework.mail.javamail.JavaMailSender;
import org.springframework.scheduling.annotation.Scheduled;
import org.springframework.stereotype.Service;

import com.edulib.edulib.models.Buku;
import com.edulib.edulib.models.DetailPeminjaman;
import com.edulib.edulib.models.Notifikasi;
import com.edulib.edulib.models.Peminjaman;
import com.edulib.edulib.models.User;
import com.edulib.edulib.repository.DetailPeminjamanRepository;
import com.edulib.edulib.repository.NotifikasiRepository;
import com.edulib.edulib.repository.PeminjamanRepository;

import lombok.RequiredArgsConstructor;

@Service
@RequiredArgsConstructor
public class LateBookScheduler {

    
    private final JavaMailSender mailSender;
    private final NotifikasiRepository notifikasiRepository;
    private final PeminjamanRepository peminjamanRepository;
    private final DetailPeminjamanRepository detailPeminjamanRepository;


    @Scheduled(cron = "0 0 8 * * ?") // Setiap jam 08:00 pagi
    // @Scheduled(cron = "*/60 * * * * ?")
    public void checkLateReturns() {
        List<DetailPeminjaman> details = detailPeminjamanRepository.findAll();
        for (DetailPeminjaman d : details) {
            Long diff = ChronoUnit.DAYS.between(d.getPeminjaman().getTanggalEstimasiKembali(), LocalDate.now());    
            if (diff > 0 && d.getPeminjaman().getStatus() == Peminjaman.StatusPeminjaman.dipinjam) {
                User user = d.getPeminjaman().getUser();
                StringBuilder pesan = new StringBuilder();
                Buku buku =  d.getBuku();
                pesan.append("Buku berjudul ");
                pesan.append(buku.getJudul());
                pesan.append(", telah telambat dikembalika selama ");
                pesan.append(diff);
                pesan.append(" hari.");
                Notifikasi notifikasi = new Notifikasi();
                notifikasi.setUser(user);
                notifikasi.setDetail(d);
                notifikasi.setJenisNotifikasi(Notifikasi.JenisNotifikasi.terlambat);
                notifikasi.setTanggalKirim(LocalDateTime.now());
                notifikasi.setIsiPesan(pesan.toString());
                notifikasiRepository.save(notifikasi);
                sendLateReturnEmail(user.getEmail(), pesan.toString());
            } else if (diff == -1  && d.getPeminjaman().getStatus() == Peminjaman.StatusPeminjaman.dipinjam) {
                User user = d.getPeminjaman().getUser();
                StringBuilder pesan = new StringBuilder();
                Buku buku =  d.getBuku();
                pesan.append("Buku berjudul ");
                pesan.append(buku.getJudul());
                pesan.append(" akan jatuh tenggat selama 1 hari");
                Notifikasi notifikasi = new Notifikasi();
                notifikasi.setUser(user);
                notifikasi.setDetail(d);
                notifikasi.setJenisNotifikasi(Notifikasi.JenisNotifikasi.pengingat);
                notifikasi.setTanggalKirim(LocalDateTime.now());
                notifikasi.setIsiPesan(pesan.toString());
                notifikasiRepository.save(notifikasi);
                sendLateReturnEmail(user.getEmail(), pesan.toString());
            }
        }
    }


    public void sendLateReturnEmail(String to, String pesan) {
        SimpleMailMessage message = new SimpleMailMessage();
        message.setTo(to);
        message.setSubject("Peringatan");
        message.setText(pesan);
        mailSender.send(message);
    }
}
