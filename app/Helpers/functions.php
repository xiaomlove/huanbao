<?php
/**
 * 自自定义函数
 */

/**
 * 格式化返回
 *
 * @param array ...$args
 * @return array
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

/**
 * 返回链接中附件的key
 *
 * @param $url
 * @return string
 */
function attachmentKey($url)
{
    if (!filter_var($url, FILTER_VALIDATE_URL))
    {
        throw new \InvalidArgumentException("url '$url' 不是有效的URL");
    }
    $parsed = parse_url($url);
    return trim($parsed['path'], "/");
}

/**
 * 返回当前URL中不带path及queryString部分
 *
 * @return string
 */
function urlBeforePath()
{
    $request = request();
    $url = $request->url();
    $path = $request->path();
    if ($path == "" || $path == "/")
    {
        return trim($url, "/");
    }
    $result = substr($url, 0, strlen($url) - strlen($path));
    return trim($result, '/');
}
