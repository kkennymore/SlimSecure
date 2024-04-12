<?php

namespace SlimSecure\Core;

/**
 * Author: Oaad Global
 * Developer: Hitek Financials Ltd
 * Year: 2024
 * Developer Contact: contact@tekfinancials.ng, kennethusiobaifo@yahoo.com
 * Project Name: Slimez
 * Description: Slimez.
 */

header('Content-Type: application/json');

/**
 * Class Responses
 * 
 * This class handles the generation of JSON responses.
 */
class Responses extends SerializeJson
{
    /**
     * Generate a JSON response.
     *
     * @param mixed $response The response data.
     * @param int $code The HTTP response code.
     * @return string The JSON-encoded response.
     */
    public static function json(mixed $response, ?int $code = 200)
    {
        $code != null ? http_response_code(trim($code)): '';
        return json_encode(new SerializeJson($response), JSON_PRETTY_PRINT);
    }
}
