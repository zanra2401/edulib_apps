package com.edulib.edulib.repository;

import java.util.List;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import com.edulib.edulib.models.User;

@Repository
public interface UserRepository extends JpaRepository<User, Long> {
    User findByUsername(String username);
    User findByNisn(String nisn);
    List<User> findByPeran(User.Peran peran);
}
