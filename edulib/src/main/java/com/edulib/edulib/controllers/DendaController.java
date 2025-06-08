package com.edulib.edulib.controllers;


import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.CrossOrigin;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import com.edulib.edulib.service.DendaService;

import lombok.RequiredArgsConstructor;

@RestController
@RequestMapping("/edulib/denda")
@CrossOrigin(origins = {"http://localhost:8282","http://localhost:80","http://localhost:80"}, allowCredentials="true")
@RequiredArgsConstructor
public class DendaController {

    private final DendaService dendaService;

    // Endpoint untuk mendapatkan semua data denda
    @GetMapping("/")
    public ResponseEntity<?> getAllDenda() {
        return dendaService.getDenda();
    }
}
