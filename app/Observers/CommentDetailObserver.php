<?php

namespace App\Observers;

use App\Models\CommentDetail;
use App\Models\AttachmentRelationship;

class CommentDetailObserver
{
    public function created(CommentDetail $commentDetail)
    {
        $this->updateAttachmentRelationships($commentDetail, __FUNCTION__);
    }

    public function updated(CommentDetail $commentDetail)
    {
        $this->updateAttachmentRelationships($commentDetail, __FUNCTION__);
    }

    private function updateAttachmentRelationships(CommentDetail $commentDetail, $action = "")
    {
        if (!$commentDetail->content)
        {
            return;
        }
        $contents = json_decode($commentDetail->content, true);
        foreach ($contents as $content)
        {
            if ($content['type'] == CommentDetail::CONTENT_TYPE_IMAGE && !empty($content['data']['attachment_key']))
            {
                $key = $content['data']['attachment_key'];
                $isExists = $commentDetail->attachments()->wherePivot("attachment_key", $key)->count();
                \Log::info(sprintf("%s, action: %s, key: %s, isExists: %s", __METHOD__, $action, $key, $isExists));
                if (!$isExists)
                {
                    $commentDetail->attachments()->attach($key);
                }
            }
        }
    }
}