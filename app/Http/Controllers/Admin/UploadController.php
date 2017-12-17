<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\AttachmentRepository;

class UploadController extends Controller
{
    protected $attachment;
    
    public function __construct(AttachmentRepository $attachment)
    {
        $this->attachment = $attachment;
    }
    
    public function image(Request $request)
    {
        $image = $request->file('image');
        $params = $request->all();
        if (empty($image))
        {
            return normalize('没有名为image的文件上传', $params);
        }
        if (is_array($image))
        {
            return normalize('不支持多张', $params);
        }
        $allowExtensions = ['jpeg', 'jpg', 'png', 'gif'];
        $ext = $image->getClientOriginalExtension();
        if (empty($ext) || !in_array($ext, $allowExtensions))
        {
            return normalize("$ext 文件不在允许列表中", $params);
        }
        $size = $image->getClientSize();
        $maxSize = 5 * 1024 * 1024;
        if ($size > $maxSize)
        {
            return normalize("文件大小 {$size} 超过允许的范围 {$maxSize}", $params);
        }
        
        $result = $this->attachment->create([$image], \Auth::user()->id);
        if ($result['ret'] != 0)
        {
            return $result;
        }
        $object = $result['data'][0];
        return normalize(0, 'OK', [
            'id' => $object->id,
            'uri' => asset(sprintf('storage/%s/%s', $object->dirname, $object->basename)),
        ]);
    }
}
