package com.prismasolutions.LMSBackend.service;

import org.springframework.stereotype.Service;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URI;
import java.net.URL;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
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

       // try {
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

//            URL url = new URL("https://api.openai.com/v1/chat/completions");
//
//            String requestBody = "{\"model\": \"gpt-3.5-turbo\", \"messages\": [{\"role\": \"user\", \"content\": \"Add visza az összes filmet amikben benne van ez a zene szám: 'Revolt' - Muse , a választ add vissza ebben a formában : [{'name': 'movieName'}]\"}]}";
//
//            HttpRequest request = HttpRequest.newBuilder()
//                    .uri(URI.create("https://api.openai.com/v1/chat/completions"))
//                    .header("Content-Type", "application/json")
//                    .header("Bearer","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")
//                    .POST(HttpRequest.BodyPublishers.ofString(requestBody))
//                    .build();
//
//
//            HttpResponse<String> response = httpClient.send(request, HttpResponse.BodyHandlers.ofString());
//
//
//        } catch (Exception e) {
//            e.printStackTrace();
//        }

        String serviceUrl = "https://api.openai.com/v1/chat/completions";
        String bearerToken = "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";
        String requestBody = "{\"model\": \"gpt-3.5-turbo\", \"messages\": [{\"role\": \"user\", \"content\": \"Add visza az összes filmet amikben benne van ez a zene szám: 'Revolt' - Muse , a választ add vissza ebben a formában : [{'name': 'movieName'}]\"}]}";

        HttpClient httpClient = HttpClient.newHttpClient();
        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(serviceUrl))
                .header("Content-Type", "application/json")
                .header("Authorization", "Bearer " + bearerToken)
                .POST(HttpRequest.BodyPublishers.ofString(requestBody))
                .build();

        try {
            HttpResponse<String> response = httpClient.send(request, HttpResponse.BodyHandlers.ofString());
            System.out.println(response.body());
        } catch (Exception e) {
            e.printStackTrace();
        }

        return null;
    }
}
