package com.prismasolutions.LMSBackend.controller;

import com.prismasolutions.LMSBackend.config.TokenAuthenticationService;
import com.prismasolutions.LMSBackend.config.UserAuthenticationProvider;
import com.prismasolutions.LMSBackend.dto.user.UserLoginDto;
import com.prismasolutions.LMSBackend.service.IUserService;
import lombok.AllArgsConstructor;
import org.hibernate.service.spi.ServiceException;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.security.authentication.BadCredentialsException;
import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.*;

import javax.servlet.http.HttpServletResponse;
import javax.validation.Valid;
import javax.validation.constraints.NotBlank;

@Controller
@RequestMapping("/user")
@CrossOrigin
@AllArgsConstructor
public class UserController {

    private final TokenAuthenticationService tokenAuthenticationService;
    private final UserAuthenticationProvider userAuthenticationProvider;
    private final IUserService userService;

    @GetMapping("/health-check")
    public ResponseEntity<?> healthCheck() {
        return ResponseEntity.ok().build();
    }

    @PostMapping("/login")
    public ResponseEntity<?> login(@Valid @RequestBody UserLoginDto userDto,
                                   HttpServletResponse response) {
        try {
            UsernamePasswordAuthenticationToken usr = new UsernamePasswordAuthenticationToken(userDto.getEmail(),
                    userDto.getPassword());

            Authentication auth = userAuthenticationProvider.authenticate(usr);
            tokenAuthenticationService.authenticationResponse(response, auth);

            SecurityContextHolder.getContext().setAuthentication(auth);

            return ResponseEntity.ok().body(userService.getCurrentUserDto());
        }
        catch (BadCredentialsException e) {
            return new ResponseEntity<>(e.getMessage(), HttpStatus.BAD_REQUEST);
        }
    }

    @PatchMapping("/set-name")
    public ResponseEntity<?> setName(@NotBlank @RequestParam String firstName,
                                     @NotBlank @RequestParam String lastName) {
        try {
            userService.setName(firstName, lastName);
            return ResponseEntity.ok().build();
        }
        catch (ServiceException e) {
            return ResponseEntity.badRequest().body(e.getMessage());
        }
    }
}
