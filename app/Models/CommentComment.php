<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentComment extends Model
{
    protected $table = 'comment_comments';

    public function comment()
    {
        return $this->hasOne(Comment::class, "id", "cid");
    }

}
