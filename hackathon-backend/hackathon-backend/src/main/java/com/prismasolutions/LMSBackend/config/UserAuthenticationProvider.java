package com.prismasolutions.LMSBackend.config;

import com.prismasolutions.LMSBackend.entity.UserEntity;
import com.prismasolutions.LMSBackend.repository.UserRepository;
import lombok.AllArgsConstructor;
import org.springframework.security.authentication.AuthenticationProvider;
import org.springframework.security.authentication.BadCredentialsException;
import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.AuthenticationException;
import org.springframework.security.core.GrantedAuthority;
import org.springframework.security.core.authority.SimpleGrantedAuthority;
import org.springframework.security.crypto.bcrypt.BCryptPasswordEncoder;
import org.springframework.stereotype.Component;

import java.util.ArrayList;
import java.util.List;
import java.util.Optional;

@Component
@AllArgsConstructor
public class UserAuthenticationProvider implements AuthenticationProvider {
    private final UserRepository userRepository;
    private final BCryptPasswordEncoder bCryptPasswordEncoder;

    @Override
    public Authentication authenticate(Authentication authentication) throws AuthenticationException {
        String email = authentication.getPrincipal().toString();
        String password = authentication.getCredentials().toString();

        if(email == null || password == null)
            throw new BadCredentialsException("Email or password is null");

        Optional<UserEntity> user = userRepository.findByEmail(email);

        if(user.isEmpty() || !bCryptPasswordEncoder.matches(password, user.get().getPassword()))
            throw new BadCredentialsException("Invalid login credentials");

        if(!user.get().getActive())
            throw new BadCredentialsException("Account has not been activated! Check confirmation email.");

        List<GrantedAuthority> authorities = new ArrayList<>();
        authorities.add(new SimpleGrantedAuthority(user.get().getRole()));

        return new UsernamePasswordAuthenticationToken(user.get().getId(), null, authorities);
    }

    @Override
    public boolean supports(Class<?> aClass) {
        return aClass.equals(UsernamePasswordAuthenticationToken.class);
    }
}
