package com.prismasolutions.LMSBackend.config;

import io.jsonwebtoken.ExpiredJwtException;
import io.jsonwebtoken.JwtException;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.stereotype.Component;
import org.springframework.web.filter.GenericFilterBean;

import javax.servlet.FilterChain;
import javax.servlet.ServletException;
import javax.servlet.ServletRequest;
import javax.servlet.ServletResponse;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.IOException;

@Component
public class AuthenticationFilter extends GenericFilterBean {
    private final TokenAuthenticationService tokenService;

    @Autowired
    public AuthenticationFilter(TokenAuthenticationService tokenService) {
        this.tokenService = tokenService;
    }

    @Override
    public void doFilter(ServletRequest request, ServletResponse response, FilterChain chain) throws IOException, ServletException {
        Logger logger = LoggerFactory.getLogger(AuthenticationFilter.class);
        try {
            Authentication authentication = tokenService
                    .verifyAuthentication((HttpServletRequest) request);

            SecurityContextHolder.getContext()
                    .setAuthentication(authentication);

            chain.doFilter(request, response);
        }
        catch (ExpiredJwtException e) {
            HttpServletResponse resp = (HttpServletResponse) response;

            resp.getWriter().print("Jwt token has expired!");
            resp.setStatus(HttpServletResponse.SC_UNAUTHORIZED);
            logger.warn("REFUSED " + request.getRemoteHost() + " -- Expired JWT Token");
        }
        catch (JwtException e) {
            HttpServletResponse resp = (HttpServletResponse) response;
            resp.getWriter().print("DENIED");
            resp.setStatus(HttpServletResponse.SC_UNAUTHORIZED);
            logger.warn("REFUSED " + request.getRemoteHost() + " -- Exception: " + e.getMessage());
        }
    }
}
