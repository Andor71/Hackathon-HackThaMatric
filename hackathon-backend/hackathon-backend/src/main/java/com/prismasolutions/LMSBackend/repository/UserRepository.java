package com.prismasolutions.LMSBackend.repository;

import com.prismasolutions.LMSBackend.entity.UserEntity;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.util.Optional;

@Repository
public interface UserRepository extends JpaRepository<UserEntity, Long> {
    boolean existsByEmail(String email);
    boolean existsByValidationCode(String validationCode);
    Optional<UserEntity> findByEmail(String email);
    Optional<UserEntity> findByValidationCode(String validationCode);
}
