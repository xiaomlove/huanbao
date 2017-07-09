<?php
/**
 * 自自定义函数
 */

function normalize($ret = 0, $msg = '', array $data = [])
{
    return [
        'ret' => (int)$ret,
        'msg' => (string)$msg,
        'data' => (array)$data,
        'timeuse' => (float)number_format(microtime(true) - LARAVEL_START, 3),
    ];
}