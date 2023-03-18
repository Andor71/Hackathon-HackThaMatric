package com.prismasolutions.LMSBackend.controller;

import com.prismasolutions.LMSBackend.service.MusicService;
import com.prismasolutions.LMSBackend.service.StreamService;
import lombok.AllArgsConstructor;
import org.springframework.http.ResponseEntity;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.*;

@RequestMapping("/music")
@CrossOrigin
@AllArgsConstructor
@Controller
public class MusicController {

    private final MusicService musicService;
    @PostMapping("/get-by-music")
    public  ResponseEntity<?> getByMusic(@RequestParam String musicTitle)
    {
        try{
            return ResponseEntity.ok().body(musicService.getAllMoviesByMusic(musicTitle));
        }catch (Exception e){
            return  ResponseEntity.badRequest().build();
        }
    }
}