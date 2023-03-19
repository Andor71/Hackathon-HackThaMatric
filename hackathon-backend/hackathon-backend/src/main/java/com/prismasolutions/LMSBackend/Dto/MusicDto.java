package com.prismasolutions.LMSBackend.Dto;

import lombok.Data;

@Data
public class MusicDto {
    Long id;
    String title;
    String spotifyLink;
    String youtubeLink;

}
