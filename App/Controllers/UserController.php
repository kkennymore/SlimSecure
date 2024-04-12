<?php

namespace SlimSecure\App\Controllers;

use SlimSecure\Core\BaseController;
use SlimSecure\Core\Responses;

/**
* UserController Class basic
*
* This controller extends
* the BaseController where you can provides methods specifically designed to manage UserController logics
* within the SlimSecure application.
*/
class UserController extends BaseController
{
    public function profile(?array $params){
        echo Responses::json($params);
    }
}
