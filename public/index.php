<?php

/**
 * Application entry script for the Slimez project.
 * 
 * This script initializes the application environment, sets up error handling, manages sessions,
 * configures headers for CORS and content type, and dispatches incoming requests to appropriate handlers.
 *
 * Author: Engineer Usiobaifo Kenneth
 * Developer: Hitek Financials Ltd
 * Year: 2024
 * Developer Contact: contact@tekfinancials.ng, kennethusiobaifo@yahoo.com
 * Project Name: Slimez
 * Description: Slimez.
 */

require "./../vendor/autoload.php";  // Load Composer dependencies
use SlimSecure\Configs\Env;        // Use the Environment configuration class

session_start();  // Start a new or resume an existing session

// Set content type to JSON for API responses
header("Content-Type: application/json");

// Set Cross-Origin Resource Sharing (CORS) headers
header('Access-Control-Allow-Origin: *');  // Allows all domains to access the API
header('Access-Control-Allow-Credentials: true');  // Allows cookies/authorization headers to be sent with requests
//header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT, PATCH');  // Specifies methods allowed when accessing the resource
header('Access-Control-Allow-Headers: Content-Type, *');  // Specifies headers that can be used during the actual request

// Set a cookie with the session ID, using configurations from the Env class
setCookie(
    Env::SYSTEM_NAME,                  // Cookie name set from system name
    session_id(),                      // Current session ID
    time() + (3600 * Env::COOKIE_EXPIRATION_TIME_IN_HOURS),  // Cookie expiration time
    '/',                               // Path where the cookie is available
    'usiobaifokenneth.com'       // Domain of the cookie
);

// Load application routing configurations
require "./../route/route.php";

/**
 * Error and Exception handling
 * Configure the application to handle errors and exceptions using custom handlers.
 */
error_reporting(E_ALL);  // Report all PHP errors
set_error_handler('\SlimSecure\Core\Exceptions::errorHandler');        // Custom error handler
set_exception_handler('\SlimSecure\Core\Exceptions::exceptionHandler');  // Custom exception handler

use \SlimSecure\Core\Router;  // Use the Router class

/**
 * Dispatch the HTTP request to the appropriate route handler as defined in the routing configuration.
 */
Router::dispatch();
