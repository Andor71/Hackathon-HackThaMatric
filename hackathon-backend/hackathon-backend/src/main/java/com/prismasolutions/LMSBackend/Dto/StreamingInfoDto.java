package com.prismasolutions.LMSBackend.Dto;

import lombok.Data;

@Data
public class StreamingInfoDto {

    Long id;
    String region;

    StreamingDto streamingDto;

}
