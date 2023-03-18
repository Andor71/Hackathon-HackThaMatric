package com.prismasolutions.LMSBackend.Dto;

import lombok.Data;

import java.util.List;

@Data
public class StreamingInfoDto {

    Long id;
    String region;

    List<StreamingDto> streamingDto;

}
