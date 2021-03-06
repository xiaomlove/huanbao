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
 * 根据key返回链接
 * @param $key
 * @param null $width
 * @param null $height
 * @return string
 */
function attachmentUrl($key, $width = null, $height = null)
{
    if (empty($key))
    {
        return '';
    }
    static $disk;
    if (!$disk)
    {
        $disk = \Storage::disk('qiniu');
    }
    if ($width || $height)
    {
        $previewOptions = "imageView2/0";
        if ($width)
        {
            $previewOptions .= "/w/$width";
        }
        if ($height)
        {
            $previewOptions .= "/h/$height";
        }
        return (string)$disk->imagePreviewUrl($key, $previewOptions);
    }
    return (string)$disk->url($key);
}


