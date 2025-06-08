package com.edulib.edulib.controllers;

import java.util.List;
import java.util.Map;
import java.util.stream.Collectors;

import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.CookieValue;
import org.springframework.web.bind.annotation.CrossOrigin;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import com.edulib.edulib.dto.AdminRequest;
import com.edulib.edulib.dto.LoginRequest;
import com.edulib.edulib.dto.PustakawanRequest;
import com.edulib.edulib.dto.SiswaRequest;
import com.edulib.edulib.service.AuthService;

import jakarta.servlet.http.Cookie;
import jakarta.servlet.http.HttpServletResponse;
import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;

@RestController
@RequestMapping("/edulib/auth")
@CrossOrigin(origins = {"http://localhost:8282","http://localhost:80","http://localhost:80"}, allowCredentials="true")
@RequiredArgsConstructor
public class AuthController {
    private final AuthService authService;

    @PostMapping("/login") 
    public ResponseEntity<?> loginAdmin(HttpServletResponse response, @Valid @RequestBody LoginRequest loginRequest, BindingResult result) {
        if (result.hasErrors()) {
            List<String> errors = result.getFieldErrors().stream()
            .map(error -> error.getField() + ": " + error.getDefaultMessage())
            .collect(Collectors.toList());
            return ResponseEntity.badRequest().body(errors);
        }

        String token = authService.login(loginRequest);

        if (token.equals("false")) {
            return ResponseEntity.status(HttpStatus.BAD_REQUEST).body(Map.of(
                    "message", "Password atau Username salah"
            ));
        }

        Cookie cookie = new Cookie("token", token);
        cookie.setHttpOnly(true); 
        cookie.setSecure(false); 
        cookie.setPath("/");
        cookie.setMaxAge(24 * 60 * 60); // 1 hari
        response.addCookie(cookie);

        return ResponseEntity.status(HttpStatus.ACCEPTED).body(Map.of(
            "message", "Berhasil Login"
        ));
    }

    @PostMapping("/admin/register")
    public ResponseEntity<?> registerAdmin(@Valid @RequestBody AdminRequest adminRequest, BindingResult result) {
        if (result.hasErrors()) {
            List<String> errors = result.getFieldErrors().stream()
            .map(error -> error.getField() + ": " + error.getDefaultMessage())
            .collect(Collectors.toList());
            return ResponseEntity.badRequest().body(errors);
        }

        return authService.registerAdmin(adminRequest);
    }

    @PostMapping("/siswa/register")
    public ResponseEntity<?> registerSiswa(@Valid @RequestBody SiswaRequest siswaRequest, BindingResult result) {
        if (result.hasErrors()) {
            List<String> errors = result.getFieldErrors().stream()
            .map(error -> error.getField() + ": " + error.getDefaultMessage())
            .collect(Collectors.toList());
            return ResponseEntity.badRequest().body(errors);
        }

        return authService.registerSiswa(siswaRequest);
    }

    @PostMapping("/pustakawan/register")
    public ResponseEntity<?> registerPustakawan(@Valid @RequestBody PustakawanRequest pustakawanRequest, BindingResult result) {
        if (result.hasErrors()) {
            List<String> errors = result.getFieldErrors().stream()
            .map(error -> error.getField() + ": " + error.getDefaultMessage())
            .collect(Collectors.toList());
            return ResponseEntity.badRequest().body(errors);
        }

        return authService.registerPustakawan(pustakawanRequest);
    }

    @GetMapping("/logedin-pustaka")
    public ResponseEntity<?> isLoginP(@CookieValue("token") String token) {
        System.out.println(token);
        if (authService.isLoginP(token)) {
            return ResponseEntity.ok("ok");
        } else {
            return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body("silakan Login Terlebih dahulu");
        }
    }

    @GetMapping("/logedin-admin")
    public ResponseEntity<?> isLoginA(@CookieValue("token") String token) {
        System.out.println(token);
        if (authService.isLoginA(token)) {
            return ResponseEntity.ok("ok");
        } else {
            return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body("silakan Login Terlebih dahulu");
        }
    }

    @GetMapping("/logedin")
    public ResponseEntity<?> isLogin(@CookieValue("token") String token) {
        System.out.println(token);
        if (authService.isLogin(token)) {
            return ResponseEntity.ok("ok");
        } else {
            return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body("silakan Login Terlebih dahulu");
        }
    }

    @GetMapping("/logout")
    public ResponseEntity<String> logout(HttpServletResponse response) {
        Cookie jwtCookie = new Cookie("token", "a");
        jwtCookie.setHttpOnly(true);
        jwtCookie.setSecure(false);
        jwtCookie.setPath("/");
        jwtCookie.setMaxAge(0); 
        response.addCookie(jwtCookie);  
        return ResponseEntity
                .ok()
                .header("Set-Cookie", "jwt=; HttpOnly; Path=/; Max-Age=0")
                .body("Logged out.");
    }
}
