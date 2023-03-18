package com.prismasolutions.LMSBackend.mapper;

import com.prismasolutions.LMSBackend.dto.user.UserDto;
import com.prismasolutions.LMSBackend.entity.UserEntity;
import org.springframework.stereotype.Component;

@Component
public class UserMapper {

    public UserEntity toEntity(UserDto dto){
        return new UserEntity();
    }


    public UserDto toDto(UserEntity entity){
        return new UserDto();
    }
}
