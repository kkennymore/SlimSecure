<?php

namespace SlimSecure\Core;

/**
 * SlimSecure Project - BaseController Class
 * 
 * Provides a fundamental structure for building controller classes that handle
 * different aspects of the SlimSecure application. This class is part of the core
 * library of SlimSecure, developed by Hitek Financials Ltd.
 *
 * @author Engineer Usiobaifo Kenneth
 * @contact contact@tekfinancials.ng, kennethusiobaifo@yahoo.com
 * @year 2024
 * @package SlimSecure\Core
 */

/**
 * Class BaseController
 *
 * Serves as the base class for all controller classes within the SlimSecure application.
 * It includes setup and teardown lifecycle hooks (before and after methods) that
 * can be overridden in derived classes to perform actions before and after controller actions.
 */
class BaseController extends HttpVerbs
{
    /**
     * @var HttpVerbs $request Instance of HttpVerbs to handle HTTP requests.
     */
    protected $request;

    /**
     * Constructor for BaseController
     *
     * Initializes the BaseController by setting up the HttpVerbs instance which can be used
     * by the derived classes for handling HTTP requests.
     */
    public function __construct(){
        $this->request = new HttpVerbs();
    }

    /**
     * Lifecycle hook to run logic before a controller action.
     *
     * This method is intended to be overridden in derived classes where pre-action
     * setup or initialization is needed. By default, it performs no action.
     *
     * @return void
     */
    protected function before()
    {
    }

    /**
     * Lifecycle hook to run logic after a controller action.
     *
     * This method is intended to be overridden in derived classes where post-action
     * cleanup or finalization is needed. By default, it performs no action.
     *
     * @return void
     */
    protected function after()
    {
    }
}
