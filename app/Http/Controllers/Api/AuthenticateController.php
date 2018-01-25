<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class AuthenticateController extends Controller
{
    /**
     * 登录，获取token
     * 
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->request->add([
            'grant_type' => 'password',
            'client_id' => $request->get('client_id', config('oauth.client_id')),
            'client_secret' => $request->get('client_secret', config('oauth.client_secret')),
            'username' => $request->username,
            'password' => $request->password,
            'scope' => '',
        ]);

        $proxy = \Request::create('oauth/token', 'POST');

        $response = \Route::dispatch($proxy);

        return $response;
    }
}
