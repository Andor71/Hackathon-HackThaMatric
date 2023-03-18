package com.prismasolutions.LMSBackend.util;


import com.prismasolutions.LMSBackend.entity.UserEntity;
import com.prismasolutions.LMSBackend.repository.UserRepository;
import lombok.AllArgsConstructor;
import org.hibernate.service.spi.ServiceException;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.stereotype.Component;
import org.springframework.transaction.annotation.Transactional;

import java.util.Optional;

@Component
@AllArgsConstructor
public class Utility {
    private final UserRepository userRepository;

    /**
     * Fetches a {@link UserEntity} of the logged in user.
     * @return The {@link UserEntity}
     */
    @Transactional(readOnly = true)
    public UserEntity getCurrentUser() {
        long id;
        try {
            id = Long.parseLong(SecurityContextHolder.getContext().getAuthentication().getPrincipal().toString());
        }
        catch (Exception ex) {
            throw new ServiceException("Current JWS token subject ID not valid. Could not retrieve user");
        }

        Optional<UserEntity> userEntityOptional = userRepository.findById(id);

        if(userEntityOptional.isEmpty())
            throw new ServiceException("Current JWS token subject ID not valid. Could not retrieve user");

        return userEntityOptional.get();
    }

    /**
     * Fetches the user with the given id
     * @param id The id of the user to fetch
     * @return The {@link UserEntity}
     */
    public UserEntity getUserById(Long id) {
        Optional<UserEntity> userEntityOptional = userRepository.findById(id);

        if(userEntityOptional.isEmpty())
            throw new ServiceException("User with ID " + id + " does not exist");

        return userEntityOptional.get();
    }
    public long getSubscribeTimeout() {
        return Long.MAX_VALUE;
    }
}
