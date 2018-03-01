<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Forum;
use App\Models\Comment;

class Topic extends Model
{
    protected $fillable = [
        'key',
        'title', 
        'fid', 
        'uid', 
        'view_count', 
        'comment_count', 
        'last_comment_time', 
        'last_comment_id', 
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
        return $this->belongsTo(Forum::class, 'fid', 'id')->withDefault([
            'id' => 0,
            'name' => '(不存在)',
        ]);
    }
    
    /**
     * 话题最后回复
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lastComment()
    {
        return $this->hasOne(Comment::class, 'id', 'last_comment_id');
    }
    
    /**
     * 话题主楼回复
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function mainFloor()
    {
        return $this->hasOne(Comment::class, 'tid', 'id')->where("floor_num", 1);
    }

    /**
     * 话题全部回复
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, "tid", "id");
    }
    
}
