<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CommentDetail;
use App\User;
use App\Models\Topic;
use App\Models\Attachment;
use App\Models\AttachmentRelationship;

class Comment extends Model
{
    const ADMIN_TOPIC_SHOW_PER_PAGE = 15;//话题下评论每页数量

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
    
    protected $touches = ['topic'];
    
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
    
    /**
     * 所属话题
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class, 'tid', 'id');
    }
    
    /**
     * 回复的回复
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(__CLASS__, 'root_id', 'id');
    }
    
    /**
     * 所属根评论
     */
    public function rootComment()
    {
        return $this->belongsTo(__CLASS__, 'root_id', 'id');
    }
    
    /**
     * 所属父评论
     */
    public function parentComment()
    {
        return $this->belongsTo(__CLASS__, 'pid', 'id');
    }

    /**
     * 楼中楼前几楼
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function firstComments()
    {
        return $this->belongsToMany(
            Comment::class,
            CommentComment::TABLE_NAME,
            "root_cid",
            "cid",
            "id",
            "id"
        );
    }
    
    /**
     * 评论所有的附件。target_type = 'comment'
     * 
     * @see App\Providers\AppServiceProvider::customMorphMap()
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function attachments()
    {
        return $this->morphToMany(
            Attachment::class,
            'target',
            AttachmentRelationship::TABLE_NAME,
            'target_id',
            'attachment_id'
        );
    }
    
}
