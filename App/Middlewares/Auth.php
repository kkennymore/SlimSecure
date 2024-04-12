<?php

namespace SlimSecure\App\Middlewares;

/**
 * Auth Middleware Class
 * 
 * This class provides methods to handle authentication tasks, such as retrieving and validating
 * authorization headers and tokens from HTTP requests. It is typically used in middleware to secure
 * routes that require user authentication.
 */
class Auth
{
    /**
     * @var mixed The authentication payload, typically storing user credentials or token details.
     */
    protected static $authPayload;

    /**
     * Retrieves the Authorization header from the current request.
     *
     * This method attempts to obtain the Authorization header using several approaches to cover
     * different server environments. It supports both Apache and Nginx servers, including environments
     * where headers are not directly accessible in $_SERVER due to fast CGI configurations.
     *
     * @return string|null Returns the content of the Authorization header if present, null otherwise.
     */
    public static function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { // Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Format the headers to be consistent with the $_SERVER superglobal
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    /**
     * Extracts the bearer token from the Authorization header.
     *
     * Parses the Authorization header to extract the bearer token, typically used for bearer authentication.
     * The bearer token is part of the header following the 'Bearer' keyword.
     *
     * @return string Returns the bearer token if found, an empty string otherwise.
     */
    public static function getBearerToken()
    {
        $headers = self::getAuthorizationHeader();
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return "";
    }
}
