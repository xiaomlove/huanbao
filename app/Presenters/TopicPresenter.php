<?php

namespace App\Presenters;

use App\Models\Topic;

class TopicPresenter
{
    public function getLastReply(Topic $topic)
    {
        if ($topic->lastComment_id == 0)
        {
            return '';
        }
        return sprintf(
            '<small>%s</small><small>%s</small>', 
            $topic->lastComment->user->name,
            $topic->lastComment->created_at->format('Y-m-d H:i')
        );
    }
}