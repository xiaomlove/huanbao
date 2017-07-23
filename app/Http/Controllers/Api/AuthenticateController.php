<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticateController extends Controller
{
    /**
     * 登录，获取token
     * 
     * @see https://github.com/tymondesigns/jwt-auth/wiki/Creating-Tokens
     * @param UserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');
    
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(normalize('invalid_credentials', $request->all()), 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(normalize('could_not_create_token', $request->all()), 500);
        }
    
        // all good so return the token
        return response()->json(normalize(0, 'OK', ['token' => $token]));
    }
}
