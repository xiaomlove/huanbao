<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Topic;
use App\Models\Forum;

class TopicTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['user'];
    
    protected $availableIncludes = ['forum', 'last_comment'];
    
    public function transform(Topic $topic)
    {
        return [
            'id' => $topic->id,
            'title' => $topic->title,
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
}

