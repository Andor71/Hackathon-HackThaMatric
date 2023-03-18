package com.prismasolutions.LMSBackend.Dto;


import lombok.Data;

import java.util.List;

@Data
public class StreamingDto {
    Long id;
    String name;
    String link;
    List<String> lang;
    List<String> sub;
}
