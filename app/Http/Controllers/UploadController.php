<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attachment;

class UploadController extends Controller
{
    protected $imageAllowExtensions = ['jpg', 'jpeg', 'gif', 'png'];

    protected $imageMaxSize = 5 * 1024 *1024;

    protected $imageFieldName = 'image';

    /**
     * 图片上传
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function image(Request $request)
    {
        $image = $request->file($this->imageFieldName);
        if (empty($image))
        {
            return response()->json(normalize("没有上传name为{$this->imageFieldName}的图片"));
        }
        if (is_array($image))
        {
            return response()->json(normalize("不支持多张"));
        }
        $ext = $image->getClientOriginalExtension();
        if (empty($ext) || !in_array(strtolower($ext), $this->imageAllowExtensions))
        {
            return response()->json(normalize("图片格式{$ext}不被支持"));
        }
        $size = $image->getClientSize();
        if ($size > $this->imageMaxSize)
        {
            return response()->json(normalize("图片大小{$size}过大"));
        }
        //统一使用qiniu
        $keyPrefix = $request->get('prefix', "");
        $disk = \Storage::disk('qiniu');
        $result = $disk->put($keyPrefix, $image);
        if (!$result)
        {
            return response()->json(normalize("保存失败(keyPrefix: $keyPrefix)"));
        }
        //保存到附件
        Attachment::create([
            'uid' => \Auth::id(),
            'mime_type' => $image->getClientMimeType(),
            'key' => $result,
            'size' => $size,
        ]);
        return response()->json(normalize(0, 'OK', [
            'url' => $disk->url($result),
            'key' => $result,
            'size' => $size,
            'ext' => $ext,
        ]));
    }

    public function token()
    {
        $returnBody = '{"key": $(key), "hash": $(etag), "fsize": $(fsize), "fname": $(fname), "imageInfo": $(imageInfo), "exif": $(exif)}';
        $token = \Storage::disk('qiniu')->uploadToken(null, 1800, ['returnBody' => $returnBody]);
        return normalize(0, "OK", ['token' => $token]);
    }
}
