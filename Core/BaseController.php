<?php

namespace SlimSecure\Core;

/**
 * Author: hITEK Financials Ltd
 * Developer: Hitek Financials Ltd
 * Year: 2024
 * Developer Contact: contact@tekfinancials.ng, kennethusiobaifo@yahoo.com
 * Project Name: Slimez
 * Description: Slimez.
 */


/**
 * Class BaseController
 *
 * This class serves as the base Controller for other Controller classes. 
 */
class BaseController extends HttpVerbs
 {
     protected $request;
 
     public function __construct(){
         $this->request = new HttpVerbs();
     }
     /**
      * Before filter - called before an action method.
      *
      * @return void
      */
     protected function before()
     {
     }
 
     /**
      * After filter - called after an action method.
      *
      * @return void
      */
     protected function after()
     {
     }
 }