<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Comment;

class CommentCommentTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['detail'];
    
    protected $availableIncludes = ['user'];
    
    public function transform(Comment $comment)
    {
        return [
            'id' => $comment->id,
            'key' => $comment->id,
            'tid' => $comment->tid,
            'created_at' => $comment->created_at->format('Y-m-d H:i'),
            'created_at_human' => $comment->created_at->diffForHumans(),
        ];
    }
    
    public function includeUser(Comment $comment)
    {
        return $this->item($comment->user, new UserTransformer());
    }
    
    public function includeDetail(Comment $comment)
    {
        return $this->item($comment->detail, new CommentDetailTransformer());
    }
}

