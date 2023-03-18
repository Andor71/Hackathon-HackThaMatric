package com.prismasolutions.LMSBackend.service;

import com.prismasolutions.LMSBackend.dto.user.UserDto;
import com.prismasolutions.LMSBackend.entity.UserEntity;
import com.prismasolutions.LMSBackend.repository.UserRepository;

import java.security.SecureRandom;
import java.util.Base64;

public interface IUserService {
    /**
     * Checks if a string is of a valid email form
     * @param email The email string
     * @return True if it has email form, false otherwise
     */
    static boolean isInvalidEmail(String email) {
        return !email.matches("^[a-z1-9.\\-_]{1,64}@([a-z]{2,6}\\.)+([a-z]{2,})$");
    }

    /**
     * Generates a random base 64 String of a specified length
     * @param byteLength The length of the String
     * @return The random String
     */
    static String generateRandomBase64String(int byteLength) {
        SecureRandom secureRandom = new SecureRandom();
        byte[] token = new byte[byteLength];
        secureRandom.nextBytes(token);
        return Base64.getUrlEncoder().withoutPadding().encodeToString(token); //base64 encoding
    }

    /**
     * Generate a random one time password
     * @return the temporary password
     */
    static String generateBase64Code() {
        return generateRandomBase64String(20);
    }

    static String generateOnetimePassword() {
        String password = generateRandomBase64String(8);

        while(isPasswordInvalid(password))
            password = generateRandomBase64String(8);

        return password;
    }

    /**
     * Keeps generating a Base 64 Code ({@link #generateBase64Code()}), checking it against the repository
     * ({@link UserRepository}) for it to be unique.
     * @return The valid Base 64 Code.
     */
    String generateValidBase64Code();

    /**
     * Checks if a password has at least 8 characters and at least one uppercase character
     * @param password The password to check
     * @return True if the password meets the constraints, false if not
     */
    static boolean isPasswordInvalid(String password) {
        return password.length() < 8 || password.equals(password.toLowerCase());
    }

    /**
     * Takes in a validation code and activates the corresponding {@link UserEntity}
     * @param validationCode The validation code
     */
    void activateUserAccount(String validationCode);

    /**
     * Fetches a {@link UserDto} of the logged in user containing all user data except password.
     * @return The {@link UserDto}
     */
    UserDto getCurrentUserDto();

    /**
     * Initiate the password recovery process for a user
     * @param email The email address of the user
     */
    void startPasswordRecovery(String email);

    /**
     * Initiate the password recovery process for a user
     * @param user User
     */
    void startPasswordRecovery(UserEntity user);

    /**
     * Verify if the Recovery Code is valid
     * @param recoveryCode The Code to be verified
     */
    void verifyRecoveryCode(String recoveryCode);

    /**
     * Changes the password for a user who is in the password recovery process
     * @param recoveryCode The Recovery Code
     * @param newPassword The new Password for the user
     */
    void changePassword(String recoveryCode, String newPassword);

    /**
     * Sets the password of the current user
     * @param newPassword The new password
     */
    void setPassword(String newPassword);

    /**
     * Sets the first and last name of the current user
     * @param firstName First Name
     * @param lastName Last Name
     */
    void setName(String firstName, String lastName);
}
