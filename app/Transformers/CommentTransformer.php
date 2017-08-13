<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Comment;
use App\Presenters\CommentPresenter;

class CommentTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['user'];
    
    protected $availableIncludes = ['detail', 'first_comments', 'attachments'];
    
    public function transform(Comment $comment)
    {
        return [
            'id' => $comment->id,
            'key' => $comment->id,
            'tid' => $comment->tid,
            'created_at' => $comment->created_at->format('Y-m-d H:i'),
            'created_at_human' => $comment->created_at->diffForHumans(),
            'floor_num' => $comment->floor_num,
            'floor_num_human' => app(CommentPresenter::class)->getFloorNumHuman($comment),
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
        $firstComments = $comment->first_comments;
        if ($firstComments)
        {
            return $this->collection($firstComments, new CommentCommentTransformer());
        }
    }
    
    public function includeAttachments(Comment $comment)
    {
        $attachments = $comment->attachments;
        if ($attachments)
        {
            return $this->collection($attachments, new AttachmentTransformer());
        }
    }
}

