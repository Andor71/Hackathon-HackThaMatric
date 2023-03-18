package com.prismasolutions.LMSBackend.Dto;

import lombok.Data;

import java.util.List;

@Data
public class MovieDto {
    Long id;
    String title;
    String type;
    String overview;
    StreamingInfoDto streamingInfoDto;
    String youtubeLink;

    List<String> casts;
    String year;
    String minimumAge;
    String imdbId;
    String imdbRating;
    String imdbVoteCount;
    String backdropURLs;
    List<String> genres;
    String poster;
}
