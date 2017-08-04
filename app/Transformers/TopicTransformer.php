<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Topic;
use App\Models\Forum;

class TopicTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['user'];
    
    protected $availableIncludes = ['forum', 'last_comment', 'main_floor'];
    
    public function transform(Topic $topic)
    {
        $lastCommentTime = \Carbon::createFromTimestamp($topic->last_comment_time);
        return [
            'id' => $topic->id,
            'key' => $topic->id,
            'title' => $topic->title,
            'last_comment_time' => $topic->last_comment_time,
            'last_comment_time_human' => $lastCommentTime->diffForHumans(),
            'comment_count' => $topic->comment_count,
            'view_count' => $topic->view_count,
        ];
    }
    
    public function includeUser(Topic $topic)
    {
        return $this->item($topic->user, new UserTransformer());
    }
    
    public function includeForum(Topic $topic)
    {
        return $this->item($topic->forum, new ForumTransformer());
    }
    
    public function includeLastComment(Topic $topic)
    {
        $lastComment = $topic->last_comment;
        if ($lastComment)
        {
            return $this->item($lastComment, new CommentTransformer());
        }
    }
    public function includeMainFloor(Topic $topic)
    {
        return $this->item($topic->main_floor, new CommentCommentTransformer());
    }
}

