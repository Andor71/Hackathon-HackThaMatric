package com.prismasolutions.LMSBackend.controller;

import com.prismasolutions.LMSBackend.service.SoundTrackService;
import lombok.AllArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.springframework.http.ResponseEntity;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.CrossOrigin;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;

@RequestMapping("/sound")
@CrossOrigin
@AllArgsConstructor
@Controller
@Slf4j
public class SoundtrackController {

    private final SoundTrackService soundTrackService;

    @PostMapping("/get-soundtrack")
    public  ResponseEntity<?> getSoundtrack(@RequestParam String movieTitle)
    {
        log.info("Entered in getSoundtrack");
        try{
            return ResponseEntity.ok().body(soundTrackService.getSoundtrack(movieTitle));
        }catch (Exception e){
            return  ResponseEntity.badRequest().build();
        }
    }
    @PostMapping("/get-link")
    public  ResponseEntity<?> getMusicLink(@RequestParam String musicTitle)
    {
        log.info("Entered in getMusicLink");
        try{
            return ResponseEntity.ok().body(soundTrackService.getMusicLink(musicTitle));
        }catch (Exception e){
            return  ResponseEntity.badRequest().build();
        }
    }
}