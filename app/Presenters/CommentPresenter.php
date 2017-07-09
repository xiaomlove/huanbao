<?php

namespace App\Presenters;

use App\Models\Comment;

class CommentPresenter
{
    public function getEditLink(Comment $comment)
    {
        if ($comment->floor_num == 1)
        {
            return route('topic.edit', $comment->tid);
        }
        else 
        {
            return route('comment.edit', $comment->id);
        }
    }
    
    public function getFloorNumHuman(Comment $comment)
    {
        $floorNum = $comment->floor_num;
        switch ($floorNum)
        {
            case 1:
                return '主帖';
            case 2:
                return '沙发';
            case 3:
                return '板凳';
            case 4:
                return '地板';
            default:
                return "{$floorNum}楼";
        }
    }
}