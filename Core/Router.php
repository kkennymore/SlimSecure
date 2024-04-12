<?php

namespace SlimSecure\Core;

use SlimSecure\Configs\Env;
use SlimSecure\Core\Responses;

/**
 * Slimez Router Class
 *
 * Developer: Hitek Financials Ltd
 * Year: 2023
 * Developer Contact: contact@tekfinancials.ng, kennethusiobaifo@yahoo.com
 * Project Name: Slimez
 * Description: Slimez.
 */

class Router
{

    /**
     * The router array stores the registered routes.
     * 
     * @var array
     */
    protected static $router = [];

    /**
     * The paths array stores the path patterns for the registered routes.
     * 
     * @var array
     */
    protected static $paths = [];

    /**
     * The params array stores the parameters extracted from the route path.
     * 
     * @var array
     */
    protected static $params = [];

    /**
     * The handler variable stores the callback function or handler for the current route.
     * 
     * @var mixed|null
     */
    protected static $handler = null;

    /**
     * Constructor for the class.
     * 
     * This constructor initializes an instance of the class.
     */
    public function __construct()
    {
    }

    /**
     * Adds a route for handling HTTP POST requests.
     *
     * @param string $path The path for the route.
     * @param mixed $handler The handler for the route.
     */
    public static function post(string $path, mixed $handler)
    {
        self::addRoute(method: "POST", path: $path, handler: $handler);
    }

    /**
     * Adds a route for handling HTTP GET requests.
     *
     * @param string $path The path for the route.
     * @param mixed $handler The handler for the route.
     */
    public static function get(string $path, mixed $handler)
    {
        self::addRoute(method: "GET", path: $path, handler: $handler);
    }

    /**
     * Adds a route for handling HTTP PUT requests.
     *
     * @param string $path The path for the route.
     * @param mixed $handler The handler for the route.
     */
    public static function put(string $path, mixed $handler)
    {
        self::addRoute(method: "PUT", path: $path, handler: $handler);
    }

    /**
     * Adds a route for handling HTTP PATCH requests.
     *
     * @param string $path The path for the route.
     * @param mixed $handler The handler for the route.
     */
    public static function patch(string $path, mixed $handler)
    {
        self::addRoute(method: "PATCH", path: $path, handler: $handler);
    }

    /**
     * Adds a route for handling HTTP OPTIONS requests.
     *
     * @param string $path The path for the route.
     * @param mixed $handler The handler for the route.
     */
    public static function options(string $path, mixed $handler)
    {
        self::addRoute(method: "OPTIONS", path: $path, handler: $handler);
    }

    /**
     * Adds a route for handling HTTP DELETE requests.
     *
     * @param string $path The path for the route.
     * @param mixed $handler The handler for the route.
     */
    public static function delete(string $path, mixed $handler)
    {
        self::addRoute(method: "DELETE", path: $path, handler: $handler);
    }

    /**
     * Adds a route to the router array with the specified method, path, and handler.
     *
     * @param string $method The HTTP method for the route.
     * @param string $path The path for the route.
     * @param mixed $handler The handler for the route.
     */
    private static function addRoute(string $method, string $path, mixed $handler)
    {
        self::$router[] = [
            "method" => $method,
            "path" => trim($path, "/"),
            "handler" => $handler
        ];
    }

    private static function matchRoute($route)
    {

        $params = array();
        $path = '';

        preg_match_all('/\/\{([^{}]+)\}/', $route, $matches);

        foreach ($matches[1] as $param) {
            $params[] = $param;
        }

        $path = substr($route, 0, strpos($route, '{'));

        return [
            "path" => empty($path) ? $route : $path,
            "params" => $params
        ];
    }

    private static function matchUrl($url, $routUrl)
    {
        @list($controller, $action) = explode("/", $routUrl);
        $pattern = '/\/(' . $controller . '\/' . $action . ')(?=\/|$)/';
        if (preg_match($pattern, $url, $matches)) {
            $splited = explode($matches[1], $url);
            $params = explode("/", $splited[1]);
            $param = [];
            foreach ($params as $p) {
                if (!empty($p)) {
                    $param[] = $p;
                }
            }
            return [
                "path" => $matches[1],
                "params" => $param,
            ];
        } else {
            return [];
        }
    }

    private static function combineArrays($array1, $array2)
    {
        $count1 = count($array1);
        $count2 = count($array2);

        // Calculate the difference in lengths between the two arrays
        $lengthDiff = ($count1 - $count2);

        // Reduce the length of the second array from the end to make it equal to the first array
        if ($lengthDiff != 0) {
            if ($count1 > $count2) {
                $array1 = array_slice($array1, 0, ($count1 - $lengthDiff));
            }
            if ($count2 > $count1) {
                $array2 = array_slice($array2, 0, ($count2 - ltrim($lengthDiff, "-")));
            }
        }
        // Combine the two arrays
        $result = array_combine($array1, $array2);
        return $result;
    }

