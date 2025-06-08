package com.edulib.edulib.controllers;

import java.util.Map;

import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.CookieValue;
import org.springframework.web.bind.annotation.CrossOrigin;
import org.springframework.web.bind.annotation.DeleteMapping;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import com.edulib.edulib.dto.EditUserRequest;
import com.edulib.edulib.models.User.Peran;
import com.edulib.edulib.security.JwtUtil;
import com.edulib.edulib.service.AuthService;
import com.edulib.edulib.service.UserService;

import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;

@RestController
@RequestMapping("/edulib/user")
@CrossOrigin(origins = {"http://localhost:8282","http://localhost:80","http://localhost:80"}, allowCredentials="true")
@RequiredArgsConstructor
public class UserController {
    private final UserService userService;
    private final AuthService authService;
    private final JwtUtil jwtUtil;

    @DeleteMapping("/{id}")   
    public ResponseEntity<?> deleteUser(@PathVariable Long id) {
        return userService.deleteUser(id);
    }

    @GetMapping("/admins")
    public ResponseEntity<?> getAdmins() {
        return userService.getUsers(Peran.ADMIN);
    }

    @GetMapping("/pustakawans")
    public ResponseEntity<?> getPustakawans() {
        return userService.getUsers(Peran.PUSTAKAWAN);
    }

    @GetMapping("/siswas")
    public ResponseEntity<?> getSiswas() {
        return userService.getUsers(Peran.SISWA);
    }

    @PostMapping("/edit/{id}")
    public ResponseEntity<?> updateUser(@RequestBody @Valid EditUserRequest newUser, @PathVariable Long id) {
        return userService.editUser(newUser, id);
    }

    @GetMapping("/user-login")
    public ResponseEntity<?> getUser(@CookieValue("token") String token) {
        if (authService.isLogin(token)) {
            Long id = jwtUtil.extractId(token);
            return userService.getUser(id);
        } else {
           return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body(Map.of(
                "message", "silakan login terlebih dahuluss"
            ));
        }
    }

    @GetMapping("/{id}")
    public ResponseEntity<?> getUser(@PathVariable Long id) {
        return userService.getUser(id);
    }

    @GetMapping("/notifikasi")
    public ResponseEntity<?> getNotif(@CookieValue("token") String token) {
        if (authService.isLogin(token)) {
            Long id = jwtUtil.extractId(token);
            return userService.getNotifikasi(id);
        } else {
           return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body(Map.of(
                "message", "silakan login terlebih dahulus"
            ));
        }
    }

    @PostMapping("/editnama")
    public ResponseEntity<?> editNama(@CookieValue("token") String token, @RequestBody String nama) {
        if (authService.isLogin(token)) {
            Long id = jwtUtil.extractId(token);
            return userService.editNama(id, nama);
        } else {
           return ResponseEntity.status(HttpStatus.UNAUTHORIZED).body(Map.of(
                "message", "silakan login terlebih dahuluss"
            ));
        }
    }
}