<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CommentDetail;
use App\User;

class Comment extends Model
{
    protected $fillable = [
        'uid', 
        'tid',
        'pid', 
        'root_id', 
        'floor_num',
        'first_comment_ids',
        'comment_count',
        'like_count',
        'dislike_count',
        'favor_count',
    ];
    
    /**
     * 回复的详情
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function detail()
    {
        return $this->hasOne(CommentDetail::class, 'cid', 'id');
    }
    
    /**
     * 回复的作者
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'uid');
    }
    
    public function first_comments()
    {
        return $this->hasMany(__CLASS__, 'root_id', 'id');
    }
    
}
