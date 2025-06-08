package com.edulib.edulib.service;

import java.util.Map;
import java.util.Optional;

import org.springframework.http.ResponseEntity;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;

import com.edulib.edulib.dto.AdminRequest;
import com.edulib.edulib.dto.LoginRequest;
import com.edulib.edulib.dto.PustakawanRequest;
import com.edulib.edulib.dto.SiswaRequest;
import com.edulib.edulib.models.User;
import com.edulib.edulib.models.User.Peran;
import com.edulib.edulib.repository.UserRepository;
import com.edulib.edulib.security.JwtUtil;

import jakarta.servlet.http.Cookie;
import jakarta.servlet.http.HttpServletResponse;
import lombok.RequiredArgsConstructor;

@Service
@RequiredArgsConstructor
public class AuthService {

    private final UserRepository userRepository;
    private final JwtUtil jwtUtil;
    private final PasswordEncoder passwordEncoder;

    public String login(LoginRequest userRequest) {
        User user;

        System.out.println("test");

        if (userRequest.getPeran().equals("admin") || userRequest.getPeran().equals("pustakawan")) {
            user = userRepository.findByUsername(userRequest.getIdentity());
        } else {
            user = userRepository.findByNisn(userRequest.getIdentity());
        }
        
        if (user == null || !validatePassword(userRequest.getPassword(), user.getPassword())) {
            return "false";
        }

        userRequest.setId(Long.valueOf(user.getIdUser()));
        String token = jwtUtil.generateToken(userRequest);


        return token;
    }

    public ResponseEntity<?> registerAdmin(AdminRequest adminRequest) {
        User user = new User();
        adminRequest.setPassword(passwordEncoder.encode(adminRequest.getPassword()));
        user.setAdmin(adminRequest);
        user.setPeran(Peran.ADMIN);
        userRepository.save(user);
        return ResponseEntity.accepted().body(Map.of(
                "message", "berhasil daftar"
            )
        );
    }

    public ResponseEntity<?> registerSiswa(SiswaRequest siswaRequest) {
        User user = new User();
        siswaRequest.setPassword(passwordEncoder.encode(siswaRequest.getPassword()));
        user.setSiswa(siswaRequest);
        user.setPeran(Peran.SISWA);
        userRepository.save(user);
        return ResponseEntity.accepted().body(Map.of(
                "message", "berhasil daftar"
            )
        );
    }

    public ResponseEntity<?> registerPustakawan(PustakawanRequest pustakawanRequest) {
        User user = new User();
        pustakawanRequest.setPassword(passwordEncoder.encode(pustakawanRequest.getPassword()));
        user.setPustakawan(pustakawanRequest);
        user.setPeran(Peran.PUSTAKAWAN);
        userRepository.save(user);
        return ResponseEntity.accepted().body(Map.of(
                "message", "berhasil daftar"
            )
        );
    }

    private boolean validatePassword(String plainPassword, String encryptedPassword) {
        return passwordEncoder.matches(plainPassword, encryptedPassword);
    }

    public boolean isLogin(String token) {
        Long id = jwtUtil.extractId(token);
                        
        Optional<User> optionalUser = userRepository.findById(id);

        if (optionalUser.isEmpty()) {
            return false;
        }

        if(!jwtUtil.validateToken(token)) { return false; }
        System.out.println(jwtUtil.exctractIdentity(token));
        return isSiswa(token);
    }

    public boolean isLoginP(String token) {
        Long id = jwtUtil.extractId(token);

        Optional<User> optionalUser = userRepository.findById(id);

        if (optionalUser.isEmpty()) {
            return false;
        }
        if(!jwtUtil.validateToken(token)) { return false; }
        return isPustaka(token);
    }

    public boolean isLoginA(String token) {
        Long id = jwtUtil.extractId(token);

        Optional<User> optionalUser = userRepository.findById(id);

        if (optionalUser.isEmpty()) {
            return false;
        }
        if(!jwtUtil.validateToken(token)) { return false; }
        return isAdmin(token);
    }

    public void logOut(HttpServletResponse response) {
        Cookie jwtCookie = new Cookie("jwt", null);
        jwtCookie.setPath("/");
        jwtCookie.setMaxAge(0); 
        response.addCookie(jwtCookie);        
    }

    public boolean isSiswa(String token) {
        Optional<User> user = userRepository.findById(jwtUtil.extractId(token));

        if (user.isEmpty()) {
            return false;
        }

        return user.get().getPeran() == Peran.SISWA;
    }

    public boolean isPustaka(String token) {
        Optional<User> user = userRepository.findById(jwtUtil.extractId(token));

        if (user.isEmpty()) {
            return false;
        }

        return user.get().getPeran() == Peran.PUSTAKAWAN;
    }

    public boolean isAdmin(String token) {
        Optional<User> user = userRepository.findById(jwtUtil.extractId(token));

        if (user.isEmpty()) {
            return false;
        }

        return user.get().getPeran() == Peran.ADMIN;
    }
}
