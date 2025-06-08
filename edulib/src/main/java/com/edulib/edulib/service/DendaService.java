package com.edulib.edulib.service;

import java.math.BigDecimal;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;

import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.stereotype.Service;

import com.edulib.edulib.dto.LaporanDendaDTO;
import com.edulib.edulib.repository.DendaRepository;

import lombok.RequiredArgsConstructor;

@Service
@RequiredArgsConstructor
public class DendaService {
    private final DendaRepository dendaRepository;

    public ResponseEntity<?> getDenda() {
        return ResponseEntity.status(HttpStatus.ACCEPTED).body(Map.of(
            "message", "berhasil",
            "denda", dendaRepository.findAllDendaInfo(),
            "laporan", this.getLaporanDendaBulanan()
        ));
    }

    private List<LaporanDendaDTO> getLaporanDendaBulanan() {
        List<Object[]> rawData = dendaRepository.findLaporanDendaPerBulanRaw();
        List<LaporanDendaDTO> result;
        result = new ArrayList<>();

        for (Object[] row : rawData) {
            String bulan = (String) row[0];
            BigDecimal total = (BigDecimal) row[1];
            result.add(new LaporanDendaDTO(bulan, total));
        }

        return result;
    }
}