    /**
     * Dispatches the current HTTP request to the appropriate route handler.
     * 
     * This method extracts the request method and URL, matches them against registered routes,
     * and executes the corresponding callback or controller action.
     */
    public static function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];
        $cleanSanity = self::getCleanUrl($url);
        $cleanUrl = parse_url($cleanSanity);
        $params = array();
        // Loop over all the routes
        foreach (self::$router as $router) {
            /**get the params and path from the url */
            $urlPathAndParams = self::matchUrl($cleanSanity, $router['path']);
            /**check if the url path is not empty */
            if (!empty($urlPathAndParams)) {
                /**get the params and path from the url */
                $routePathAndParam = self::matchRoute($router["path"]);
                /**gcheck if the path from the url is same as the part in the route file */
                if (strtolower($urlPathAndParams['path']) == strtolower(trim($routePathAndParam["path"], "/"))) {
                    /**get the route path */
                    $router["path"] = trim($routePathAndParam["path"], "/");
                    /**check if the route params if added */
                    if (!empty($routePathAndParam["params"])) {
                        /**combine params, make one array the key and the other value */
                        $params = self::combineArrays($routePathAndParam["params"], $urlPathAndParams["params"]);
                    }
                    // Check if the URL path matches the route path
                    if (strtolower($router["path"]) == strtolower($urlPathAndParams["path"])) {
                        self::escapeArray($router);
                        self::validateArray($router);
                        self::sanitizeArray($router);
                        self::executeCallback($router, $params, self::$handler, $requestMethod);
                    }
                }
                return;
            }
            // Add the paths to the array
            self::$paths[] = $router['path'];
        }

        // Check if the page is in the array of paths
        if (!in_array(trim($cleanUrl["path"], "/"), self::$paths)) {
            self::handleNotFound([
                "status" => Env::NOT_FOUND_METHOD,
                "message" => '404 - Page not found',
            ], Env::NOT_FOUND_METHOD);
            return;
        }
    }

    /**
     * Handles the "not found" scenario by sending an appropriate response.
     * 
     * @param string|array $message The error message or an array of error messages.
     * @param int $code The HTTP status code.
     */
    private static function handleNotFound(string | array $message, int $code)
    {

        if (is_array($message)) {
            echo Responses::json([$message], $code);
           
        } else {
            echo $message;
            http_response_code($code);
        }
    }

    /**
     * Executes the callback or controller action for the matched route.
     * 
     * @param mixed $router The route information.
     * @param array $params The parameters extracted from the route path.
     * @param mixed $handler The handler for the route.
     * @param string $requestMethod The request method.
     */
    private static function executeCallback($router, $params, $handler, $requestMethod)
    {
        // Check if the request method matches the route request method
        if ($router['method'] != $requestMethod) {
            self::handleNotFound([
                "status" => Env::METHOD_NOT_ALLOWED,
                "message" => "Wrong request method: Only " . $router['method'] . " method is supported for this route",
            ], Env::METHOD_NOT_ALLOWED);
            return;
        }

        // Check if the handler is available and set
        if (!isset($router['handler'])) {
            self::handleNotFound([
                "status" => Env::SERVER_ERROR_METHOD,
                "message" => "Route handler not available or not properly written. Please check your route file",
            ], Env::SERVER_ERROR_METHOD);
            return;
        }

        // Assign the handler value to the global variable
        $handler = $router['handler'];

        // Process this block of code if the route is using a callback function
        if (is_callable($handler)) {
            call_user_func_array($handler, [$params]);
            return;
        }

        // Run this block of code if the route handler is of the form Controller@action
        if (is_array($handler) && strpos(implode($handler), '@') !== false || is_string($handler) && strpos($handler, '@') !== false) {
            // Explode the string
            list($className, $methodName) = is_array($handler) ? explode('@', implode($handler)) : explode('@', $handler);
            $className = '\\SlimSecure\\App\\Controllers\\' . $className;
            $controller = new $className();
            call_user_func_array([$controller, $methodName], [$params]);
            return;
        }

        // Get the class name from the handler using the namespace
        $className = '\\' . $handler[0];
        $methodName =  $handler[1];

        // Check if the class exists
        if (!class_exists($className)) {
            // Check the string version
            $className = '\\SlimSecure\\App\\Controllers' . $className;
            // Check if the class exists
            if (!class_exists($className)) {
                self::handleNotFound([
                    "status" => Env::SERVER_ERROR_METHOD,
                    "message" => "Class " . $className . "' does not does not exist",
                ], Env::SERVER_ERROR_METHOD);
                return;
            }
        }

        // Get the controller class namespace path and create its object
        $controller = new $className;

        // Check if the invoked method exists
        if (!method_exists($controller, $methodName)) {
            self::handleNotFound([
                "status" => Env::SERVER_ERROR_METHOD,
                "message" => "Method " . $methodName . " does not does not exist in the class " . $className,
            ], Env::SERVER_ERROR_METHOD);
            return;
        }

        // All went well, now display the content of the controller method
        call_user_func_array([$controller, $methodName], [$params]);
    }

    /**
     * Cleans the URL by removing any query parameters.
     * 
     * @param string $url The URL to clean.
     * @return string The cleaned URL.
     */
    private static function getCleanUrl($url)
    {
        $urlParts = explode('?', $url);
        $cleanUrl = rtrim($urlParts[0], '/');
        return $cleanUrl;
    }

    /**
     * Sanitizes the values in an array by applying HTML special characters encoding.
     * 
     * @param array $array The array to sanitize.
     */
    private static function sanitizeArray(&$array)
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                self::sanitizeArray($value);
            } else {
                $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }
        }
    }

    /**
     * Validates the values in an array by applying default filters.
     * 
     * @param array $array The array to validate.
     * @return array The validated array.
     */
    private static function validateArray($array)
    {
        $validatedArray = [];

        foreach ($array as $key => $value) {
            $validatedValue = filter_var($value, FILTER_DEFAULT);

            if ($validatedValue !== false) {
                $validatedArray[$key] = $validatedValue;
            }
        }

        return $validatedArray;
    }

    /**
     * Escapes the values in an array by applying HTML entity encoding.
     * 
     * @param array $array The array to escape.
     */
    private static function escapeArray(&$array)
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                self::escapeArray($value);
            } else {
                $value = htmlentities($value, ENT_QUOTES, 'UTF-8');
            }
        }
    }
}
