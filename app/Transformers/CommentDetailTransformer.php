<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\CommentDetail;

class CommentDetailTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];
    
    public function transform(CommentDetail $commentDetail)
    {
        return [
            'id' => $commentDetail->id,
            'key' => $commentDetail->id,
            'cid' => $commentDetail->cid,
            'content' => $commentDetail->content,
        ];
    }
}

