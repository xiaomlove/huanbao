<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'uid', 
        'tid',
        'pid', 
        'root_id', 
        'floor_num',
        'comment_count',
        'like_count',
        'dislike_count',
        'favor_count',
    ];
    
}
