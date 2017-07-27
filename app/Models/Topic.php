<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Forum;
use App\Models\Comment;

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
    
    /**
     * 话题作者
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'uid');
    }
    
    /**
     * 主题所属版块
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function forum()
    {
        return $this->belongsTo(Forum::class, 'fid', 'id');
    }
    
    /**
     * 话题最后回复
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function last_comment()
    {
        return $this->hasOne(Comment::class, 'id', 'last_comment_id');
    }
    
    /**
     * 话题主楼
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function main_floor()
    {
        return $this->hasOne(Comment::class, 'tid', 'id');
    }
    
}