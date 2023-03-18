package com.prismasolutions.LMSBackend.Dto;

import lombok.Data;

@Data
public class MovieDto {
    Long id;
    String title;
    String type;
    String overview;
    StreamingInfoDto streamingInfoDto;

    String youtubeLink;
}
