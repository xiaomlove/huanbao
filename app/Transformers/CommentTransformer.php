<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Comment;
use App\Presenters\CommentPresenter;

class CommentTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['detail', 'firstComments', 'user', 'comments', 'parentComment'];
    
    public function transform(Comment $comment)
    {
        return [
            'id' => $comment->id,
            'key' => $comment->key,
            'tid' => $comment->tid,
            'created_at' => $comment->created_at->format('Y-m-d H:i'),
            'created_at_human' => $comment->created_at->diffForHumans(),
            'floor_num' => $comment->floor_num,
            'floor_num_human' => app(CommentPresenter::class)->getFloorNumHuman($comment),
            'is_first' => $comment->floor_num == 1 ? 1 : 0,
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
    
    public function includeFirstComments(Comment $comment)
    {
        $firstComments = $comment->firstComments;
        if ($firstComments->isNotEmpty())
        {
            return $this->collection($firstComments, $this);
        }
    }

    public function includeComments(Comment $comment)
    {
        $comments = $comment->comments;
        if ($comments->isNotEmpty())
        {
            return $this->collection($comments, $this);
        }
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

