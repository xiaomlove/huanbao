<?php
namespace App\Repositories;

use App\Models\Topic;

class TopicRepository
{
    protected $topic;
    
    public function __construct(Topic $topic)
    {
        $this->topic = $topic;
    }
    
    
}