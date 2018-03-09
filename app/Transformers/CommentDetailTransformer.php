<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\CommentDetail;

class CommentDetailTransformer extends TransformerAbstract
{
    private static $originalContent = null;

    protected $defaultIncludes = [];

    protected $availableIncludes = ['attachments'];

    public function __construct()
    {
        if (self::$originalContent === null)
        {
            if (preg_match('/TopicController/', \Route::current()->getActionName()))
            {
                self::$originalContent = false;
            }
            else
            {
                self::$originalContent = true;
            }
        }
    }

    public function transform(CommentDetail $commentDetail)
    {
        if (self::$originalContent)
        {
            $content = json_decode($commentDetail->content, true);
        }
        else
        {
            $content = str_limit($this->getCommentText($commentDetail), 100);
        }
        return [
            'id' => $commentDetail->id,
            'cid' => $commentDetail->cid,
            'content' => $content,
        ];
    }

    public function includeAttachments(CommentDetail $commentDetail)
    {
        $attachments = $commentDetail->attachments;
        if ($attachments->isNotEmpty())
        {
            return $this->collection($attachments, new AttachmentTransformer());
        }
    }

    private function getCommentText(CommentDetail $commentDetail)
    {
        $contents = json_decode($commentDetail->content, true);
        $texts = [];
        foreach ($contents as $content)
        {
            if ($content['type'] == CommentDetail::CONTENT_TYPE_TEXT)
            {
                $texts[] = $content['data']['text'];
            }
        }
        return implode('', $texts);
    }
}

