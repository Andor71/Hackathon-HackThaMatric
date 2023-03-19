package com.prismasolutions.LMSBackend.service;

import org.springframework.boot.configurationprocessor.json.JSONArray;
import org.springframework.boot.configurationprocessor.json.JSONObject;
import org.springframework.stereotype.Service;


import java.net.URI;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.util.*;

@Service
public class MusicServiceImpl implements MusicService {

    @Override
    public List<String> getAllMoviesByMusic(String musicTitle) {
        String serviceUrl = "https://api.openai.com/v1/chat/completions";
        String bearerToken = "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";
        String requestBody = "{\"model\": \"gpt-3.5-turbo\", \"messages\": [{\"role\": \"user\", \"content\": \"Give me every movie that contains the track with the title '"+ musicTitle +"', in the following format : [{'name': 'movieName'}]\"}]}";

        HttpClient httpClient = HttpClient.newHttpClient();
        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(serviceUrl))
                .header("Content-Type", "application/json")
                .header("Authorization", "Bearer " + bearerToken)
                .POST(HttpRequest.BodyPublishers.ofString(requestBody))
                .build();

        List<String> titlesFinal = new ArrayList<>();
        try {
            HttpResponse<String> response = httpClient.send(request, HttpResponse.BodyHandlers.ofString());
            JSONObject jsonObject = new JSONObject(response.body());
            System.out.println("ss");
            while (true){
                if(jsonObject.has("choices")){
                    System.out.println("ff");
                    break;
                }
                System.out.println("dd");
                response = httpClient.send(request, HttpResponse.BodyHandlers.ofString());
                jsonObject = new JSONObject(response.body());
            }

            JSONArray movieNamesArray = jsonObject.getJSONArray("choices");
            JSONObject movieNames = movieNamesArray.getJSONObject(0);

            JSONObject movieTitlesJSON = movieNames.getJSONObject("message");

            Iterator<String> movieKeys = movieTitlesJSON.keys();
            while(movieKeys.hasNext()) {
                String key = movieKeys.next();
                String titles = movieTitlesJSON.getString(key);

                if(key.equals("content")) {
                    String titlesString = titles.trim();

                    int notGoodIndex = titlesString.indexOf("Sorry, as an AI language model");
//                    if(notGoodIndex != -1){
//                        getAllMoviesByMusic(musicTitle);
//                    }
                    titlesString = titlesString.replace("{'name': '", "");
                    titlesString = titlesString.replace("'}, ", "/~");
                    titlesString = titlesString.replace("'}", "");
                    titlesString = titlesString.replace("[", "");
                    titlesString = titlesString.replace("]", "");
                    notGoodIndex = titlesString.indexOf("/");
//                    if(notGoodIndex == -1 ) {
//                        getAllMoviesByMusic(musicTitle);
//                    }
                    titlesFinal = Arrays.asList(titlesString.split("/~"));
                }
            }
            System.out.println(titlesFinal);
            System.out.println();
        } catch (Exception e) {
            e.printStackTrace();
        }

        return titlesFinal;
    }
}
