<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\CommentDetail;

class CommentDetailTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];

    protected $availableIncludes = ['attachments'];
    
    public function transform(CommentDetail $commentDetail)
    {
        return [
            'id' => $commentDetail->id,
            'cid' => $commentDetail->cid,
            'content' => str_limit($this->getCommentText($commentDetail), 100),
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

