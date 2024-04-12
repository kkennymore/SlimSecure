<?php

use SlimSecure\Core\Router;
use SlimSecure\App\Controllers\UserController;
/**
 * Author: Engineer Usiobaifo Kenneth
 * Developer: Hitek Financials Ltd
 * Year: 2024
 * Developer Contact: contact@tekfinancials.ng, kennethusiobaifo@yahoo.com
 * Project Name: Hitek Financials Ltd (SlimSecure Framework)
 * Description: SlimSecure.
 */


/**
 * Define routes and their corresponding controllers and methods.
 */

// Login route
Router::post("/user/login", 'UserController@login');
// Register route
Router::post("/user/register", [UserController::class, 'register']);
// get profile data with optional params, you can get the optional params in the controller profile method from the $params
// by $params['id']
Router::get("/user/profile/{id}/{name}/{city}/{email}",[UserController::class, "profile"]);
// logout route
Router::get("/user/logout", [UserController::class, 'logout']);