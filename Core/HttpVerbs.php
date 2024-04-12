<?php

namespace SlimSecure\Core;

/**
 * Class HttpVerbs
 *
 * This class encapsulates handling of various HTTP verbs (GET, POST, PUT, DELETE, OPTIONS) using PHP's cURL library.
 * It simplifies making HTTP requests by providing methods tailored for each HTTP action, supporting customization through headers and parameters.
 */
class HttpVerbs
{
    /**
     * Process a GET request to retrieve data from the specified endpoint.
     *
     * @param string $endPoint The URL where the GET request is sent.
     * @param string $bearerToken Authorization token, typically a bearer token.
     * @param array $headers Additional headers to send with the request.
     * @return array The response data decoded from JSON format.
     */
    public function getVerb(string $endPoint = '', string $bearerToken = '', array $headers = [])
    {
        $header = array_merge(
            [
                'Authorization: Bearer ' . trim($bearerToken),
            ],
            $headers
        );

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => trim($endPoint),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $header,
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

    /**
     * Process an OPTIONS request to retrieve the supported communication options for the specified endpoint.
     *
     * @param array|string $optionsParams Parameters to include in the OPTIONS request.
     * @param string $endPoint The URL where the OPTIONS request is sent.
     * @param string $bearerToken Authorization token for the request.
     * @param array $headers Additional headers to send with the request.
     * @return array The response data decoded from JSON format.
     */
    public function optionsVerb($optionsParams, string $endPoint = '', string $bearerToken = '', array $headers = [])
    {
        $header = array_merge(
            [
                'Authorization: Bearer ' . trim($bearerToken),
                'Accept: application/json',
                'Content-Type: application/json',
            ],
            $headers
        );
        $fields_string = is_array($optionsParams) ? http_build_query($optionsParams) : $optionsParams;

        $chandle = curl_init();
        curl_setopt($chandle, CURLOPT_URL, trim($endPoint));
        curl_setopt($chandle, CURLOPT_CUSTOMREQUEST, 'OPTIONS');
        curl_setopt($chandle, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($chandle, CURLOPT_HTTPHEADER, $header);
        curl_setopt($chandle, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($chandle);
        curl_close($chandle);
        return json_decode($result, true);
    }

    /**
     * Process a DELETE request to remove resources from the specified endpoint.
     *
     * @param string $endPoint The URL where the DELETE request is sent.
     * @param string $bearerToken Authorization token for the request.
     * @param array $headers Additional headers to send with the request.
     * @return array The response data decoded from JSON format.
     */
    public function deleteVerb(string $endPoint = '', string $bearerToken = '', array $headers = [])
    {
        $header = array_merge(
            [
                'Authorization: Bearer ' . trim($bearerToken),
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
            ],
            $headers
        );

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => trim($endPoint),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => $header,
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

    /**
     * Process a PUT request to update existing resources at the specified endpoint.
     *
     * @param array $putParams Parameters for the PUT request, typically an array of data to update.
     * @param string $endPoint The URL where the PUT request is sent.
     * @param string $bearerToken Authorization token for the request.
     * @param array $headers Additional headers to send with the request.
     * @return array The response data decoded from JSON format.
     */
    public function putVerb(array $putParams = [], string $endPoint = '', string $bearerToken = '', array $headers = [])
    {
        $header = array_merge(
            [
                'Authorization: Bearer ' . trim($bearerToken),
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
            ],
            $headers
        );
        $fields_string = http_build_query($putParams);
        $chandle = curl_init();
        curl_setopt($chandle, CURLOPT_URL, trim($endPoint));
        curl_setopt($chandle, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($chandle, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($chandle, CURLOPT_HTTPHEADER, $header);
        curl_setopt($chandle, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($chandle);
        curl_close($chandle);
        return json_decode($result, true);
    }

    /**
     * Process a POST request to create new resources at the specified endpoint.
     *
     * @param array|string $postParams Parameters for the POST request, either in array or string format.
     * @param string $endPoint The URL where the POST request is sent.
     * @param string $bearerToken Authorization token for the request.
     * @param array $headers Additional headers to send with the request.
     * @return array The response data decoded from JSON format.
     */
    public function postVerb($postParams, string $endPoint = '', string $bearerToken = '', array $headers = [])
    {
        $headersData = array_merge(
            [
                'Authorization: Bearer ' . trim($bearerToken),
                'Content-Type: application/json',
            ],
            $headers
        );
        
        $fields_string = is_array($postParams) ? http_build_query($postParams) : $postParams;

        $curlHandler = curl_init();
        curl_setopt_array($curlHandler, [
            CURLOPT_URL => trim($endPoint),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $fields_string,
            CURLOPT_HTTPHEADER => $headersData,
        ]);

        $result = curl_exec($curlHandler);
        curl_close($curlHandler);
        return json_decode($result, true);
    }
}
