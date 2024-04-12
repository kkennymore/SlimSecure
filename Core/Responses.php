<?php

namespace SlimSecure\Core;

/**
 * Class Responses
 * 
 * Handles the generation of JSON responses for the application. This class is responsible for
 * serializing response data into JSON format and setting appropriate HTTP response codes.
 * Inherits functionalities from the SerializeJson class for efficient JSON serialization.
 */
class Responses extends SerializeJson
{
    /**
     * Generate a JSON response.
     *
     * This method is used to encode the given response data into JSON format and set the HTTP
     * response code for the current request. The method leverages the SerializeJson class for
     * serialization, ensuring that any object passed to it is properly converted into JSON.
     * It also pretty-prints the JSON to enhance readability when the response is viewed directly.
     *
     * @param mixed $response The data to be serialized into JSON. This can be any type that
     *                        is compatible with json_encode(), including arrays and objects.
     * @param int|null $code Optional. The HTTP status code to be sent with the response. If not specified,
     *                       defaults to 200 (OK).
     * @return string The JSON-encoded string of the response data, formatted with JSON_PRETTY_PRINT
     *                to enhance readability.
     */
    public static function json(mixed $response, ?int $code = 200)
    {
        // Set the HTTP response code, defaulting to 200 if not provided
        if ($code !== null) {
            http_response_code($code);
        }
        
        // Encode the response using the SerializeJson class's serialization capabilities
        // JSON_PRETTY_PRINT makes the output more readable when directly viewed
        return json_encode(new SerializeJson($response), JSON_PRETTY_PRINT);
    }
}
