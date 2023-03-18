package com.prismasolutions.LMSBackend.config;

import io.jsonwebtoken.Claims;
import io.jsonwebtoken.Jwts;
import io.jsonwebtoken.SignatureAlgorithm;
import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.GrantedAuthority;
import org.springframework.security.core.authority.SimpleGrantedAuthority;
import org.springframework.stereotype.Component;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;

@Component
public class TokenAuthenticationService {
    private final long EXPIRATION_TIME = 24 * 60 * 60 * 1000;
    private final String SECRET = "XiEo8h396Klje2";
    private final String HEADER_STRING = "Authorization";
    private final String TOKEN_PREFIX = "Bearer";

    public void authenticationResponse(HttpServletResponse response, Authentication auth) {
        String JWT = this.generateJwsToken(auth);

        response.addHeader("Access-Control-Expose-Headers", "Authorization");
        response.addHeader(HEADER_STRING, TOKEN_PREFIX + " " + JWT);
    }

    public Authentication verifyAuthentication(String jwsToken) {
        final Claims claims = Jwts.parser()
                .setSigningKey(SECRET)
                .parseClaimsJws(jwsToken)
                .getBody();

        String username = claims.getSubject();

        ArrayList<GrantedAuthority> authorities = new ArrayList<>();

        Arrays.stream(claims.get("authorities").toString().split(","))
                .forEach(s -> {
                    String auth = s.replaceAll("[{}\\[\\]]", "");
                    auth = auth.substring(auth.lastIndexOf("=") + 1);
                    authorities.add(new SimpleGrantedAuthority(auth));
                });

        UsernamePasswordAuthenticationToken usernamePasswordAuthenticationToken =
                new UsernamePasswordAuthenticationToken(claims.getSubject(), null, authorities);

        return username != null ?
                usernamePasswordAuthenticationToken : null;
    }

    public Authentication verifyAuthentication(HttpServletRequest request) {
        String jwsToken = request.getHeader(HEADER_STRING);

        if(jwsToken == null) {
            jwsToken = request.getParameter("Authorization");

            if(jwsToken == null)
                return null;
        }
        else {
            jwsToken = jwsToken.substring(7);
        }

        return verifyAuthentication(jwsToken);
    }

    public String generateJwsToken(Authentication auth) {
        return Jwts.builder()
                .setSubject(auth.getPrincipal().toString())
                .claim("authorities", auth.getAuthorities())
                .setExpiration(new Date(System.currentTimeMillis() + EXPIRATION_TIME))
                .signWith(SignatureAlgorithm.HS512, SECRET)
                .compact();
    }

}
