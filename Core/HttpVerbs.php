<?php

namespace SlimSecure\Core;

/**
 * Author: Slimez
 * Developer: Hitek Financials Ltd
 * Year: 2024
 * Developer Contact: contact@tekfinancials.ng, kennethusiobaifo@yahoo.com
 * Project Name: Slimez
 * Description: Slimez.
 */

/**
 * Class HttpVerbs
 *
 * This class provides methods for handling HTTP requests using different HTTP verbs.
 */
class HttpVerbs
{
    /**
     * Process a GET request.
     *
     * @param string $endpointQueryStringParams The query string parameters for the endpoint.
     * @return mixed The response data.
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
     * Process an OPTIONS request.
     *
     * @param array|object|string $optionsParams The parameters for the request.
     * @param string $endPoint The endpoint for the request.
     * @param string $contentType The content type for the request. Default is 'json'.
     * @return mixed The response data.
     */
    public function optionsVerb(array | string $optionsParams, string $endPoint = '', string $bearerToken = '', array $headers = [])
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
        //set the url, number of POST vars, POST data
        curl_setopt(
            $chandle,
            CURLOPT_URL,
            trim($endPoint)
        );
        curl_setopt($chandle, CURLOPT_POST, true);
        curl_setopt($chandle, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($chandle, CURLOPT_HTTPHEADER, $header);
        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($chandle, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($chandle);
        curl_close($chandle);
        // Return the data
        return json_decode($result, true);
    }

    /**
     * Process a DELETE request.
     *
     * @param string $endpointQueryStringParams The query string parameters for the endpoint.
     * @return mixed The response data.
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
     * Process a PUT request.
     *
     * @param array $putParams The parameters for the request.
     * @param string $endPoint The endpoint for the request.
     * @return mixed The response data.
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
        $fields_string = is_array($putParams) ? http_build_query($putParams) : $putParams;
        //open connection
        $chandle = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($chandle, CURLOPT_URL, trim($endPoint));
        curl_setopt($chandle, CURLOPT_PUT, true);
        curl_setopt($chandle, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($chandle, CURLOPT_HTTPHEADER, $header);
        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($chandle, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($chandle);
        curl_close($chandle);
        // Return the data
        return json_decode($result, true);
    }

    /**
     * Process a POST request.
     *
     * @param array $postParams The parameters for the request.
     * @param string $endPoint The endpoint for the request.
     * @return mixed The response data.
     */
    public function postVerb(array | string $postParams, string $endPoint = '', string $bearerToken = '',  array $headers = [])
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

        //$//httpCode = curl_getinfo($curlHandler, CURLINFO_HTTP_CODE);

        if (curl_errno($curlHandler)) {
            echo 'Curl error: ' . curl_error($curlHandler);
        }

        curl_close($curlHandler);

        return json_decode($result, true);
    }
}
