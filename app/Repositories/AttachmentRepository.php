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
    public function create(array $uploadedFile, $uid = 0)
    {
        $created = [];
        foreach ($uploadedFile as $file)
        {
            if (!($file instanceof UploadedFile))
            {
                return normalize(sprintf("附件数据不是  %s 的实例", UploadedFile::class));
            }
            $mimeType = $file->getClientMimeType();
            $savePath = sprintf("%s/%s", substr($mimeType, 0, strpos($mimeType, '/')), date('Ym'));
            $saveResult = \Storage::putFile($savePath, $file);
            if (!$saveResult)
            {
                return normalize(sprintf('%s 保存到  %s 失败', $file->getLinkTarget(), $savePath));
            }
//             $absPath = storage_path("app/public/$saveResult");
//             if (file_exists($absPath))
//             {
//                 \Log::info(sprintf('%s, file: %s exists, will orientate().',__METHOD__, $absPath));
//                 \Image::make($absPath)->orientate()->save($absPath);
//             }
//             else
//             {
//                 \Log::info(sprintf('%s, file: %s NOT exists!',__METHOD__, $absPath));
//             }
            
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
    
    /**
     * 处理前端传过来的附件（图片）
     * 
     * @param array $data
     */
    public function getFromRequestData(array $data)
    {
        $attachments = [];
        //对于新上传的，规定name为image
        if (!empty($data['image']))
        {
            $images = is_array($data['image']) ? $data['image'] : [$data['image']];
            if (is_object($images[0]))
            {
                $imageResult = $this->create($images, $data['uid']);
                if ($imageResult['ret'] != 0)
                {
                    return $imageResult;
                }
                $attachments = array_merge($attachments, $imageResult['data']);
            }
        }
        //对于已存在的，规定name为 attachment_id
        if (!empty($data['attachment_id']))
        {
            //图片已经传好并获取ID
            $attachmentIdArr = is_array($data['attachment_id']) ? $data['attachment_id'] : explode(',', $data['attachment_id']);
            $rows = $this->attachment->find($attachmentIdArr);
            if (empty($rows))
            {
                return normalize("invalid attachment_id: " . implode(',', $attachmentIdArr), $data);
            }
            $attachments = array_merge($attachments, $rows->all());
        }
        return normalize(0, "OK", $attachments);
    }
}