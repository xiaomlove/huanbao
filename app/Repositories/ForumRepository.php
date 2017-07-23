<?php

namespace App\Repositories;

use App\Models\Forum;

class ForumRepository
{
    protected $forum;
    
    public function __construct(Forum $forum)
    {
        $this->forum = $forum;
    }
    
    public function listAll(array $params = [])
    {
        $defaults = [
            'max_depth' => null,
        ];
        $args = array_merge($defaults, $params);
        $tree = $this->forum->listTree($args);
        return normalize(0, 'OK', $tree);
    }
}