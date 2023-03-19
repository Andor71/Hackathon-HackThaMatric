package com.prismasolutions.LMSBackend.service;

import com.prismasolutions.LMSBackend.Dto.MovieDto;
import com.prismasolutions.LMSBackend.Dto.MoviesByStreamingDto;
import com.prismasolutions.LMSBackend.util.Utility;
import lombok.AllArgsConstructor;
import org.springframework.boot.configurationprocessor.json.JSONException;
import org.springframework.stereotype.Service;
import java.util.ArrayList;
import java.util.List;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
@Service
@AllArgsConstructor
public class StreamServiceImpl implements StreamService {

    private final Utility utility;
    @Override
    public List<MoviesByStreamingDto> getAllByTitle(String title) throws IOException, JSONException {

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

        List<MovieDto> movieDtos = utility.convertJSONToMovieDto(response.toString());

        List<MoviesByStreamingDto> moviesByGenreDtos = new ArrayList<>();

        List<String> streaming = new ArrayList<>();

        for(MovieDto movies : movieDtos){
            for(int i = 0 ; i < movies.getStreamingInfoDto().getStreamingDto().size(); i++){
                if(!streaming.contains(movies.getStreamingInfoDto().getStreamingDto().get(i).getName())){
                    streaming.add(movies.getStreamingInfoDto().getStreamingDto().get(i).getName());
                }
            }
        }

        streaming.add(null);

        Long id = Long.valueOf(0);
        for(String stream : streaming){
            MoviesByStreamingDto moviesByStreamingDto = new MoviesByStreamingDto();
            moviesByStreamingDto.setId(id++);
            moviesByStreamingDto.setStreaming(stream);
            moviesByStreamingDto.setMovies(new ArrayList<>());
            moviesByGenreDtos.add(moviesByStreamingDto);
        }
        System.out.println(moviesByGenreDtos);
        System.out.println(movieDtos);
        for(MoviesByStreamingDto moviesByStreamingDto : moviesByGenreDtos){
            for(int i = 0 ; i < movieDtos.size() ; i++) {
                if(movieDtos.get(i).getStreamingInfoDto().getStreamingDto().size() == 0 && moviesByStreamingDto.getStreaming() == null){
                    moviesByStreamingDto.getMovies().add(movieDtos.get(i));
                }
                for(int j = 0 ; j < movieDtos.get(i).getStreamingInfoDto().getStreamingDto().size(); j++){

                    System.out.println( movieDtos.get(i).getStreamingInfoDto().getStreamingDto());
                    if (movieDtos.get(i).getStreamingInfoDto().getStreamingDto().get(j).getName().equals(moviesByStreamingDto.getStreaming())) {
                        moviesByStreamingDto.getMovies().add(movieDtos.get(i));
                    }
                }

            }
        }

        return moviesByGenreDtos;
    }
}
