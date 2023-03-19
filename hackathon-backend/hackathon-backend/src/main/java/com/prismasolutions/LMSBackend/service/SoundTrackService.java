package com.prismasolutions.LMSBackend.service;

import com.prismasolutions.LMSBackend.Dto.LinksDto;
import com.prismasolutions.LMSBackend.Dto.MusicDto;
import org.springframework.boot.configurationprocessor.json.JSONException;

import java.util.List;

public interface SoundTrackService {

    List<MusicDto> getSoundtrack(String movieTitle) throws JSONException;
    LinksDto getMusicLink(String musicTitle) throws JSONException;

}
