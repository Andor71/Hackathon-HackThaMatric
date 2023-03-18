package com.prismasolutions.LMSBackend.service;

import org.springframework.stereotype.Service;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.nio.charset.StandardCharsets;
import java.util.Base64;
import java.util.List;

@Service
public class MusicServiceImpl implements MusicService {

    @Override
    public List<String> getAllMoviesByMusic(String musicTitle) {

//        try {
//            // Set the API endpoint URL
//            URL url = new URL("https://api.openai.com/v1/chat/completions");
//
//            // Create a connection object
//            HttpURLConnection connection = (HttpURLConnection) url.openConnection();
//
//            // Set the request method to POST
//            connection.setRequestMethod("POST");
//
//            // Set the request headers
//            connection.setRequestProperty("Content-Type", "application/x-www-form-urlencoded");
//
//
//            // Enable output and input on the connection
//            connection.setDoOutput(true);
//            connection.setDoInput(true);
//
//            // Define the request parameters
//            String params = "param1=value1&param2=value2";
//
//            // Get the output stream of the connection and write the parameters to it
//            OutputStream outputStream = connection.getOutputStream();
//            outputStream.write(params.getBytes("UTF-8"));
//            outputStream.flush();
//            outputStream.close();
//
//            // Read the response from the server
//            BufferedReader in = new BufferedReader(new InputStreamReader(connection.getInputStream()));
//            String line;
//            StringBuffer response = new StringBuffer();
//            while ((line = in.readLine()) != null) {
//                response.append(line);
//            }
//            in.close();
//
//            // Print the response
//            System.out.println(response.toString());
//        } catch (Exception e) {
//            e.printStackTrace();
//        }

//        try {
//            // Set the API endpoint URL
//            URL url = new URL("https://api.openai.com/v1/chat/completions");
//
//            // Create a connection object
//            HttpURLConnection connection = (HttpURLConnection) url.openConnection();
//
//            // Set the request method to POST
//            connection.setRequestMethod("POST");
//
//            // Set the request headers
//            connection.setRequestProperty("Content-Type", "application/x-www-form-urlencoded");
//
//            String authHeader = "Bearer " + "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" ;
//            connection.setRequestProperty("Auth", authHeader);
//
//            // Enable output and input on the connection
//            connection.setDoOutput(true);
//            connection.setDoInput(true);
//
//            // Define the request parameters
//            String requestBody = "{\"model\": \"gpt-3.5-turbo\", \"messages\": [{\"role\": \"user\", \"content\": \"Add visza az összes filmet amikben benne van ez a zene szám: 'Revolt' - Muse , a választ add vissza ebben a formában : [{'name': 'movieName'}]\"}]}";
//
//
//            // Get the output stream of the connection and write the parameters to it
//            OutputStream outputStream = connection.getOutputStream();
//            outputStream.write(requestBody.getBytes("UTF-8"));
//            outputStream.flush();
//            outputStream.close();
//
//            // Read the response from the server
//            BufferedReader in = new BufferedReader(new InputStreamReader(connection.getInputStream()));
//            String line;
//            StringBuffer response = new StringBuffer();
//            while ((line = in.readLine()) != null) {
//                response.append(line);
//            }
//            in.close();
//
//            // Print the response
//            System.out.println(response.toString());
//        } catch (Exception e) {
//            e.printStackTrace();
//        }

        return null;
    }
}
