package com.prismasolutions.LMSBackend.service;

import com.prismasolutions.LMSBackend.Dto.LinksDto;
import com.prismasolutions.LMSBackend.Dto.MusicDto;

import java.util.List;

public interface SoundTrackService {

    List<MusicDto> getSoundtrack(String movieTitle);
    LinksDto getMusicLink(String musicTitle);

}
