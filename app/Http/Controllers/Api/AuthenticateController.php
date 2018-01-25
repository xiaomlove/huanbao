<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;


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
        $data = [
            'grant_type' => 'password',
            'client_id' => $request->get('client_id', config('oauth.client_id')),
            'client_secret' => $request->get('client_secret', config('oauth.client_secret')),
            'username' => $request->username,
            'password' => $request->password,
            'scope' => '',
        ];
        $client = new Client();
        try
        {
            $url = urlBeforePath() . "/oauth/token";
            $response = $client->post($url, ['form_params' => $data]);
            $result = json_decode((string)$response->getBody(), true);
            return normalize(0, "OK", $result);
        }
        catch (\Exception $e)
        {
            $result = json_decode((string)$e->getResponse()->getBody(), true);
            return normalize($result['message'], $request->all());
        }

    }
}
