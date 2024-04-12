<?php

/**
 * Author: Oaad Global
 * Developer: Hitek Financials Ltd
 * Year: 2024
 * Developer Contact: contact@tekfinancials.ng, kennethusiobaifo@yahoo.com
 * Project Name: Slimez
 * Description: Slimez.
 */

namespace SlimSecure\App\Middlewares;
use SlimSecure\Configs\Env;
/**
 * Class Auth
 * Middleware class for authentication.
 */
class Auth
{
    /**
     * @var mixed The authentication payload.
     */
    protected static $authPayload;

    
    /**
     * Get the Authorization header.
     *
     * @return string|null The Authorization header value or null if not found.
     */
    public static function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    /**
     * Get the access token from the header.
     *
     * @return string The access token or an empty string if not found.
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
