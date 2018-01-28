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
     * @return array
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
        $url = url('/') . "/oauth/token";
        return $this->requestOAuthServer("post", $url, ['form_params' => $data]);
    }

    /**
     * 刷新token
     *
     * @param Request $request
     * @return array
     */
    public function refreshToken(Request $request)
    {
        $data = [
            'grant_type' => 'refresh_token',
            'client_id' => $request->get('client_id', config('oauth.client_id')),
            'client_secret' => $request->get('client_secret', config('oauth.client_secret')),
            'refresh_token' => $request->refresh_token,
            'scope' => '',
        ];
        $url = url('/') . "/oauth/token";
        //结果码-1001，刷新token失败，这里前端需要进行登录操作了。
        return $this->requestOAuthServer("post", $url, ['form_params' => $data], -1001);
    }

    /**
     * 退出，删除access_token
     *
     * @param Request $request
     * @return array
     */
    public function logout(Request $request)
    {
//        dd(\Auth::user());
        $result = \Auth::user()->tokens()->delete();
        if ($result)
        {
            return normalize(0, "OK", []);
        }
        else
        {
            return normalize("删除token失败");
        }
    }

    private function requestOAuthServer($method, $url, array $options = [], $failureCode = -1)
    {
        try
        {
            $client = new Client();
            $response = call_user_func_array([$client, $method], [$url, $options]);
            $result = json_decode((string)$response->getBody(), true);
            return normalize(0, "OK", $result);
        }
        catch (\Exception $e)
        {
            $result = json_decode((string)$e->getResponse()->getBody(), true);
            return normalize($failureCode, get_class($e) . ': ' . $result['message'], request()->all());
        }
    }
}
