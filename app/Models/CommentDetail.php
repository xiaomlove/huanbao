<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;

class CommentDetail extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['cid', 'content'];
    
    protected $touches = ['comment'];//联动更新comment()方法中模型
    
    public function comment()
    {
        return $this->belongsTo(Comment::class, 'cid', 'id');
    }
}
