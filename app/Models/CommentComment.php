<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentComment extends Model
{
    const TABLE_NAME = 'comment_comments';

    protected $table = self::TABLE_NAME;

    public function comment()
    {
        return $this->hasOne(Comment::class, "id", "cid");
    }

}
