package com.prismasolutions.LMSBackend.service;

import com.prismasolutions.LMSBackend.Dto.MovieDto;
import com.prismasolutions.LMSBackend.util.Utility;
import lombok.AllArgsConstructor;
import org.springframework.boot.configurationprocessor.json.JSONException;
import org.springframework.stereotype.Service;
import java.util.List;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.List;
@Service
@AllArgsConstructor
public class StreamServiceImpl implements StreamService {

    private final Utility utility;
    @Override
    public List<MovieDto> getAllByTitle(String title) throws IOException, JSONException {

        String url = "https://streaming-availability.p.rapidapi.com/v2/search/title";
        String apiKey = "1f6ac10dcamsh091a1e09cced23ap1d57afjsn886a6d651fd1"; // ide helyettesítsd be a saját API kulcsodat

        // query paraméterek hozzáadása
//        String language = "en"; // példa nyelv
        String country = "RO"; // példa ország
        String queryParams = String.format("?country=%s&title=%s", country, title);

        URL apiUrl = new URL(url + queryParams);
        HttpURLConnection connection = (HttpURLConnection) apiUrl.openConnection();

        // RapidAPI fejlécek hozzáadása
        connection.setRequestProperty("x-rapidapi-host", "streaming-availability.p.rapidapi.com");
        connection.setRequestProperty("x-rapidapi-key", apiKey);

        // GET kérés elküldése
        connection.setRequestMethod("GET");

        // válasz olvasása
        BufferedReader responseReader = new BufferedReader(new InputStreamReader(connection.getInputStream()));
        String responseLine;
        StringBuilder response = new StringBuilder();
        while ((responseLine = responseReader.readLine()) != null) {
            response.append(responseLine.trim());
        }
        responseReader.close();

        List<MovieDto> movieDto = utility.convertJSONToMovieDto(response.toString());
        // válasz feldolgozása
//        System.out.println(response.toString());

        return movieDto;
    }
}
