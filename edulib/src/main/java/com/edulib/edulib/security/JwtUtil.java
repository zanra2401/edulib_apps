package com.edulib.edulib.security;

import java.nio.charset.StandardCharsets;
import java.util.Date;

import javax.crypto.SecretKey;

import org.springframework.beans.factory.annotation.Value;
import org.springframework.security.authorization.AuthorizationDeniedException;
import org.springframework.stereotype.Component;

import com.edulib.edulib.dto.LoginRequest;

import io.jsonwebtoken.Claims;
import io.jsonwebtoken.Jws;
import io.jsonwebtoken.Jwts;
import io.jsonwebtoken.security.Keys;

@Component
public class JwtUtil {
    
    @Value("${jwt.secret}")
    private String secret;

    public String generateToken(LoginRequest user) {

        SecretKey key = Keys.hmacShaKeyFor(this.secret.getBytes(StandardCharsets.UTF_8));
        Long expiration = Long.valueOf(1000 * 60 * 60);

        String jwt = Jwts.builder()
            .signWith(key)  
            .issuedAt(new Date())
            .issuedAt(new Date(System.currentTimeMillis() + expiration))
            .subject(user.getIdentity())
            .claim("id_user", user.getId())
            .compact();

        return jwt;
    }

    public Long extractId(String token) {
        Jws<Claims> jws = Jwts.parser()
            .verifyWith(Keys.hmacShaKeyFor(this.secret.getBytes(StandardCharsets.UTF_8)))
            .build()
            .parseSignedClaims(token);

        return  jws.getPayload().get("id_user", Long.class);
    }

    public String exctractIdentity(String token) {
        Jws<Claims> jws = Jwts.parser()
            .verifyWith(Keys.hmacShaKeyFor(this.secret.getBytes(StandardCharsets.UTF_8)))
            .build()
            .parseSignedClaims(token);

        return jws.getPayload().getSubject();
    }

    public boolean validateToken(String token) {
        try {
            Jwts.parser()
                .verifyWith(Keys.hmacShaKeyFor(this.secret.getBytes(StandardCharsets.UTF_8)))
                .build()
                .parseSignedClaims(token);
            return true;
        } catch (AuthorizationDeniedException e) {
            return false;
        }
    }
}
