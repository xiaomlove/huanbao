<?php
/**
 * 自自定义函数
 */

function normalize($ret = 0, $msg = '', array $data = [])
{
    $args = func_get_args();
	if (is_array($ret))
    {
        reset($ret);
        $ret = current($ret) ? current($ret) : 1;
        $msg = next($ret);       
        $data = next($ret);
    }
    elseif (func_num_args() < 3 && is_string($args[0]))
    {
        $ret = 1;
        $msg = $args[0];
        $data = isset($args[1]) ? $args[1] : [];
    }
    return [
        'ret' => (int)$ret,
        'msg' => (string)$msg,
        'data' => (array)$data,
        'timeuse' => (float)number_format(microtime(true) - LARAVEL_START, 3),
    ];
}