package com.prismasolutions.LMSBackend.controller;


import lombok.AllArgsConstructor;
import org.springframework.http.ResponseEntity;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.CrossOrigin;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;

@RequestMapping("/stream")
@CrossOrigin
@AllArgsConstructor
@Controller
public class StreamController {


    @GetMapping("/ping")
    public ResponseEntity<?> ping(){
        return ResponseEntity.ok().build();
    }
}
