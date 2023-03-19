package com.prismasolutions.LMSBackend.util;

import com.prismasolutions.LMSBackend.Dto.MovieDto;
import com.prismasolutions.LMSBackend.Dto.StreamingDto;
import com.prismasolutions.LMSBackend.Dto.StreamingInfoDto;
import org.springframework.boot.configurationprocessor.json.JSONArray;
import org.springframework.boot.configurationprocessor.json.JSONException;
import org.springframework.boot.configurationprocessor.json.JSONObject;
import org.springframework.stereotype.Component;

import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;

@Component
public class Utility {
    static Long movieID = Long.valueOf(0);
    static Long streamInfoId = Long.valueOf(0);
    static Long streamId = Long.valueOf(0);
    public List<MovieDto> convertJSONToMovieDto(String data) throws JSONException {
        JSONObject json = new JSONObject(data);
        JSONArray jsonArray = json.getJSONArray("result");
        List<MovieDto> movieDtos = new ArrayList<>();
        for (int j = 0; j < jsonArray.length(); j++) {
            try {
                json = jsonArray.getJSONObject(j);

                MovieDto movieDto = new MovieDto();

                movieDto.setId(movieID++);
                movieDto.setType(json.getString("type"));
                movieDto.setOverview(json.getString("overview"));
                movieDto.setYoutubeLink(json.getString("youtubeTrailerVideoLink"));
                movieDto.setTitle(json.getString("title"));
                movieDto.setYear(json.getString("year"));
                movieDto.setMinimumAge(json.getString("advisedMinimumAudienceAge"));
                movieDto.setImdbId(json.getString("imdbId"));
                movieDto.setImdbRating(json.getString("imdbRating"));
                movieDto.setImdbVoteCount(json.getString("imdbVoteCount"));
                if(!json.isNull("backdropURLs")){
                    movieDto.setBackdropURLs(json.getJSONObject("backdropURLs").getString("original"));
                }
                if(!json.isNull("posterURLs")) {
                    movieDto.setPoster(json.getJSONObject("posterURLs").getString("original"));
                }
                List<String> casts = new ArrayList<>();
                if(!json.isNull("cast")){
                    for(int k = 0 ; k < json.getJSONArray("cast").length();k++){
                        casts.add(json.getJSONArray("cast").getString(k));
                    }
                }
                movieDto.setCasts(casts);

                List<String> genres = new ArrayList<>();
                if(!json.isNull("genres")){
                    for(int k = 0 ; k < json.getJSONArray("genres").length();k++){
                        genres.add(json.getJSONArray("genres").getJSONObject(k).getString("name"));
                    }
                }
                movieDto.setGenres(genres);


                StreamingInfoDto streamingInfoDto = new StreamingInfoDto();

                List<StreamingDto> streamingDtos = new ArrayList<>();

                if (!json.getJSONObject("streamingInfo").toString().equals("{}")) {

                    JSONObject jsonStreamingInfo = json.getJSONObject("streamingInfo");

                    Iterator<String> keys = jsonStreamingInfo.keys();

                    String region = keys.next();
                    streamingInfoDto.setRegion(region);

                    JSONObject jsonRegion = jsonStreamingInfo.getJSONObject(region);

                    keys = jsonRegion.keys();

                    while (keys.hasNext()) {
                        String key = keys.next();
                        StreamingDto streamingDto = new StreamingDto();

                        streamingDto.setId(streamId++);
                        streamingDto.setName(key);

                        JSONObject streaming = jsonRegion.getJSONArray(key).getJSONObject(0);

                        Boolean hasAudio = false;
                        for (int i = 0; i < jsonRegion.getJSONArray(key).length(); i++) {
                            if (jsonRegion.getJSONArray(key).getJSONObject(i).has("audios")) {

                                streaming = jsonRegion.getJSONArray(key).getJSONObject(i);
                            }
                        }

                        streamingDto.setLink("link");

                        Iterator<String> keysStreaming = streaming.keys();
                        List<String> subtitles = new ArrayList<>();
                        List<String> languages = new ArrayList<>();

                        if (!streaming.isNull("audios") && streaming.get("audios") != null) {
                            if (streaming.getJSONArray("audios").isNull(0)) {
                                JSONArray jsonAudios = streaming.getJSONArray("audios");

                                for (int i = 0; i < jsonAudios.length(); i++) {
                                    languages.add(jsonAudios.getJSONObject(i).getString("language"));
                                }
                            }
                        }

                        if (!streaming.isNull("audios") && streaming.get("subtitles") != null) {
                            JSONArray jsonSubs = streaming.getJSONArray("subtitles");
                            for (int i = 0; i < jsonSubs.length(); i++) {
                                subtitles.add(jsonSubs.getJSONObject(i).getJSONObject("locale").getString("language"));

                            }
                        }
                        streamingDto.setLang(languages);
                        streamingDto.setSub(subtitles);
                        streamingDtos.add(streamingDto);
                    }


                }


                streamingInfoDto.setId(streamInfoId++);
                streamingInfoDto.setStreamingDto(streamingDtos);
                movieDto.setStreamingInfoDto(streamingInfoDto);
                movieDtos.add(movieDto);
            }catch (Exception e){
                //do nothing
            }
        }

        return movieDtos;
    }

}
