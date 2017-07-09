<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = [
        'title', 
        'fid', 
        'uid', 
        'view_count', 
        'comment_count', 
        'last_comment_time', 
        'last_comment_id', 
        'is_sticky',
    ];
    
    
}
