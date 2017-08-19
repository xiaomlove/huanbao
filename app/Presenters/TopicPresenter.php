<?php

namespace App\Presenters;

use App\Models\Topic;

class TopicPresenter
{
    public function getLastReply(Topic $topic)
    {
        if ($topic->last_comment_id == 0)
        {
            return '';
        }
        return sprintf(
            '<small>%s</small><small>%s</small>', 
            $topic->last_comment->user->name,
            $topic->last_comment->created_at->format('Y-m-d H:i')
        );
    }
}