package com.prismasolutions.LMSBackend.service;

import com.prismasolutions.LMSBackend.dto.user.UserDto;
import com.prismasolutions.LMSBackend.entity.UserEntity;
import com.prismasolutions.LMSBackend.mapper.UserMapper;
import com.prismasolutions.LMSBackend.repository.UserRepository;
import com.prismasolutions.LMSBackend.util.Utility;
import lombok.AllArgsConstructor;
import org.hibernate.service.spi.ServiceException;
import org.springframework.security.crypto.bcrypt.BCryptPasswordEncoder;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.Optional;


@Service
@AllArgsConstructor
public class UserService implements IUserService{

    private final Utility utility;
    private final BCryptPasswordEncoder bCryptPasswordEncoder;
    private final UserRepository userRepository;
    private final UserMapper userMapper;

    @Override
    public String generateValidBase64Code() {
        String validation = IUserService.generateBase64Code();
        while (userRepository.existsByValidationCode(validation))
            validation = IUserService.generateBase64Code();

        return validation;
    }

    @Override
    public void activateUserAccount(String validationCode) {
        Optional<UserEntity> user = userRepository.findByValidationCode(validationCode);

        if (user.isEmpty())
            throw new ServiceException("Invalid validation code!");

        user.get().setActive(true);
        user.get().setValidationCode(null);
    }

    /**
     * @inheritDoc Get information about the current logged in user in Dto form.
     */
    @Override
    public UserDto getCurrentUserDto() {
        return userMapper.toDto(utility.getCurrentUser());
    }

    @Override
    public void startPasswordRecovery(UserEntity user) {
        if (!user.getActive())
            throw new ServiceException("User has not confirmed his email.");
        user.setValidationCode(this.generateValidBase64Code());
        userRepository.save(user);
    }

    @Override
    public void startPasswordRecovery(String email) {
        if (email.isEmpty())
            throw new ServiceException("The email parameter is required");

        Optional<UserEntity> userEntityOptional = userRepository.findByEmail(email);

        if (userEntityOptional.isEmpty())
            throw new ServiceException("No user with that email address exists");

        UserEntity user = userEntityOptional.get();

        this.startPasswordRecovery(user);
    }

    @Override
    public void verifyRecoveryCode(String recoveryCode) {
        if (recoveryCode.isEmpty())
            throw new ServiceException("Recovery Code is empty");

        Optional<UserEntity> userEntityOptional = userRepository.findByValidationCode(recoveryCode);

        if (userEntityOptional.isEmpty() || !userEntityOptional.get().getActive())
            throw new ServiceException("Invalid Recovery Code");
    }

    @Override
    @Transactional(rollbackFor = Exception.class)
    public void changePassword(String recoveryCode, String newPassword) {
        if (recoveryCode.isEmpty() || newPassword.isEmpty())
            throw new ServiceException("Recovery Code or Password is empty");

        Optional<UserEntity> userEntityOptional = userRepository.findByValidationCode(recoveryCode);

        if (userEntityOptional.isEmpty())
            throw new ServiceException("Invalid recovery code");

        if (!userEntityOptional.get().getActive())
            throw new ServiceException("User is not active");

        if (IUserService.isPasswordInvalid(newPassword))
            throw new ServiceException("Password must have a minimum of 8 characters and at least one Uppercase letter");

        userEntityOptional.get().setPassword(bCryptPasswordEncoder.encode(newPassword));
        userEntityOptional.get().setValidationCode(null);
    }

    /**
     * @inheritDoc Set the password of the current user
     */
    @Override
    @Transactional(rollbackFor = Exception.class)
    public void setPassword(String newPassword) {
        UserEntity currentUser = utility.getCurrentUser();

        if (IUserService.isPasswordInvalid(newPassword))
            throw new ServiceException("Password must have a minimum of 8 characters and at least one Uppercase letter");

        currentUser.setPassword(bCryptPasswordEncoder.encode(newPassword));
    }

    /**
     * @inheritDoc Set the name of the current user
     */
    @Override
    @Transactional(rollbackFor = Exception.class)
    public void setName(String firstName, String lastName) {
        UserEntity currentUser = utility.getCurrentUser();

        currentUser.setFirstName(firstName);
        currentUser.setLastName(lastName);
    }
}
