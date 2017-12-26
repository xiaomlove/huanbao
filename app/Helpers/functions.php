<?php
/**
 * 自自定义函数
 */

function normalize(...$args)
{
    if (!isset($args[2]))
    {
        //参数少于3个时，默认为错误状态。
        $ret = -1;
        $msg = isset($args[0]) ? $args[0] : 'ERROR';
        $data = isset($args[1]) ? $args[1] : [];
    }
    else
    {
        $ret = $args[0];
        $msg = $args[1];
        $data = $args[2];
    }

    return [
        'ret' => (int)$ret,
        'msg' => (string)$msg,
        'data' => $data,
        'timeuse' => (float)number_format(microtime(true) - LARAVEL_START, 3),
    ];
}

function apiUser()
{
    static $user;
    if (is_null($user))
    {
        $user = \JWTAuth::parseToken()->authenticate();
    }
    return $user;
}

function originalJsonEncode($data)
{
    return json_encode($data, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_FORCE_OBJECT);
}