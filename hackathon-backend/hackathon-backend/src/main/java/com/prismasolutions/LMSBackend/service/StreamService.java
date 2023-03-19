package com.prismasolutions.LMSBackend.service;

import com.prismasolutions.LMSBackend.Dto.MovieDto;
import com.prismasolutions.LMSBackend.Dto.MoviesByStreamingDto;
import org.springframework.boot.configurationprocessor.json.JSONException;

import java.io.IOException;
import java.net.MalformedURLException;
import java.util.List;

public interface StreamService {

    List<MoviesByStreamingDto> getAllByTitle(String title) throws IOException, JSONException;
}
