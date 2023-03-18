package com.prismasolutions.LMSBackend.controller;

import com.prismasolutions.LMSBackend.service.MusicService;
import com.prismasolutions.LMSBackend.service.StreamService;
import lombok.AllArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.springframework.http.ResponseEntity;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.*;

@RequestMapping("/music")
@CrossOrigin
@AllArgsConstructor
@Controller
@Slf4j
public class MusicController {

    private final MusicService musicService;

    @PostMapping("/get-by-music")
    public  ResponseEntity<?> getByMusic(@RequestParam String musicTitle)
    {
        log.info("Entered in getByMusic");
        try{
            return ResponseEntity.ok().body(musicService.getAllMoviesByMusic(musicTitle));
        }catch (Exception e){
            return  ResponseEntity.badRequest().build();
        }
    }

    @PostMapping("/get")
    public  ResponseEntity<?> ff()
    {
        log.info("Entered in getByMusic");
        try{
            return ResponseEntity.ok().build();
        }catch (Exception e){
            return  ResponseEntity.badRequest().build();
        }
    }
}