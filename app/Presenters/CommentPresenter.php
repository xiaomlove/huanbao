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
    
    public function getPosition(Comment $comment)
    {
        $getFloorNumComment = $comment;
        $suffix = '';
        if ($getFloorNumComment->floor_num == -1 && $comment->rootComment)
        {
            $getFloorNumComment = $comment->rootComment;
            $suffix = '子回复';
        }
        $out = [];
        if ($suffix)
        {
            $out[] = $suffix;
        }
        $out[] = $this->getFloorNumHuman($getFloorNumComment);
        if ($comment->topic)
        {
            $out[] = str_limit($comment->topic->title, 20, '...');
            if ($comment->topic->forum)
            {
                $out[] = $comment->topic->forum->name;
            }
            else
            {
                $out[] = "[已删除版块({$comment->topic->fid})]";
            }
        }
        else 
        {
            $out[] = "[已删除话题({$comment->tid})]";
        }
        
        return implode(' -> ', array_reverse($out));
    }
}