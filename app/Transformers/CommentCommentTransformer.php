<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Comment;

class CommentCommentTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [];
    
    protected $availableIncludes = ['user', 'parentComment', 'detail'];
    
    public function transform(Comment $comment)
    {
        return [
            'id' => $comment->id,
            'key' => $comment->key,
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

    public function includeParentComment(Comment $comment)
    {
        $parent = $comment->parentComment;
        if ($parent && $parent->floor_num == -1)
        {
            return $this->item($parent, $this);
        }
    }
}

