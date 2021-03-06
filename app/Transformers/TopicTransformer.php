<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Topic;
use App\Models\Forum;

class TopicTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user', 'forum', 'lastComment', 'mainFloor'];
    
    public function transform(Topic $topic)
    {
        $lastCommentTime = \Carbon::createFromTimestamp($topic->last_comment_time);
        return [
            'id' => $topic->id,
            'key' => $topic->key,
            'title' => $topic->title,
            'last_comment_time' => $lastCommentTime->format('Y-m-d H:i'),
            'last_comment_time_human' => $lastCommentTime->diffForHumans(),
            'created_at' => $topic->created_at->format('Y-m-d H:i'),
            'created_at_human' => $topic->created_at->diffForHumans(),
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
        $lastComment = $topic->lastComment;
        if ($lastComment->isNotEmpty())
        {
            return $this->item($lastComment, new CommentTransformer());
        }
    }
    public function includeMainFloor(Topic $topic)
    {
        return $this->item($topic->mainFloor, new CommentTransformer());
    }
}

