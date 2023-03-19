package com.prismasolutions.LMSBackend.Dto;

import lombok.Data;

import java.util.List;

@Data
public class MoviesByStreamingDto {
    Long id;
    String streaming;
    List<MovieDto> movies;
}
