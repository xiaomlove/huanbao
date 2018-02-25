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

function imageFormGroup($label, $name, $value, \Illuminate\Support\ViewErrorBag $errors)
{
    $errorClassName = $errors->has($name) ? 'has-error' : '';
    $urlDomain = config('filesystems.disks.qiniu.domains.default');
    if ($errorClassName)
    {
        $errorHtml = '<small class="help-block">' . $errors->first($name) . '</small>';
    }
    else
    {
        $errorHtml = '';
    }
    $html =
<<<EOF
    <div class="form-group {$errorClassName}">
        <label for="" class="col-sm-2 control-label">{$label}</label>
        <div class="col-sm-8">
            <input type="text" name="{$name}" class="form-control" placeholder="图片地址，确保域名为 {$urlDomain} 且能正常打开，或点击右边上传" value="{$value}">
            {$errorHtml}
        </div>
        <div class="col-sm-2">
            <input type="file" class="upload">
            <a class="preview" href="{$value}" target="_blank"><img src="{$value}" /></a>
        </div>
    </div>
EOF;
    return $html;
}

