package com.prismasolutions.LMSBackend.controller;


import com.prismasolutions.LMSBackend.service.StreamService;
import lombok.AllArgsConstructor;
import org.springframework.boot.configurationprocessor.json.JSONException;
import org.springframework.http.ResponseEntity;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.CrossOrigin;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;

@RequestMapping("/stream")
@CrossOrigin
@AllArgsConstructor
@Controller
public class StreamController {

    private final StreamService streamService;

    @GetMapping("/ping")
    public ResponseEntity<?> ping(){
        return ResponseEntity.ok().build();
    }

    @GetMapping("/get-by-title")
    public  ResponseEntity<?> getByTitle(@RequestParam String title)
    {
        try{
            return ResponseEntity.ok().body(streamService.getAllByTitle(title));
        }catch (JSONException e){
            return ResponseEntity.status(301).build();
        }
        catch (Exception e){
            return  ResponseEntity.badRequest().build();
        }
    }
}
