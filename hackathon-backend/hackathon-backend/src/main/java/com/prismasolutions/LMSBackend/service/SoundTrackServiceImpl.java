package com.prismasolutions.LMSBackend.service;

import com.prismasolutions.LMSBackend.Dto.LinksDto;
import com.prismasolutions.LMSBackend.Dto.MusicDto;
import org.springframework.boot.configurationprocessor.json.JSONArray;
import org.springframework.boot.configurationprocessor.json.JSONException;
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

    static long id = 0;
    @Override
    public LinksDto getMusicLink(String musicTitle) throws JSONException {
        String serviceUrl = "https://musicapi13.p.rapidapi.com/search";
        String requestBody = "{\"track\": \""+musicTitle+"\",\"artist\": \"\",\"type\": \"track\",\"sources\": [\"spotify\",\"youtube\"]}";

        HttpClient httpClient = HttpClient.newHttpClient();
        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create("https://musicapi13.p.rapidapi.com/search"))
                .header("content-type", "application/json")
                .header("X-RapidAPI-Key", "bb402aaa7dmsh91aa4e3a0c58881p1b49a0jsnee4e354f197c")
                .header("X-RapidAPI-Host", "musicapi13.p.rapidapi.com")
                .method("POST", HttpRequest.BodyPublishers.ofString("{\r\"track\": \""+musicTitle+"\",\r\"artist\": \"\",\r\"type\": \"track\",\r\"sources\": [\r\"spotify\",\r\"youtube\"\r]\r}"))
                .build();


        LinksDto linkFinal = new LinksDto();
        try {
            HttpResponse<String> response = HttpClient.newHttpClient().send(request, HttpResponse.BodyHandlers.ofString());
            //System.out.println(response.body());
            JSONObject jsonObject = new JSONObject(response.body());
            if(!jsonObject.isNull("tracks")) {
                JSONArray auxLinks = jsonObject.getJSONArray("tracks");

                String spotifyLink = auxLinks.getJSONObject(0).getJSONObject("data").getString("url");
                String youtubeLink = auxLinks.getJSONObject(1).getJSONObject("data").getString("url");
                linkFinal.setSpotifyLink(spotifyLink);
                linkFinal.setYoutubeLink(youtubeLink);

            }

        }catch (JSONException e){
            throw e;
        }
        catch (Exception e) {
//            e.printStackTrace();
        }
        return linkFinal;
    }

    @Override
    public List<MusicDto> getSoundtrack(String movieTitle) throws JSONException {
        String serviceUrl = "https://api.openai.com/v1/chat/completions";
        String bearerToken = "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";
        String requestBody = "{\"model\": \"gpt-3.5-turbo\", \"messages\": [{\"role\": \"user\", \"content\": \"Add visza az összes zene számot a következő című filmből: "+movieTitle+" , a választ add vissza ebben a formában : [{'musicTitle'}]\"}]}";

        HttpClient httpClient = HttpClient.newHttpClient();
        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(serviceUrl))
                .header("Content-Type", "application/json")
                .header("Authorization", "Bearer " + bearerToken)
                .POST(HttpRequest.BodyPublishers.ofString(requestBody))
                .build();

        List<String> titlesFinal = new ArrayList<>();
        List<MusicDto> musics = new ArrayList<>();
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

                for (String item : titlesFinal) {
                    LinksDto link = getMusicLink(item);
                    MusicDto track = new MusicDto();
                    track.setId(id);
                    track.setTitle(item);
                    track.setSpotifyLink(link.getSpotifyLink());
                    track.setYoutubeLink(link.getYoutubeLink());
                    musics.add(track);
                    id += 1;
                }
            }
        }catch (JSONException e){
            throw e;
        }
        catch (Exception e) {
            e.printStackTrace();
        }
        System.out.println(musics);

        return musics;
    }
}
