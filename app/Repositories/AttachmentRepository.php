<?php

namespace App\Repositories;

use Illuminate\Http\UploadedFile;
use App\Models\Attachment;

class AttachmentRepository
{
    protected $attachment;
    
    public function __construct(Attachment $attachment)
    {
        $this->attachment = $attachment;
    }
    
    /**
     * 保存附件。先依据类型，再按月份存储
     * 
     * @param array|Illuminate\Http\UploadedFile $uploadedFile
     * @param integer $uid
     * @return array
     */
    public function create($uploadedFile, $uid = 0)
    {
        $uploadedFile = (array)$uploadedFile;
        $created = [];
        foreach ($uploadedFile as $file)
        {
            if (!($file instanceof UploadedFile))
            {
                return normalize(sprintf("附件数据不是  %s 的实例", UploadedFile::class));
            }
            \Log::info(sprintf("%s, file: %s", __METHOD__, $file));
//             dd($file);
            $mimeType = $file->getClientMimeType();
            $savePath = sprintf("%s/%s", substr($mimeType, 0, strpos($mimeType, '/')), date('Ym'));
            $saveResult = \Storage::putFile($savePath, $file);
            if (!$saveResult)
            {
                return normalize(sprintf('%s 保存到  %s 失败', $file->getLinkTarget(), $savePath));
            }
            $pathInfo = pathinfo($saveResult);
            $data = [
                'uid' => $uid,
                'mime_type' => $mimeType,
                'size' => $file->getClientSize(),
                'basename' => $pathInfo['basename'],
                'dirname' => $pathInfo['dirname'],
            ];
            $created[] = $this->attachment->create($data);
        }
        return normalize(0, "OK", $created);
    }
}