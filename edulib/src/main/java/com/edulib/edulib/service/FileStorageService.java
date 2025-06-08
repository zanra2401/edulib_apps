package com.edulib.edulib.service;

import java.io.IOException;
import java.io.InputStream;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.nio.file.StandardCopyOption;
import java.util.UUID;

import org.springframework.beans.factory.annotation.Value;
import org.springframework.stereotype.Service;
import org.springframework.util.StringUtils;
import org.springframework.web.multipart.MultipartFile;

@Service
public class FileStorageService {

    private final Path fileStorageLocation;

    // Mengambil path dari application.properties
    public FileStorageService(@Value("${upload.path}") String uploadPath) {
        this.fileStorageLocation = Paths.get(uploadPath).toAbsolutePath().normalize();
        try {
            Files.createDirectories(this.fileStorageLocation);
        } catch (Exception ex) {
            throw new RuntimeException("Tidak dapat membuat direktori untuk menyimpan file upload.", ex);
        }
    }

    public String storeFile(MultipartFile file) {
        // Normalisasi nama file untuk keamanan
        String originalFileName = StringUtils.cleanPath(file.getOriginalFilename());

        try {
            if (originalFileName.contains("..")) {
                throw new RuntimeException("Maaf! Nama file mengandung karakter path yang tidak valid " + originalFileName);
            }

            // Buat nama file unik untuk menghindari duplikasi
            String fileExtension = "";
            try {
                fileExtension = originalFileName.substring(originalFileName.lastIndexOf("."));
            } catch(Exception e) {
                fileExtension = "";
            }
            String uniqueFileName = UUID.randomUUID().toString() + fileExtension;
            
            // Simpan file ke lokasi target
            Path targetLocation = this.fileStorageLocation.resolve(uniqueFileName);
            try (InputStream inputStream = file.getInputStream()) {
                Files.copy(inputStream, targetLocation, StandardCopyOption.REPLACE_EXISTING);
            }

            return uniqueFileName;
        } catch (IOException ex) {
            throw new RuntimeException("Tidak dapat menyimpan file " + originalFileName + ". Silakan coba lagi!", ex);
        }
    }
}