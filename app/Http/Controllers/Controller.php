<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    /**
     * 使用 jwt 认证的用户
     * @var array
     */
    private $apiUser;
    
    /**
     * 获取当前 jwt api 认证的用户
     * @return array
     */
    public function apiUser()
    {
        if (is_null($this->apiUser))
        {
            $this->apiUser = \JWTAuth::parseToken()->authenticate();
        }
        return $this->apiUser;
    }
}
