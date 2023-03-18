package com.prismasolutions.LMSBackend.service;

import org.springframework.boot.configurationprocessor.json.JSONArray;
import org.springframework.boot.configurationprocessor.json.JSONObject;
import org.springframework.stereotype.Service;

import java.net.URI;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Iterator;
import java.util.List;

@Service
public class SoundTrackServiceImpl implements SoundTrackService {

    @Override
    public List<String> getSoundtrack(String movieTitle) {
        String serviceUrl = "https://api.openai.com/v1/chat/completions";
        String bearerToken = "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";
        String requestBody = "{\"model\": \"gpt-3.5-turbo\", \"messages\": [{\"role\": \"user\", \"content\": \"Add visza az összes zene számot a következő című filmből: Batman , a választ add vissza ebben a formában : [{'musicTitle'}]\"}]}";

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
                    if(notGoodIndex != -1){
                        getSoundtrack(movieTitle);
                    }
                    titlesString = titlesString.replace("{'", "");
                    titlesString = titlesString.replace("'}, ", "/~");
                    titlesString = titlesString.replace("'}", "");
                    titlesString = titlesString.replace("[", "");
                    titlesString = titlesString.replace("]", "");
                    notGoodIndex = titlesString.indexOf("/");
                    if(notGoodIndex == -1 ) {
                        getSoundtrack(movieTitle);
                    }
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
