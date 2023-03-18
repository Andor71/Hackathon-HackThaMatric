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

    public MovieDto convertJSONToMovieDto(String data) throws JSONException {
        JSONObject json = new JSONObject(data);
        json = json.getJSONArray("result").getJSONObject(0);
        MovieDto movieDto = new MovieDto();

        movieDto.setId(movieID++);
        movieDto.setType(json.getString("type"));
        movieDto.setOverview(json.getString("overview"));
        movieDto.setYoutubeLink(json.getString("youtubeTrailerVideoLink"));
        movieDto.setTitle(json.getString("title"));
        StreamingInfoDto streamingInfoDto = new StreamingInfoDto();

        JSONObject jsonStreamingInfo = json.getJSONObject("streamingInfo");

        Iterator<String> keys = jsonStreamingInfo.keys();

        String region = keys.next();
        streamingInfoDto.setRegion(region);

        JSONObject jsonRegion = jsonStreamingInfo.getJSONObject(region);

        keys = jsonRegion.keys();
        List<StreamingDto> streamingDtos = new ArrayList<>();
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

        streamingInfoDto.setId(streamInfoId++);
        streamingInfoDto.setStreamingDto(streamingDtos);
        movieDto.setStreamingInfoDto(streamingInfoDto);


        return movieDto;
    }

}
