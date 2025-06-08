package com.edulib.edulib.service;

import java.util.List;
import java.util.Map;
import java.util.Optional;

import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;

import com.edulib.edulib.dto.EditUserRequest;
import com.edulib.edulib.models.User;
import com.edulib.edulib.models.User.Peran;
import com.edulib.edulib.repository.NotifikasiRepository;
import com.edulib.edulib.repository.UserRepository;

import lombok.RequiredArgsConstructor;

@Service
@RequiredArgsConstructor
public class UserService {
    private final UserRepository userRepository;
    private final PasswordEncoder passwordEncoder;
    private final NotifikasiRepository notifikasiRepository;

    public ResponseEntity<?> deleteUser(Long userId) {
        Optional<User> optionalUser = userRepository.findById(userId);

        if (optionalUser.isEmpty()) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body(Map.of(
                "message", "user tidak ditemuakan"
            ));
        } 

        userRepository.delete(optionalUser.get());
        return ResponseEntity.status(HttpStatus.ACCEPTED).body(Map.of(
            "message", "berhasil hapus user"
        ));
    }

    public ResponseEntity<?> getUsers(Peran peran) {
        List<User> users = userRepository.findByPeran(peran);
        return ResponseEntity.status(HttpStatus.ACCEPTED).body(Map.of(
            "message", "berhasil",
            "user", users
        ));
    }

    public ResponseEntity<?> editUser(EditUserRequest newUser, Long id) {
        Optional<User> optionalUser = userRepository.findById(id);

        if (optionalUser.isEmpty()) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body(Map.of(
                "message", "user tidak ditemukan"
            ));
        }

        if (newUser.getPassword() != null && !newUser.getPassword().isBlank()) {
            newUser.setPassword(passwordEncoder.encode(newUser.getPassword()));
        }

        User user = optionalUser.get();
        user.update(newUser);
        
        userRepository.save(user);

        return ResponseEntity.status(HttpStatus.CREATED).body(Map.of(
            "message", "User Berhasil di update"
        ));
    }

    public ResponseEntity<?> getUser(Long id) {
        Optional<User> user = userRepository.findById(id);

        if (user.isEmpty()) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body("gagal");
        } 

        User userGet = user.get();
        return ResponseEntity.ok(Map.of(
            "message", "berhasil",
            "user", userGet
        ));
    }

    public ResponseEntity<?> editNama(Long id, String nama) {
        Optional<User> optionalUser = userRepository.findById(id);

        if (optionalUser.isEmpty()) {
            return ResponseEntity.status(HttpStatus.NOT_FOUND).body(Map.of(
                "message", "user tidak ditemukan"
            ));
        }

        User user = optionalUser.get();
        user.setNamaLengkap(nama);
        userRepository.save(user);
        return ResponseEntity.ok("ok");
    }

    public ResponseEntity<?> getNotifikasi(Long id) {
        List<Object[]> notif = notifikasiRepository.getNotifikasiByUser(id);
        return ResponseEntity.ok(Map.of(
            "message", "berhasil",
            "user", notif
        ));
    }
}