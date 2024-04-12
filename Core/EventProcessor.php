<?php

namespace SlimSecure\Core;


class EventProcessor extends HttpVerbs
{

    /*process the sending of the event data to the event box 
    * in json data as a post request
    */
    public function sendJsonEvent(string $endPoint = 'http://0.0.0.0:6103/webhook', array $eventData = [], array $headers = [])
    {

        $headers = [
            'Content-Type: application/json',
        ];


        $response = $this->postVerb(postParams: Responses::json($eventData,null), endPoint: $endPoint, headers: $headers);

        // $eventData = [
        //     'event_type' => 'wallet',
        //     'auth_token' => 'your_auth_token', // Replace with your actual auth token
        //     'data' => ['key' => 'value'],
        // ];
        return $response;
    }

    /*process the sending of the event data to the event box 
    * as a Binary data for rpc call data as a post request
    */
    public static function sendRpcCall(string $endpoint = 'http://localhost:5003/rpc', string $binaryData = 'path/to/binary/file.jpg', array $headers = [])
    {

        $header = [
            'Content-Type: application/octet-stream',
        ];
        
        $headerData = array_merge($header, $headers);

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($binaryData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode >= 200 && $httpCode < 300) {
            echo 'Request successful - HTTP Status Code: ' . $httpCode . PHP_EOL;
        } else {
            echo 'Request failed - HTTP Status Code: ' . $httpCode . PHP_EOL;
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);  // Set a timeout of 10 seconds
        }

        curl_close($ch);

        return $response;
    }
}
